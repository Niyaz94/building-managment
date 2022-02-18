<?php
    require_once "header.php";
    $style=file_get_contents('../_general/style/pdf/payment.css');
    $mpdf =new \Mpdf\Mpdf([
        'tempDir' => __DIR__ . '/tmp',
        "mode" => "utf-8", 
        "format" => "A5", 
        "default_font_size" => "0", 
        "default_font" => "Arial",
        "margin_left" => "4",
        "margin_right" => "4",
        "margin_top" => "60", 
        "margin_bottom" => "10", 
        "margin_header" => "0", 
        "margin_footer" => "0", 
        "orientation" => "L"
    ]);
    $payment = $database->return_data3(array(
        'tablesName'=>array("'rent_payment'","'agreement'","'customer'"),
        'columnsName'=>array('*'),
        'conditions'=>array(
            array('columnName'=>'PNTID','operation'=>'=','value'=>$_GET['pntid'],'link'=>'and'),
            array('columnName'=>'PNTDeleted','operation'=>'=','value'=>0,'link'=>'and'),
            array('columnName'=>'CUSDeleted','operation'=>'=','value'=>0,'link'=>'and'),
            array('columnName'=>'AGRDeleted','operation'=>'=','value'=>0,'link'=>''),
        ),
        'others'=>'',
        'returnType'=>'key'
    ));
    extract($payment);
    $mpdf->setHTMLHeader('
        <div style="heigth:120px;">
            <div style="text-align:center;padding-top: 10px;">
                <img src="../_general/image/pdf/logo.jpeg" width="100px" heigth="60px" alt="">
            </div>
            <h2 style="text-align:center;padding-bottom:0px;margin-bottom:0px;">Royal Moll</h2>
            <div class="headerAddress" style="padding-bottom:0px;margin-bottom:0px;">Erbil - Shorsh - 60m st. Opposite to Haj Jalil Khayat Mosque</div>
            <div class="divline"></div>
            <table id="headerTable">
                <tr>
                    <td style="text-align:center;font-size:18px;font-weight:bold;color:red;">NO. '.$PNTInvoiceId.' ( '.$PNTDate.' )</td>
                    <td style="text-align:left;font-size:20px;font-weight:bold;color:red;">(Receipt)</td>
                    <td ></td>
                </tr>
            </table>
        </div>
    ');	
    $mpdf->WriteHTML($style,1);	

    $table='
        <table id="meanTable">
            <tbody>
                <tr>
                    <td width="25%" style="text-align:right;font-size:16px;font-weight:bold;">Received From Mr :</td>
                    <td width="50%" style="text-align:center;border-bottom:1px dashed black;">'.$CUSName.'</td>
                    <td width="25%" style="text-align:left;font-size:18px;font-weight:bold;" dir="rtl">أسملت من السيد :</td>
                </tr>
                <tr>
                    <td width="25%" style="text-align:right;font-size:15px;font-weight:bold;">Shop Name Leased :</td>
                    <td width="50%" style="text-align:center;border-bottom:1px dashed black;">'.$AGRShopTitle.'</td>
                    <td width="25%" style="text-align:left;font-size:16px;font-weight:bold;" dir="rtl">أسم المحل المستأجر :</td>
                </tr>
                <tr>
                    <td width="25%" style="text-align:right;font-size:18px;font-weight:bold;">The Amount :</td>
                    <td width="50%" style="text-align:center;border-bottom:1px dashed black;">'.changePrice($PNTTotalMoney).'</td>
                    <td width="25%" style="text-align:left;font-size:18px;font-weight:bold;" dir="rtl">مبلغ و قدره :</td>
                </tr>
                <tr>
                    <td width="25%" style="text-align:right;font-size:18px;font-weight:bold;">For :</td>
                    <td width="50%" style="text-align:center;border-bottom:1px dashed black;">'.html_entity_decode($PNTNote).'</td>
                    <td width="25%" style="text-align:left;font-size:18px;font-weight:bold;"  dir="rtl">و ذلك عن :</td>
                </tr>
               
            </tbody>
        </table>
    ';
    $tableBottomIQD='
        <table id="tableBottomIQD" style="height:80px;width:50%;margin:0 auto;">
            <thead>
                <tr>
                    <th style="font-size:18px;font-weight:bold;">
                        Dinar
                    </th>
                </tr>
            </thead>
            
            <tr>
                <td style="text-align:center;font-size:16px;" width="50%">'.changePrice($PNTTotalIQD).' IQD</td>
            </tr>
        </table>
    ';
    $tableBottomUSD='
        <table id="tableBottomUSD" style="height:80px;width:50%;margin:0 auto;">
            <thead>
                <tr>
                    <th style="font-size:18px;font-weight:bold;">
                        Dollar
                    </th>
                </tr>
            </thead>
            
            <tr>
                <td style="text-align:center;font-size:16px;" width="50%">'.changePrice($PNTTotalUSD).' USD</td>
            </tr>
        </table>
    ';
    $bottomPart='
        <div style="overflow:hidden;margin-top:12px;">
            <div style="float:left;width:50%;height:100px;justify-content:center;">
                '.$tableBottomUSD.'
            </div>
            <div style="float:right;width:50%;height:100px;align:center;">
                '.$tableBottomIQD.'
            </div>
        </div>
        <br>
        <div style="padding-left:15px;font-size:20px;font-weight:bold;">Signature</div>
    ';
    $mpdf->WriteHTML('
       '.$table.'
        '.$bottomPart.'
    ',2);  
    $mpdf->Output();
?>