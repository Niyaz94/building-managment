<?php
    require_once "header.php";
    $style=file_get_contents("../_general/style/pdf/report/rentMonthly.css");
    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        "mode" => "utf-8", 
        "format" => "A4", 
        "default_font_size" => "0", 
        "default_font" => "Arial",
        "margin_left" => "4",
        "margin_right" => "4",
        "margin_top" => "50", 
        "margin_bottom" => "10", 
        "margin_header" => "0", 
        "margin_footer" => "10", 
        "orientation" => "P"
    ]); 
    $mpdf->setHTMLHeader('
        <div>
            <div style="text-align:center;padding-top: 10px;">
                <img src="../_general/image/pdf/logo.jpeg" width="100px" heigth="80px" alt="">
            </div>
            <h2 style="text-align:center;padding-bottom:0px;margin-bottom:0px;">
                Monthly Rent
            </h2>           
        </div>
        <hr>
    ');	
    $mpdf->setFooter("|{PAGENO} of {nb}|");
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
    $agreementMonthlu=$database->return_data("
        SELECT
            CUSName,
            SOPNumber,
            AGRWorkype,
            AGRShopTitle,
            GROUP_CONCAT(ARTRentType,'_',ARTPaidType) as type
        FROM
            agreement,
            agreementrent,
            customer,
            shop
        WHERE
            AGRID=ARTAGRFORID AND
            SUBSTRING(ARTDate,1,7)='$month' AND
            SOPID=AGRSOPFORID AND
            CUSID=AGRCUSFORID AND
            CUSDeleted=0 AND
            AGRDeleted=0 AND
            AGRDeleted=0 AND
            ARTDeleted=0
        group BY
            AGRID
    ","key_all");

    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th>#</th>
                    <th>C Name</th>
                    <th>B Number</th>
                    <th>Working Type</th>
                    <th>Building Title</th>
                    <th>Area R</th>
                    <th>Service R</th>
                    <th>Electric R</th>
                </tr>
            </thead>
            <tbody>
    ';
    for($i=0;$i<count($agreementMonthlu);$i++){
        $rentType=array(
            "Not Paid",
            "Not Paid",
            "Not Paid"
        );
        $splitType=explode(",",$agreementMonthlu[$i]["type"]);
        for ($index = 0; $index < count($splitType); $index++) {
            $splitRent=explode("_",$splitType[$index]);
            if($splitRent[0]=="0"){
                if($splitRent[1]=="0"){
                    $rentType[0]="Not Paid";
                }else if($splitRent[1]=="1"){
                    $rentType[0]="Partial Paid";
                }else if($splitRent[1]=="2"){
                    $rentType[0]="Paid";
                }
            }
            if($splitRent[0]=="1"){
                if($splitRent[1]=="0"){
                    $rentType[1]="Not Paid";
                }else if($splitRent[1]=="1"){
                    $rentType[1]="Partial Paid";
                }else if($splitRent[1]=="2"){
                    $rentType[1]="Paid";
                }
            }
            if($splitRent[0]="2"){
                if($splitRent[1]=="0"){
                    $rentType[2]="Not Paid";
                }else if($splitRent[1]=="1"){
                    $rentType[2]="Partial Paid";
                }else if($splitRent[1]=="2"){
                    $rentType[2]="Paid";
                }
            }
        }
        $table.='
            <tr>
                <td >'.($i+1).'</td>
                <td >'.$agreementMonthlu[$i]["CUSName"].'</td>
                <td >'.$agreementMonthlu[$i]["SOPNumber"].'</td>
                <td >'.$agreementMonthlu[$i]["AGRWorkype"].'</td>
                <td >'.$agreementMonthlu[$i]["AGRShopTitle"].'</td>
                <td >'.$rentType[0].'</td>
                <td >'.$rentType[1].'</td>
                <td >'.$rentType[2].'</td>                      
            </tr>
        ';
    }
    $table.='
        </tbody>
        </table>
    ';
    $mpdf->WriteHTML('
       '.$table.'
    ',2);	
    $mpdf->Output();
?>
        

                        