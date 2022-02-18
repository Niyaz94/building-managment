<?php
    require_once "header.php";
    $style=file_get_contents("../_general/style/pdf/report/customerRentDetail.css");

    $agreement_name=$database->return_data("
        SELECT 
            AGRShopTitle,
            SOPArea
        FROM 
            agreement,
            shop
        WHERE 
            AGRID=".$_GET["agrid"]." and 
            AGRDeleted=0 and
            SOPDeleted=0 and
            SOPID=AGRSOPFORID
    ","key");
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
            <h2 style="text-align:center;padding-bottom:0px;margin-bottom:0px;">
                Customer Rent Detail
            </h2>    
            <div style="text-align:center;">
                <p style="font-size:24px;">'.$agreement_name["AGRShopTitle"].'</p>
                <p style="font-size:16px;">Area is '.$agreement_name["SOPArea"].'m</p>
                <span>Start Date : </span>
                <span>'.$_GET["startDate"].'</span>
                <span style="color:white;">AAAAAAAA</span>
                <span>End Date : </span>
                <span >'.$_GET["endDate"].'</span>
            </div>       
        </div>
        <hr>
    ');	
    $mpdf->setFooter("|{PAGENO} of {nb}|");
    $mpdf->WriteHTML($style,1);	

    $data=json_decode($_report->report5($_GET),true);



    $balance=$data[count($data)-1][2];
    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th>ID</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Debt</th>
                    <th>Pay</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
    ';
    $table.='
        <tr>
            <td colspan=6 style="background-color:#d86e6e;">Start Balance</td>
            <td style="padding:0px;margin:0px;">'.number_format($balance).'</td>
        </tr>
    ';
    for($i=0,$iL=count($data);$i<$iL;$i++){    
        if(isset($data[$i]["debt"])){
            $balance-=$data[$i]["debt"];
            $table.='
                <tr>
                    <td>'.($i+1).'</td>
                    <td>'.$data[$i]["type"].'</td>
                    <td>'.$data[$i]["date"].'</td>
                    <td>'.$data[$i]["note"].'</td>
                    <td style="padding:0px;margin:0px;">'.number_format($data[$i]["debt"]).'</td>
                    <td></td>
                    <td style="padding:6px 4px;margin:0px;">'.number_format($balance).'</td>
                </tr>
            ';
        }
        if(isset($data[$i]["payment"])){
            $balance+=$data[$i]["payment"];
            $table.='
                <tr>
                    <td>'.($i+1).'</td>
                    <td>'.$data[$i]["type"].'</td>
                    <td>'.$data[$i]["date"].'</td>
                    <td>'.$data[$i]["note"].'</td>
                    <td></td>
                    <td style="padding:0px;margin:0px;">'.number_format($data[$i]["payment"]).'</td>
                    <td style="padding:6px 4px;margin:0px;">'.number_format($balance).'</td>
                </tr>
            ';
        }                           
    }
    $table.='
        <tr>
            <td colspan=6 style="background-color:#d86e6e;">End Balance</td>
            <td style="padding:0px;margin:0px;border:0px;">'.number_format($balance).'</td>
        </tr>
    ';
    $table.='
        </tbody>
        </table>
    ';
    $mpdf->WriteHTML('
       '.$table.'
    ',2);	
    $mpdf->Output();
?>
        

                        