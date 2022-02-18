<?php
    require_once 'header.php';

    $style=file_get_contents("../_general/style/pdf/expenses.css");
    $mpdf =new \Mpdf\Mpdf([
        'tempDir' => __DIR__ . '/tmp',
        "mode" => "utf-8", 
        "format" => "A4", 
        "default_font_size" => "0", 
        "default_font" => "Arial",
        "margin_left" => "4",
        "margin_right" => "4",
        "margin_top" => "90", 
        "margin_bottom" => "10", 
        "margin_header" => "0", 
        "margin_footer" => "25", 
        "orientation" => "P"
    ]); 

    $expenses = $database->return_data3(array(
        "tablesName"=>array("'expenses'","'expenses_group'"),
        "columnsName"=>array("*"),
        "conditions"=>array(
            array("columnName"=>"EXPID","operation"=>"=","value"=>$_GET["expid"],"link"=>"and"),
            array("columnName"=>"EXPDeleted","operation"=>"=","value"=>0,"link"=>""),
        ),
        "others"=>"",
        "returnType"=>"key"
    ));
    extract($expenses);

    $IQD=0;
    $USD=0;
    if($EXPMoneyType==0){
        $IQD=0;
        $USD=$EXPMoney;
    }else{
        $IQD=$EXPMoney;
        $USD=0;
    }

    $mpdf->setHTMLHeader("
        <div style='heigth:200px;'>
            <div style='text-align:center;padding-top: 10px;'>
                <img src='../_general/image/pdf/logo.jpeg' width='100px' heigth='120px' alt=''>
            </div>
            <h1 style='text-align:center;padding-bottom:0px;margin-bottom:0px;'>Royal Moll</h1>
            <div class='headerAddress' style='padding-bottom:0px;margin-bottom:0px;'>Erbil - Shorsh - 60m st. Opposite to Haj Jalil Khayat Mosque</div>
            <br/>
            <table id='headerTable' dir='rtl'>
                <tr>
                    <td>رقم الوصل : $EXPID</td>
                    <td style='text-align:center;'>مستند صرف  (Voucher)</td>
                    <td ></td>
                </tr>
                <tr>
                    <td ></td>
                    <td></td>
                    <td dir='ltr'>Date : $EXPDate</td>
                </tr>
                <tr>
                    <td width='30%'>نوع المصارف و المدفوع له :</td>
                    <td width='40%' style='text-align:right;'>$EXGName</td>
                    <td width='30%' dir='ltr'>Paid To : $EXPForPerson</td>
                </tr>
            </table>
        </div>
        <hr>
    ");	
    $mpdf->WriteHTML($style,1);	


    $table="
        <table id='meanTable' dir='rtl'>
            <thead>
                <tr>
                    <th width='20%'>دینار (Dinar)</th>
                    <th width='20%'> دولار </th>
                    <th width='60%'>التفاصيل (Details)</th>
                </tr>
            </thead>
            <tbody>
                <tr id='firstRow'>
                    <td style='text-align:center;' rowspan=2>".changePrice($IQD)."</td>
                    <td style='text-align:center;'>".changePrice($USD)."</td>
                    <td style='text-align:center;'>".html_entity_decode($EXPNote)."</td>
                </tr>
                <tr>
                    <td style='text-align:center;' class='firstTdColor'>".$USD."</td>
                    <td style='text-align:center;' colspan=2>".($USD>0?convert_money($USD):"")."</td>
                </tr>
                <tr>
                    <td style='text-align:center;' class='firstTdColor'>".$IQD."</td>
                    <td style='text-align:center;' colspan=2>".($IQD>0?convert_money($IQD):"")."</td>
                </tr>
               
            
            </tbody>
        </table>
    ";
    $tableBottomPart="
        <table id='tableBottomPart' dir='rtl' style='height:100px;'>
            <tr>
                <td width='50%'>التوقيع (Signature)</td>
                <td width='50%'></td>
            </tr>
            <tr>
                <td width='50%' >اسم المستلم (Receiver)</td>
                <td width='50%'>$EXPForPerson</td>
            </tr>
        </table>
    ";
    $bottomPart="
        <div style='overflow:hidden;'>
            <div style='float:left;width:50%;height:100px;'>
                <div style='float:left;width:50%;height:100px;text-align:left;' dir='rtl'> المحاسب (Accountant)</div>
                <div style='float:right;width:50%;height:100px;'>
                <div style='text-align:right;padding-right:12px;' dir='rtl'> المدير (Manager)</div>
                </div>
            </div>
            <div style='float:right;width:50%;height:100px;'>
                $tableBottomPart
            </div>
        </div>
    ";
    $mpdf->WriteHTML("
        $table
        <br/>
        $bottomPart
    ",2);	
    //$mpdf->setHTMLFooter("<p>This is Footer</P>");
    $mpdf->Output();
    function convert_money($num){
        $f = new NumberFormatter("ar", NumberFormatter::SPELLOUT);
        return $f->format($num);
    }
?>