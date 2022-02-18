<?php
    require_once "header.php";
    $style=file_get_contents("../_general/style/pdf/report/monthlyExpense.css");
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
                Expenses For '.$_GET["month"].'
            </h2>           
        </div>
        <hr>
    ');	
    $mpdf->setFooter("|{PAGENO} of {nb}|");
    $mpdf->WriteHTML($style,1);	
    $data=json_decode($_report->report8($_GET["month"]),true);
   
    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th>#</th>
                    <th>Expense Type</th>
                    <th>USD Expense</th>
                    <th>IQD Expense</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_iqd=$total_usd=0;
    for($i=0,$iL=count($data);$i<$iL;$i++){    
        $table.='
            <tr>
                <td>'.($i+1).'</td>
                <td>'.$data[$i]["expense_type"].'</td>
                <td>'.number_format($data[$i]["usd"]).'</td>
                <td>'.number_format($data[$i]["iqd"]).'</td>
            </tr>
        ';
        $total_iqd+=$data[$i]["iqd"];
        $total_usd+=$data[$i]["usd"];

    }
    $table.='
        <tr style="background-color:#607d8b;color:white;">
            <td colspan=2>Total</td>
            <td>'.number_format($total_usd).'</td>
            <td>'.number_format($total_iqd).'</td>
        </tr>
    ';
    $table.='
        </tbody>
        </table>
    ';

    $mpdf->WriteHTML("
    ".$table."
    ",2);	
    $mpdf->Output();
?>
        

                        