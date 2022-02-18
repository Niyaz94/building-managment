
<?php
    require_once "header.php";
    $style=file_get_contents("../_general/style/pdf/report/report_all_round.css");
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
                <img src="logo.jpg" width="300px" heigth="180px" alt="">
            </div>
            <h2 style="text-align:center;padding-bottom:0px;margin-bottom:0px;">
                Blumont Kerosene Distribution Program 2018 – 2019
            </h2>           
        </div>
        <hr>
    ');	
    $mpdf->setFooter("|{PAGENO} of {nb}|");
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
    $table.="
    ";
    $mpdf->WriteHTML("
    ".$table."
    ",2);	
    $mpdf->Output();
?>
        

                        