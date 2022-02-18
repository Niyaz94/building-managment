<?php
    require_once "header.php";

    $style=file_get_contents('../_general/style/pdf/agreement.css');
    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        'mode' => 'utf-8', 
        'format' => 'A4', 
        'default_font_size' => '0', 
        'default_font' => 'Arial',
        'margin_left' => '4',
        'margin_right' => '4',
        'margin_top' => '105', 
        'margin_bottom' => '15', 
        'margin_header' => '0', 
        'margin_footer' => '15', 
        'orientation' => 'P'
    ]); 
    $mpdf->setFooter('|{PAGENO} of {nb}|');

    $agreement = $database->return_data3(array(
        'tablesName'=>array("'agreement'","'customer'"),
        'columnsName'=>array('agreement.*'),
        'conditions'=>array(
            array('columnName'=>'AGRID','operation'=>'=','value'=>$_GET['agrid'],'link'=>'and'),
            array('columnName'=>'CUSDeleted','operation'=>'=','value'=>0,'link'=>'and'),
            array('columnName'=>'AGRDeleted','operation'=>'=','value'=>0,'link'=>''),
        ),
        'others'=>'',
        'returnType'=>'key'
    ));

    $system_settings = $database->return_data2(array(
        "tablesName"=>array("system_settings"),
        "columnsName"=>array("*"),
        "conditions"=>array(
            array("columnName"=>"SYSID","operation"=>"=","value"=>1,"link"=>""),
        ),
        "others"=>"",
        "returnType"=>"key"
    ));
    extract($system_settings);

    $mpdf->setHTMLHeader('
        <div style="heigth:200px;">
            <table id="imageHeaderTable" style="">
                <tr style="padding-bottom:0px;">
                    <td style="text-align:left;vertical-align:text-top;padding-top:50px;font-size:36px;font-weight:bold;">Statement</td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:right;padding-top: 50px;">
                        <img src="../_general/image/pdf/logo.jpeg" width="150px" heigth="180px" alt="">
                    </td>
                </tr>
            </table>
            <div style="overflow:hidden;">
                <div style="float:left;width:35%;height:170px;">
                    <div style="text-align:left;padding-left:8px;padding-top:12px;font-size:18px;">Shop Name : '.(
                        strlen($agreement["AGRContactName"])>0?
                        $agreement["AGRContactName"]:
                        $agreement["AGRShopTitle"]
                    ).'</div>
                </div>
                <div style="float:right;width:65%;height:170px;">
                    <table id="headerTableAddress">
                        <tr>
                            <td width="40%" style="text-align:right;font-size:14px;padding-bottom:0px;border-right: 2px solid black;padding-right:14px;">Date</td>
                            <td width="60%" style="text-align:left;font-size:14px;padding-left:15px;">'.$SYSCompanyName.'</td>
                        </tr>
                        <tr>
                            <td width="40%" style="text-align:right;font-size:14px;padding-top:0px;border-right: 2px solid black;padding-right:14px;">'.date("Y-m-d").'</td>
                            <td width="60%" style="text-align:left;font-size:14px;padding-left:15px;">Iraq - Erbil</td>
                        </tr>
                        <tr>
                            <td width="40%" style="text-align:right;font-size:14px;border-right: 2px solid black;padding-right:14px;"></td>
                            <td width="60%" style="text-align:left;font-size:13px;padding-left:15px;">'.$SYSCompanyAddress.'</td>
                        </tr>
                        <tr>
                            <td width="40%" style="text-align:right;font-size:14px;padding-bottom:0px;border-right: 2px solid black;padding-right:14px;"></td>
                            <td width="60%" style="text-align:left;font-size:14px;padding-left:15px;">Phone : '.$SYSCompanyPhone.'</td>
                        </tr>
                        <tr>
                            <td width="40%" style="text-align:right;font-size:14px;padding-top:0px;border-right: 2px solid black;padding-right:14px;"></td>
                            <td width="60%" style="text-align:left;font-size:14px;padding-left:15px;">Mail : '.$SYSCompanyEmail	.'</td>
                        </tr>
                            
                    </table>
                </div>
            </div>
        </div>
        <hr>
    ');	
    $mpdf->WriteHTML($style,1);	

    $agreementrent = $database->return_data('
        select
            agreementrent.*,
            case
                when ARTPaidType=0 then 0
                when ARTPaidType=1 then (
                    SELECT
                        SUM(RPDMoney)
                    FROM
                        rent_payment_detail
                    WHERE
                        RPDARTFORID=ARTID AND
                        RPDDeleted=0
                )
            end as paidbefore
        from
            agreementrent   
        where
            ARTAGRFORID='.$_GET['agrid'].' and 
            ARTPaidType<>2 and
            ARTDeleted=0
    
    ','key_all');

    //echo "<pre>";
    //print_r($agreementrent);
    //echo "</pre>";
    //exit;

    $table='
        <table id="meanTable">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Item</th>
                    <th width="15%">Date</th>
                    <th width="15%">Rent Amount</th>
                    <th width="25%">Paid</th>
                    <th width="25%">Note</th>
                </tr>
            </thead>
            <tbody>
                <tbody>
    ';
    $total=0;
    for($i=0;$i<count($agreementrent);$i++){
        //echo count($agreementrent);
        $total+=(($agreementrent[$i]["ARTRent"]*(1-($agreementrent[$i]["ARTRentD"]/100)))-$agreementrent[$i]["paidbefore"]);
        $rent="";
        if($agreementrent[$i]["ARTRentType"]==0){
            $rent="Rent";
        }else if($agreementrent[$i]["ARTRentType"]==1){
            $rent="Service";
        }else if($agreementrent[$i]["ARTRentType"]==2){
            $rent="Electric";
        }
        $paid="";
        if($agreementrent[$i]["ARTPaidType"]==0){
            $paid="Not Paid";
        }else if($agreementrent[$i]["ARTPaidType"]==1){
            $paid="Partial Paid [ ".(($agreementrent[$i]["ARTRent"]*(1-($agreementrent[$i]["ARTRentD"]/100)))-$agreementrent[$i]["paidbefore"])." Remain]";
        }
        $table.='
            <tr>
                <td style="text-align:center;">'.($i+1).'</td>
                <td style="text-align:center;">'.$rent.'</td>
                <td style="text-align:center;">'.date("F/Y",strtotime($agreementrent[$i]["ARTDate"])).'</td>
                <td style="text-align:center;">'.changePrice(($agreementrent[$i]["ARTRent"]*(1-($agreementrent[$i]["ARTRentD"]/100)))).' USD</td>
                <td style="text-align:center;">'.$paid.'</td>
                <td style="text-align:center;">'.html_entity_decode($agreementrent[$i]["ARTNote"]).'</td>
            </tr>
        ';
    }
    $table.='
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align:right;font-size:24px;padding-right:100px;" colspan=5>Total</td>
                    <td style="text-align:center;font-size:24px;">'.changePrice($total).' USD</td>
                </tr>
            </tfoot>
        </table>
    ';
    
   
    $mpdf->WriteHTML('
       '. $table.'
    ',2);	
    //$mpdf->setHTMLFooter('<p>This is Footer</P>');

    $mpdf->Output();
?>