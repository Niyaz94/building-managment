<?php
    require_once "header.php";
    $style=file_get_contents("../_general/style/pdf/report/monthlyTotal.css");
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
                '.$_GET["month"].'
            </h2>           
        </div>
        <hr>
    ');	
    $data=json_decode($_report->report7($_GET["month"]),true);
    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th>#</th>
                    <th>Shop Name</th>
                    <th>Shop Rent</th>
                    <th>Monthly Rent</th>
                    <th>Service</th>
                    <th>Electric</th>
                    <th>Total</th>
                    <th>Month Debt</th>
                    <th>Total Debt</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_iqd=$total_usd=0;
    for($i=0,$iL=count($data);$i<$iL;$i++){    
        $table.='
            <tr>
                <td>'.($i+1).'</td>
                <td>'.$data[$i]["AGRShopTitle"].'</td>
                <td>'.number_format($data[$i]["AGRShopRental"]).'</td>
                <td>'.number_format($data[$i]["rent"]).'</td>
                <td>'.number_format($data[$i]["service"]).'</td>
                <td>'.number_format($data[$i]["electric"]).'</td>
                <td>'.number_format($data[$i]["electric"]+$data[$i]["rent"]+$data[$i]["service"]).'</td>
                <td>'.implode(" / ",explode(";",$data[$i]["date_rent"])).'</td>
                <td>'.number_format($data[$i]["totalDebt"]).'</td>
            </tr>
        ';
    }
    $table.='
        </tbody>
        </table>
    ';
    $mpdf->setFooter("|{PAGENO} of {nb}|");
    $mpdf->WriteHTML($style,1);	
    
    $mpdf->WriteHTML("
    ".$table."
    ",2);	
    $mpdf->Output();
?>
        

                        