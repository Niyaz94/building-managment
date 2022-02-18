<?php
    require_once "header.php";
    $style=file_get_contents("../_general/style/pdf/report/electricPerMonth.css");
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
        "orientation" => "L"
    ]); 
    $mpdf->setHTMLHeader('
        <div>
            <div style="text-align:center;padding-top: 10px;">
                <img src="../_general/image/pdf/logo.jpeg" width="100px" heigth="80px" alt="">
            </div>
            <h2 style="text-align:center;padding-bottom:0px;margin-bottom:0px;">
                Eletctric For '.$_GET["month"].'
            </h2>           
        </div>
        <hr>
    ');	
    $mpdf->setFooter("|{PAGENO} of {nb}|");
    $mpdf->WriteHTML($style,1);	
    extract($_GET);

    $agreement=$database->return_data("
        SELECT
            AGRID,
            AGRWorkype,
            AGRShopTitle,
            AGRSOPFORID,
            AGRCUSFORID,
            AGRWatchPaymentType,
            AGRElectricRental,
            SOPID,
            SOPNumber,
            SOPElectricRate
        FROM
            agreement,
            shop
        WHERE
            AGRDeleted=0 AND
            SOPDeleted=0 AND
            AGRSOPFORID=SOPID
    ","key_all");
    $agreementLength=count($agreement);
    for ($i=0; $i < $agreementLength; $i++) { 
        if($agreement[$i]["AGRWatchPaymentType"]==1){//if the watch rent monthly
            //not have three field we create them manualy
            $agreement[$i]["WTCName"]="Monthly";
            $agreement[$i]["WNTOldRead"]=0;
            $agreement[$i]["WNTNewRead"]=0;
            //return agreement rent for this month and must be ARTRentType equal to 2 that is mean electric type
            $agreementrent=$database->return_data("
                SELECT
                    ARTID,
                    ARTRent,
                    ARTRentD,
                    ARTPaidType
                FROM
                    agreementrent
                WHERE
                    ARTDeleted=0 AND
                    ARTAGRFORID='".$agreement[$i]["AGRID"]."' AND
                    SUBSTRING(ARTDate,1,7)='$month' AND
                    ARTRentType=2
            ","key");
            //this system paid the rent from old to new using month of agreement
            if($agreementrent["ARTPaidType"]==0) {//if not paid the rent
                $agreement[$i]["currentTotal"]=$agreementrent["ARTRent"]*(1-($agreementrent["ARTRentD"]/100));
                //maybe have old rent
                $OldID=$database->return_data("
                    SELECT
                        ifnull(GROUP_CONCAT(ARTID),0) as old
                    FROM
                        agreementrent
                    WHERE
                        ARTDeleted=0 AND
                        ARTAGRFORID='".$agreement[$i]["AGRID"]."' AND
                        SUBSTRING(ARTDate,1,7)<'$month' AND
                        ARTRentType=2
                ","key")["old"];
                $agreement[$i]["oldTotal"]=$database->return_data("
                    SELECT
                        (
                            IFNULL(sum(ARTRent*(1-(ARTRentD/100))),0)
                            -
                            IFNULL((
                                SELECT
                                    SUM(RPDMoney)
                                FROM
                                    rent_payment_detail
                                WHERE
                                    RPDDeleted=0 and
                                    RPDARTFORID in ($OldID)
                            ),0)
                        ) as totalDebt
                    FROM
                        agreementrent
                    WHERE
                        ARTDeleted=0 and 
                        ARTID in ($OldID)
                ","key")["totalDebt"]*1;
            }else if($agreementrent["ARTPaidType"]==1) {
                $current=$agreementrent["ARTRent"]*(1-($agreementrent["ARTRentD"]/100));
                $old=$database->return_data("
                    SELECT
                        sum(RPDMoney) as total
                    FROM
                        rent_payment_detail
                    WHERE
                        RPDDeleted=0 AND
                        RPDARTFORID=".$agreementrent["ARTID"]."
                ","key")["total"];
                //here the user paid partialy his rent for this we find the remain
                $agreement[$i]["currentTotal"]=$current-$old;
                //always zero because system paid from old to new 
                $agreement[$i]["oldTotal"]=0;
            }else if($agreementrent["ARTPaidType"]==2) {//if paid the rent
                $agreement[$i]["currentTotal"]=0;
                $agreement[$i]["oldTotal"]=0;
            }
        }else if($agreement[$i]["AGRWatchPaymentType"]==0){//if the warch rent for each watch
            //au query error tedaea chunka hech relasionship ka laneuan watch u agreementrent
            $agreementrent=$database->return_data("
                SELECT
                    WTCName,
                    ARTPaidType,
                    ifnull((
                        SELECT
                            concat(WNTOldRead,',',WNTNewRead)
                        FROM
                            watchrent
                        WHERE
                            WNTDeleted=0 AND
                            WNTWTCFORID=WTCID AND
                            WNTARTFORID=ARTID
                    ),0) as readData,
                    ARTRent,
                    ARTRentD,
                    WTCID
                FROM
                    watch,
                    agreementrent
                WHERE
                    WTCDeleted=0 AND
                    ARTDeleted=0 AND
                    WTCSOPFORID='".$agreement[$i]["SOPID"]."' AND
                    ARTAGRFORID='".$agreement[$i]["AGRID"]."' AND
                    SUBSTRING(ARTDate,1,7)='$month' AND
                    ARTRentType=2
            ","key_all");
            $agreementrentLength=count($agreementrent);
            $addingTowatch=array(
                "WTCName"=>array(),
                "WNTOldRead"=>array(),
                "WNTNewRead"=>array(),
                "currentTotal"=>array(),
                "oldTotal"=>array()
            );
            for ($j=0; $j < $agreementrentLength; $j++) { 
                if($agreementrent[$j]["readData"]=="0"){
                    continue;
                }else{
                    $split=explode(",",$agreementrent[$j]["readData"]);
                    array_push($addingTowatch["WTCName"],$agreementrent[$j]["WTCName"]);
                    array_push($addingTowatch["WNTOldRead"],$split[0]);
                    array_push($addingTowatch["WNTNewRead"],$split[1]);
                    if($agreementrent[$j]["ARTPaidType"]==0) {//if not paid the rent
                        $OldID=$database->return_data("
                            SELECT
                                ifnull(GROUP_CONCAT(ARTID),0) as old
                            FROM
                                agreementrent,
                                watchrent
                            WHERE
                                WNTDeleted=0 AND
                                ARTID=WNTARTFORID AND
                                WNTWTCFORID='".$agreementrent[$j]["WTCID"]."' AND
                                ARTDeleted=0 AND
                                ARTAGRFORID='".$agreement[$i]["AGRID"]."' AND
                                SUBSTRING(ARTDate,1,7)<'$month' AND
                                ARTRentType=2
                        ","key")["old"];
                        $oldTotal=$database->return_data("
                            SELECT
                                (
                                    IFNULL(sum(ARTRent*(1-(ARTRentD/100))),0)
                                    -
                                    IFNULL((
                                        SELECT
                                            SUM(RPDMoney)
                                        FROM
                                            rent_payment_detail
                                        WHERE
                                            RPDDeleted=0 and
                                            RPDARTFORID in ($OldID)
                                    ),0)
                                ) as totalDebt
                            FROM
                                agreementrent
                            WHERE
                                ARTDeleted=0 and 
                                ARTID in ($OldID)
                        ","key")["totalDebt"]*1;
                        array_push($addingTowatch["currentTotal"],($agreementrent[$j]["ARTRent"]*(1-($agreementrent[$j]["ARTRentD"]/100))));
                        array_push($addingTowatch["oldTotal"],$oldTotal);
                    }else if($agreementrent[$j]["ARTPaidType"]==1) {
                        $current=$agreementrent[$i]["ARTRent"]*(1-($agreementrent[$j]["ARTRentD"]/100));
                        $old=$database->return_data("
                            SELECT
                                sum(RPDMoney) as total
                            FROM
                                rent_payment_detail
                            WHERE
                                RPDDeleted=0 AND
                                RPDARTFORID=".$agreementrent[$j]["ARTID"]."
                        ","key")["total"];
                        array_push($addingTowatch["currentTotal"],($current-$old));
                        array_push($addingTowatch["oldTotal"],0);
                    }else if($agreementrent[$i]["ARTPaidType"]==2) {//if paid the rent
                        array_push($addingTowatch["currentTotal"],0);
                        array_push($addingTowatch["oldTotal"],0);
                    }
                }
            }
            $agreement[$i]["WTCName"]=$addingTowatch["WTCName"];
            $agreement[$i]["WNTOldRead"]=$addingTowatch["WNTOldRead"];
            $agreement[$i]["WNTNewRead"]=$addingTowatch["WNTNewRead"];
            $agreement[$i]["currentTotal"]=$addingTowatch["currentTotal"];
            $agreement[$i]["oldTotal"]=$addingTowatch["oldTotal"];
        } 
    }
    $table='
        <table  id="meanTable" dir="rtl">
            <thead>
                <tr class="border-double bg-blue">
                    <th>ت</th>
                    <th>اسم المحل</th>
                    <th>تسلسل الساعة</th>
                    <th>القراءة السابقة</th>
                    <th>القراءة الجديدة</th>
                    <th>فرق القرااتين</th>
                    <th>سعر الوحدة</th>
                    <th>الوحدة / المبلغ</th>
                    <th>الديون السابقة</th>
                    <th>المبلغ الكلي</th>
                </tr>
            </thead>
            <tbody>
    ';
    for($i=0;$i<count($agreement);$i++){
        if(is_array($agreement[$i]["WNTNewRead"])){
            for ($j = 0; $j < count($agreement[$i]["WNTNewRead"]); $j++) {   
                $table.='
                    <tr>
                        <td>'.($i+1).'</td>
                        <td>'.$agreement[$i]["AGRShopTitle"].'</td>
                        <td>'.$agreement[$i]["WTCName"][$j].'</td>
                        <td>'.$agreement[$i]["WNTOldRead"][$j].'</td>
                        <td>'.$agreement[$i]["WNTNewRead"][$j].'</td>
                        <td>'.($agreement[$i]["WNTNewRead"][$j]-$agreement[$i]["WNTOldRead"][$j]).'</td>
                        <td>'.$agreement[$i]["SOPElectricRate"].'</td>
                        <td>'.$agreement[$i]["currentTotal"][$j].'</td>
                        <td>'.$agreement[$i]["oldTotal"][$j].'</td>
                        <td>'.($agreement[$i]["currentTotal"][$j]+$agreement[$i]["oldTotal"][$j]).'</td>
                    </tr>
                ';                             
            }
        }else{
            $table.='
                <tr>
                    <td>'.($i+1).'</td>
                    <td>'.$agreement[$i]["AGRShopTitle"].'</td>
                    <td>'.$agreement[$i]["WTCName"].'</td>
                    <td>'.$agreement[$i]["WNTOldRead"].'</td>
                    <td>'.$agreement[$i]["WNTNewRead"].'</td>
                    <td>'.($agreement[$i]["WNTNewRead"]-$agreement[$i]["WNTOldRead"]).'</td>
                    <td>'.$agreement[$i]["SOPElectricRate"].'</td>
                    <td>'.$agreement[$i]["currentTotal"].'</td>
                    <td>'.$agreement[$i]["oldTotal"].'</td>
                    <td>'.($agreement[$i]["currentTotal"]+$agreement[$i]["oldTotal"]).'</td>
                </tr>
            ';       
        }
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
        

                        