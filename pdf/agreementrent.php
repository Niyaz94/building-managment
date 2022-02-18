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
                    <td style="text-align:left;vertical-align:text-top;padding-top:50px;font-size:36px;font-weight:bold;">Invoice</td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:right;padding-top: 50px;">
                        <img src="../_general/image/pdf/logo.jpeg" width="150px" heigth="180px" alt="">
                    </td>
                </tr>
            </table>
            <div style="overflow:hidden;">
                <div style="float:left;width:35%;height:170px;">
                    <div style="text-align:left;padding-left:8px;padding-top:12px;font-size:18px;">Shop Name : '.(
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
                            <td width="40%" style="text-align:right;font-size:14px;border-right: 2px solid black;padding-right:14px;">Invoice Number</td>
                            <td width="60%" style="text-align:left;font-size:13px;padding-left:15px;">'.$SYSCompanyAddress.'</td>
                        </tr>
                        <tr>
                            <td width="40%" style="text-align:right;font-size:14px;padding-bottom:0px;border-right: 2px solid black;padding-right:14px;">'.$_GET['agrid'].'</td>
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
    $mpdf->setHTMLFooter('
        <div style="text-align:right;">
            <h1>Management</h1>
        </div>
    ');
    $table="";
    if($agreement["AGRWatchPaymentType"]==0 && $_GET["ARTType"]==2){

        $watch_data = $database->return_data('
            SELECT
                WNTOldRead as oldRead,
                WNTNewRead as newRead,
                substring_index(substring_index(WNTRegisterDate," ",1)," ",-1) as watchDate
            FROM
                watchrent
            WHERE
                WNTDeleted=0 AND
                WNTARTFORID='.$_GET["artid"].'
        ','key');
        $shop_rent = $database->return_data('
            SELECT
                SOPElectricRate
            FROM
                shop
            WHERE
                SOPDeleted=0 AND
                SOPID='.$agreement["AGRSOPFORID"].'
        ','key');
        $oldDebt=$database->return_data("
            SELECT
                sum(
                    (ARTRentAftDis)-
                    IFNULL((
                        SELECT
                            SUM(RPDMoney)
                        FROM
                            rent_payment_detail
                        WHERE
                            RPDDeleted=0 and
                            RPDARTFORID=ARTID
                    ),0)
                ) as totalDebt
            FROM
                agreementrent
            WHERE
                ARTRentType=2 and
                ARTDeleted=0 and 
                ARTDate<'".$_GET['ARTDate']."' and
                ARTAGRFORID=".$_GET["agrid"]."
        ","key")["totalDebt"];
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
                ARTDate="'.$_GET['ARTDate'].'" and 
                ARTID="'.$_GET['artid'].'" and 
                ARTPaidType<>2 and
                ARTDeleted=0
        ','key_all');
        $table.='
            <table id="meanTable">
                <thead>
                    <tr>
                        <th width="12%">Item</th>
                        <th width="10%">Old Read</th>
                        <th width="12%">New Read</th>
                        <th width="8%">Unit Price</th>
                        <th width="12%">Date</th>
                        <th width="15%">Rent Amount</th>
                        <th width="11%">Paid</th>
                        <th width="20%">Note</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
        ';
        $total=0;
        for($i=0;$i<count($agreementrent);$i++){
            $total+=($agreementrent[$i]["ARTRentAftDis"]-$agreementrent[$i]["paidbefore"]);
          
            $paid="";
            if($agreementrent[$i]["ARTPaidType"]==0){
                $paid="Not Paid";
            }else if($agreementrent[$i]["ARTPaidType"]==1){
                $paid="Partial Paid [ ".($agreementrent[$i]["ARTRentAftDis"]-$agreementrent[$i]["paidbefore"])." Remain]";
            }
            $table.='
                <tr>
                    <td style="text-align:center;">Electric</td>
                    <td style="text-align:center;">'.$watch_data["oldRead"].'</td>
                    <td style="text-align:center;">'.$watch_data["newRead"].'</td>
                    <td style="text-align:center;">'.$shop_rent["SOPElectricRate"].'</td>
                    <td style="text-align:center;">'.date("M / Y", strtotime("+1 month",strtotime($watch_data["watchDate"]))).'</td>
                    <td style="text-align:center;">'.changePrice($agreementrent[$i]["ARTRentAftDis"]).' USD</td>
                    <td style="text-align:center;">'.$paid.'</td>
                    <td style="text-align:center;">'.html_entity_decode($agreementrent[$i]["ARTNote"]).'</td>
                </tr>
            ';
        }
        $table.='
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align:right;font-size:16px;padding-right:100px;" colspan=6>Total</td>
                        <td style="text-align:center;font-size:16px;" colspan=2>'.changePrice($total).' USD</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-size:16px;padding-right:100px;" colspan=6>Debt</td>
                        <td style="text-align:center;font-size:16px;" colspan=2>'.changePrice($oldDebt).' USD</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-size:16px;padding-right:100px;" colspan=6>GT</td>
                        <td style="text-align:center;font-size:16px;" colspan=2>'.changePrice($total+$oldDebt).' USD</td>
                    </tr>
                </tfoot>
            </table>
        ';
    }else{
        $oldDebt=$database->return_data("
            SELECT
                sum(
                    (ARTRentAftDis)-
                    IFNULL((
                        SELECT
                            SUM(RPDMoney)
                        FROM
                            rent_payment_detail
                        WHERE
                            RPDDeleted=0 and
                            RPDARTFORID=ARTID
                    ),0)
                ) as totalDebt
            FROM
                agreementrent
            WHERE
                ARTRentType ".($_GET["ARTType"]==2?"=2":"<>2")." and
                ARTDeleted=0 and 
                ARTDate<'".$_GET['ARTDate']."' and
                ARTAGRFORID=".$_GET["agrid"]."
        ","key")["totalDebt"];
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
                ARTDate="'.$_GET['ARTDate'].'" and 
                ARTAGRFORID="'.$_GET['agrid'].'" and 
                ARTPaidType<>2 and
                ARTDeleted=0
        ','key_all');
        $table.='
            <table id="meanTable">
                <thead>
                    <tr>
                        <th width="15%">Item</th>
                        <th width="15%">Date</th>
                        <th width="15%">Rent Amount</th>
                        <th width="25%">Paid</th>
                        <th width="30%">Note</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
        ';
        $total=0;
        for($i=0;$i<count($agreementrent);$i++){
            if(($_GET["ARTType"]==2 && ($agreementrent[$i]["ARTRentType"]==0 || $agreementrent[$i]["ARTRentType"]==1) ) || (($_GET["ARTType"]==0 || $_GET["ARTType"]==1) && $agreementrent[$i]["ARTRentType"]==2)){
                continue;
            }
            //echo count($agreementrent);
            $total+=($agreementrent[$i]["ARTRentAftDis"]-$agreementrent[$i]["paidbefore"]);
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
                $paid="Partial Paid [ ".($agreementrent[$i]["ARTRentAftDis"]-$agreementrent[$i]["paidbefore"])." Remain]";
            }
            $table.='
                <tr>
                    <td style="text-align:center;">'.$rent.'</td>
                    <td style="text-align:center;">'.date("F/Y",strtotime($_GET["ARTDate"])).'</td>
                    <td style="text-align:center;">'.changePrice($agreementrent[$i]["ARTRentAftDis"]).' USD</td>
                    <td style="text-align:center;">'.$paid.'</td>
                    <td style="text-align:center;">'.html_entity_decode($agreementrent[$i]["ARTNote"]).'</td>
                </tr>
            ';
        }
        $table.='
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align:right;font-size:16px;padding-right:100px;" colspan=4>Total</td>
                        <td style="text-align:center;font-size:16px;" colspan=2>'.changePrice($total).' USD</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-size:16px;padding-right:100px;" colspan=4>Debt</td>
                        <td style="text-align:center;font-size:16px;">'.changePrice($oldDebt).' USD</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-size:16px;padding-right:100px;" colspan=4>GT</td>
                        <td style="text-align:center;font-size:16px;">'.changePrice($total+$oldDebt).' USD</td>
                    </tr>
                </tfoot>
            </table>
        ';
    }
    
    $mpdf->WriteHTML('
       '. $table.'
    ',2);	
    $mpdf->Output();
?>