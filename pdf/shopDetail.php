<?php
    require_once "header.php";
    $style=file_get_contents("../_general/style/pdf/report/shopDetail.css");
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
                Shop Detail
            </h2>           
        </div>
        <hr>
    ');	
    $mpdf->setFooter("|{PAGENO} of {nb}|");
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
   
    $shop=$database->return_data("
        SELECT
            *
        FROM
            shop
        WHERE
            ".($buildType==3?"":"SOPCategory=".$buildType." and ")."
            ".($buildStiuation==2?"":($buildStiuation==1?"SOPType=1 and ":"SOPType<>1 and "))."
        SOPDeleted=0
    ","key_all");

    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th>#</th>
                    <th>B Number</th>
                    <th>B Floor</th>
                    <th>B Area</th>
                    <th>B Type</th>
                    <th>B Situation</th>
                    <th>Electric</th>
                </tr>
            </thead>
            <tbody>
    ';
    for($i=0;$i<count($shop);$i++){
        $SOPCategory="";
        if($shop[$i]["SOPCategory"]==0){
            $SOPCategory="Shop";
        }else if($shop[$i]["SOPCategory"]==1){
            $SOPCategory="Stand";
        }else if($shop[$i]["SOPCategory"]==2){
            $SOPCategory="Office";
        }
        $SOPType="";
        if($shop[$i]["SOPType"]==0 || $shop[$i]["SOPType"]==2){
            $SOPType="Empty";
        }else if($shop[$i]["SOPType"]==1){
            $SOPType="Full";
        }
        $table.='
            <tr>
                <td >'.($i+1).'</td>
                <td >'.$shop[$i]["SOPNumber"].'</td>
                <td >'.$shop[$i]["SOPFloor"].'</td>
                <td >'.$shop[$i]["SOPArea"].'</td>
                <td >'.$SOPCategory.'</td>
                <td >'.$SOPType.'</td>
                <td >'.$shop[$i]["SOPElectricRate"].'</td>
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
        

                        