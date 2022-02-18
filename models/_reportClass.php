<?php
	class _reportClass{
        private $database;
		public function __construct($database){
            $this->database=$database;
        }
        function report4($data){
            extract($data);
            $agreement=$this->database->return_data("
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
                    $agreementrent=$this->database->return_data("
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
                        $agreement[$i]["currentTotal"]=number_format($agreementrent["ARTRent"]*(1-($agreementrent["ARTRentD"]/100)),0,'.','');
                        //maybe have old rent
                        $OldID=$this->database->return_data("
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
                        $agreement[$i]["oldTotal"]=$this->database->return_data("
                            SELECT
                                (
                                    IFNULL(sum(ARTRentAftDis),0)
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
                        $current=number_format($agreementrent["ARTRent"]*(1-($agreementrent["ARTRentD"]/100)),0,'.','');
                        $old=$this->database->return_data("
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
                }else if($agreement[$i]["AGRWatchPaymentType"]==0){//if the watch rent for each watch

                    //au query error tedaea chunka hech relasionship ka laneuan watch u agreementrent
                    $agreementrent=$this->database->return_data("
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
                                $OldID=$this->database->return_data("
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
                                $oldTotal=$this->database->return_data("
                                    SELECT
                                        (
                                            IFNULL(sum(ARTRentAftDis),0)
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
                                array_push($addingTowatch["currentTotal"],(number_format($agreementrent[$j]["ARTRent"]*(1-($agreementrent[$j]["ARTRentD"]/100)),0,'.','')));
                                array_push($addingTowatch["oldTotal"],$oldTotal);
                            }else if($agreementrent[$j]["ARTPaidType"]==1) {
                                $current=number_format($agreementrent[$i]["ARTRent"]*(1-($agreementrent[$j]["ARTRentD"]/100)),0,'.','');
                                $old=$this->database->return_data("
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
                            }else if($agreementrent[$j]["ARTPaidType"]==2) {//if paid the rent
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
            echo json_encode($agreement);
        }
        function report5($data){
            //price in contract
            //area
            //namee of rent
            extract($data);
            $payment_detail=$this->database->return_data("
                SELECT
                    RPDMoney as payment,
                    PNTNote as note,
                    case ARTRentType
                        when 0 then 'Rent'
                        when 1 then 'Service'
                        when 2 then 'Electronic'
                    end as type,
                    substring_index(substring_index(RPDRegisterDate,' ',1),' ',-1) as date
                FROM
                    rent_payment_detail,
                    agreementrent,
                    rent_payment
                WHERE
                    RPDDeleted=0 AND
                    ARTDeleted=0 AND
                    PNTDeleted=0 AND
                    ARTID=RPDARTFORID AND
                    PNTID=RPDPNTFORID AND
                    substring_index(substring_index(RPDRegisterDate,' ',1),' ',-1) between '".$startDate."' and '".$endDate."' and 
                    ARTAGRFORID=".$agrid."
            ","key_all");
            $payment_total=$this->database->return_data("
                SELECT
                    sum(RPDMoney) as total
                FROM
                    rent_payment_detail,
                    agreementrent
                WHERE
                    RPDDeleted=0 AND
                    ARTDeleted=0 AND
                    ARTID=RPDARTFORID AND
                    substring_index(substring_index(RPDRegisterDate,' ',1),' ',-1) < '".$startDate."' and 
                    ARTAGRFORID=".$agrid."
            ","key")["total"];
            $rent_detail=$this->database->return_data("
                SELECT
                    ARTRentAftDis as debt,
                    ARTDate as date,
                    ARTNote as note,
                    case ARTRentType
                        when 0 then 'Rent'
                        when 1 then 'Service'
                        when 2 then 'Electronic'
                    end as type
                FROM
                    agreementrent
                WHERE
                    ARTFutureType=0 AND
                    ARTDeleted=0 AND
                    ARTDate between '".$startDate."' and '".$endDate."' AND
                    ARTAGRFORID=".$agrid."
            ","key_all");
            $rent_total=$this->database->return_data("
                SELECT
                    sum(ARTRentAftDis) as total
                FROM
                    agreementrent
                WHERE
                    ARTFutureType=0 AND
                    ARTDeleted=0 AND
                    ARTDate < '".$startDate."' AND
                    ARTAGRFORID=".$agrid."
            ","key")["total"];
            $array_detail=array_merge($rent_detail,$payment_detail);
            usort($array_detail, function($a, $b) {
                return new DateTime($a['date']) <=> new DateTime($b['date']);
            });
            for ($i=0,$il=count($array_detail); $i < $il; $i++) { 
                $array_detail[$i]["note"]=html_entity_decode($array_detail[$i]["note"]);
            }
            array_push($array_detail,array($payment_total,$rent_total,$payment_total-$rent_total));
            return json_encode($array_detail);
        }
        function report6($data){
            extract($data);
            return json_encode($this->database->return_data("
                SELECT
                    AGRShopTitle,
                    ARTDate,
                    ARTRentAftDis as total,
                    case ARTRentType
                        when 0 then 'Rent'
                        when 1 then 'Service'
                        when 2 then 'Electronic'
                    end as ARTRentType
                FROM
                    agreement,
                    agreementrent
                WHERE
                    ".($agrid==0?"":" ARTAGRFORID=".$agrid." AND ")."
                    ".($rentType==3?"":" ARTRentType=".$rentType." AND ")."
                    ARTDate between '".$startDate."' and '".$endDate."' and
                    ARTDeleted=0 and
                    ARTRentAftDis>0 and
                    AGRDeleted=0 and
                    ARTPaidType=2 and
                    AGRID=ARTAGRFORID
            ","key_all"));
        }
        function report7($date){
            $data=$this->database->return_data("
                SELECT
                    AGRID,
                    SOPArea,
                    SOPFloor,
                    AGRShopTitle,
                    AGRShopRental,
                    GROUP_CONCAT(
                        CONCAT(ARTRentAftDis, '_', ARTRentType,'_',ARTRent) SEPARATOR ';'
                    ) as month_rent,
                    (
                        SELECT
                            GROUP_CONCAT(SUBSTRING(ARTDate, 1, 7) SEPARATOR ';')
                        FROM
                            agreementrent
                        WHERE
                            ARTDeleted=0 AND
                            ARTRentType=0 AND
                            ARTPaidType<>2 AND
                            ARTAGRFORID=AGRID
                    ) as date_rent,
                    (
                        IFNULL((
                            SELECT
                                sum(ARTRentAftDis)
                            FROM
                                agreementrent
                            WHERE
                                ARTDeleted=0 and 
                                ARTAGRFORID=AGRID
                        ),0)
                        -
                        IFNULL((
                            SELECT
                                SUM(PNTTotalMoney)
                            FROM
                                rent_payment
                            WHERE
                                PNTDeleted=0 and
                                PNTAGRFORID=AGRID
                        ),0)
                    ) as totalDebt
                FROM
                    agreement,
                    agreementrent,
                    shop
                WHERE
                    ARTDeleted = 0 AND 
                    AGRDeleted = 0 AND 
                    SOPDeleted = 0 AND 
                    AGRID = ARTAGRFORID AND 
                    SOPID = AGRSOPFORID AND 
                    SUBSTRING(ARTDate, 1, 7) = '$date'
                GROUP BY
                    AGRID
                order by
                    AGROrderRow
            ","key_all");
            for ($i=0,$iL=count($data); $i < $iL; $i++) { 
                $data[$i]["rent"]=0;
                $data[$i]["service"]=0;
                $data[$i]["electric"]=0;
                $rent=explode(";",$data[$i]["month_rent"]);
                for ($j=0,$jL=count($rent); $j < $jL ; $j++) { 
                    $split=explode("_",$rent[$j]);
                    if($split[1]==0){
                        $data[$i]["rent"]=$split[0];
                        $data[$i]["rentBasic"]=$split[2];
                    }else if($split[1]==1){
                        $data[$i]["service"]=$split[0];
                        $data[$i]["serviceBasic"]=$split[2];
                    }else if($split[1]==2){
                        $data[$i]["electric"]=$split[0];
                        $data[$i]["electricBasic"]=$split[2];
                    }
                }
            }
            return json_encode($data);
        }
        function report8($date){
            $return_data=$this->database->return_data("
                SELECT
                    EXGName,
                    SUM(EXPMoney) as total,
                    EXPMoneyType,
                    0 as state
                FROM
                    expenses,
                    expenses_group
                WHERE
                    EXGDeleted=0 AND
                    EXPDeleted=0 AND
                    EXGID=EXPEXGFORID AND
                    SUBSTRING(EXPDate, 1, 7) = '$date'
                GROUP BY
                    EXGID,EXPMoneyType
            ","key_all");
            $new_array=[];
            for ($i=0,$iL=count($return_data); $i < $iL; $i++) { 
                for ($j=$i+1,$jL=count($return_data); $j < $jL; $j++) { 
                    if($return_data[$i]["EXGName"]==$return_data[$j]["EXGName"] &&  $return_data[$i]["state"]==0){
                        array_push($new_array,[
                            "expense_type"=>$return_data[$i]["EXGName"],
                            ($return_data[$i]["EXPMoneyType"]==0?"usd":"iqd")=>$return_data[$i]["total"],
                            ($return_data[$j]["EXPMoneyType"]==0?"usd":"iqd")=>$return_data[$j]["total"]
                        ]);
                        $return_data[$i]["state"]=1;
                        $return_data[$j]["state"]=1;
                    }
                }
                if($return_data[$i]["state"]==0){
                    array_push($new_array,[
                        "expense_type"=>$return_data[$i]["EXGName"],
                        ($return_data[$i]["EXPMoneyType"]==0?"usd":"iqd")=>$return_data[$i]["total"],
                        ($return_data[$i]["EXPMoneyType"]!=0?"usd":"iqd")=>0                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
                    ]);
                }
            }
            return json_encode($new_array);                                                                                                                                                                                                                                                                                                                                         
        }
        function report9($date){  
            $base_usd=$this->database->return_data("
                SELECT
                    CPTMoney
                FROM
                    capital
                WHERE
                	CPTMoneyType=0 and
                    CPTTransactionType=2 AND
                    CPTDeleted=0
            ","key")["CPTMoney"];
            $base_iqd=$this->database->return_data("
                SELECT
                    CPTMoney
                FROM
                    capital
                WHERE
                	CPTMoneyType=1 and
                    CPTTransactionType=2 AND
                    CPTDeleted=0
            ","key")["CPTMoney"];
            $today_expense=$this->database->return_data("
                SELECT
                    EXPID,EXPMoney,EXPMoneyType,EXPNote
                FROM
                    expenses
                WHERE
                    EXPDeleted=0 AND
                    EXPDate= '$date'
            ","key_all");
            for ($i=0,$iL=count($today_expense); $i < $iL; $i++) { 
                $today_expense[$i]["EXPNote"]=html_entity_decode($today_expense[$i]["EXPNote"]);
            }
            $today_income=$this->database->return_data("
                SELECT
                    PNTID,
                    PNTInvoiceId,
                    PNTTotalUSD as usd,
                    PNTExtraIQD as iqd,
                    PNTTotalIQD as origniqd,
                    AGRShopTitle,
                    PNTNote,
                    PNTUSDRate,
                    PNTTotalMoney as total,
                    (
                        SELECT
                        	GROUP_CONCAT(ARTRentType,'_',RPDMoney)
                        FROM
                        	rent_payment_detail,
                        	agreementrent
                        WHERE
                        	ARTDeleted=0 AND
                    		RPDDeleted=0 AND
                    		RPDARTFORID=ARTID AND
                        	RPDPNTFORID=PNTID
                    ) as total_detail
                FROM
                    agreement,
                    rent_payment
                WHERE
                    PNTDeleted=0 AND
                    PNTAGRFORID=AGRID AND
                    AGRDeleted=0 AND
                    PNTDate ='$date'
            ","key_all");
            $today_income=$this->separate_invoices($today_income);
            for ($i=0,$iL=count($today_income); $i < $iL; $i++) { 
                $today_income[$i]["PNTNote"]=html_entity_decode($today_income[$i]["PNTNote"]);
            }

            $total_advance=$this->database->return_data("
                SELECT
                    sum(ADVMoney) as total,
                    ADVMoneyType
                FROM
                    advance
                WHERE
                    ADVDeleted=0 AND
                    ADVDate<='".$date."'
                group BY
                	ADVMoneyType
            ","key_all");
            $advance_return=$this->database->return_data("
                SELECT
                    sum(CPTMoney)as total,
                    CPTMoneyType
                FROM
                    capital
                WHERE
                    CPTDeleted=0 and
                    CPTTransactionType=4 AND
                    CPTDate<='".$date."'
                group BY
                	CPTMoneyType
            ","key_all");
            $advance=array("usd"=>0,"iqd"=>0);
            for ($i=0,$iL=count($total_advance); $i < $iL; $i++) { 
                if($total_advance[$i]["ADVMoneyType"]==0){
                    $advance["usd"]=$total_advance[$i]["total"];
                }else if($total_advance[$i]["ADVMoneyType"]==1){
                    $advance["iqd"]=$total_advance[$i]["total"];
                }
            }
            for ($i=0,$iL=count($advance_return); $i < $iL; $i++) { 
                if($advance_return[$i]["CPTMoneyType"]==0){
                    $advance["usd"]-=$advance_return[$i]["total"];
                }else if($advance_return[$i]["CPTMoneyType"]==1){
                    $advance["iqd"]-=$advance_return[$i]["total"];
                }
            }
            $capital=$this->database->return_data("
                SELECT
                    sum(PNTExtraIQD) as iqd,
                    sum(PNTTotalUSD) as usd,
                    PNTPaidType
                FROM
                    rent_payment
                WHERE
                    PNTDeleted=0 AND
                    PNTDate<='".$date."'
                group BY
                    PNTPaidType
            ","key_all");
            return json_encode(array(
                "today_income"=>$today_income,
                "today_expense"=>$today_expense,
                "capital"=>$capital,
                "yesterday"=>totalAllType($this->database,date('Y-m-d', strtotime('-1 day', strtotime($date)))),
                "base_usd"=>$base_usd,
                "base_iqd"=>$base_usd,
                "advance"=>$advance,
                "real_capital"=>totalAllType($this->database,$date)
            ));  
        }
        function report10($data){
            extract($data);
            $return_data=$this->database->return_data("
                SELECT
                    EXGName, EXPMoney, EXPUSDRate, EXPDate, EXPForPerson, EXPNote,
                    case EXPMoneyType
                        when 0 then 'USD'
                        when 1 then 'IQD'
                    end as EXPMoneyType
                FROM
                    expenses,expenses_group
                WHERE
                    EXPDeleted=0 AND
                    EXGDeleted=0 AND
                    EXGID=EXPEXGFORID AND
                    ".($exgid==0?"":"EXPEXGFORID=$exgid AND ")."
                    ".($moneyType==2?"":"EXPMoneyType=$moneyType AND ")."
                    EXPDate between '".$startDate."' and '".$endDate."'
            ","key_all");
            $total_usd=$total_iqd=0;
            for ($i=0,$iL=count($return_data); $i < $iL; $i++) { 
                $return_data[$i]["EXPNote"]=html_entity_decode($return_data[$i]["EXPNote"]);
                if($return_data[$i]["EXPMoneyType"]=="USD"){
                    $total_usd+=$return_data[$i]["EXPMoney"];
                }else if($return_data[$i]["EXPMoneyType"]=="IQD"){
                    $total_iqd+=$return_data[$i]["EXPMoney"];
                }
            }
            array_push($return_data,array("total_usd"=>$total_usd,"total_iqd"=>$total_iqd));
            return json_encode($return_data);                                                                                                                                                                                                                                                                                                                                         
        }
        function report11($data){
            extract($data);
            $return_data=$this->database->return_data("
                SELECT
                    CPTMoney, CPTUSDRate, CPTNote, CPTDate,
                    case CPTMoneyType
                        when 0 then 'USD'
                        when 1 then 'IQD'
                    end as CPTMoneyType
                FROM
                    capital
                WHERE
                    CPTTransactionType=6 AND
                    CPTDeleted=0 AND
                    ".($moneyType==2?"":"CPTMoneyType=$moneyType AND ")."
                    CPTDate between '".$startDate."' and '".$endDate."'
            ","key_all");
            for ($i=0,$iL=count($return_data); $i < $iL; $i++) { 
                $return_data[$i]["CPTNote"]=html_entity_decode($return_data[$i]["CPTNote"]);
            }
            return json_encode($return_data);                                                                                                                                                                                                                                                                                                                                         
        }
		function __destruct() {
			$this->fileContaent=null;
        }
        function separate_invoices($data){
            $full_data=array();
            for ($i=0,$iL=count($data); $i < $iL; $i++) { 
                $row=[
                    ["PNTID"=>$data[$i]["PNTInvoiceId"],"usd"=>0,"iqd"=>0,"AGRShopTitle"=>$data[$i]["AGRShopTitle"],"PNTNote"=>$data[$i]["PNTNote"],"type"=>0,"total"=>0,"state"=>0],
                    ["PNTID"=>$data[$i]["PNTInvoiceId"],"usd"=>0,"iqd"=>0,"AGRShopTitle"=>$data[$i]["AGRShopTitle"],"PNTNote"=>$data[$i]["PNTNote"],"type"=>1,"total"=>0,"state"=>0],
                    ["PNTID"=>$data[$i]["PNTInvoiceId"],"usd"=>0,"iqd"=>0,"AGRShopTitle"=>$data[$i]["AGRShopTitle"],"PNTNote"=>$data[$i]["PNTNote"],"type"=>2,"total"=>0,"state"=>0]
                ];
                $split_data=explode(",",$data[$i]["total_detail"]);
                for ($j=0,$jL=count($split_data); $j < $jL; $j++) { 
                    $split_j=explode("_",$split_data[$j]);
                    if($split_j[0]==0){
                        $row[0]["total"]=$row[0]["total"]+$split_j[1];
                    }else if($split_j[0]==1){
                        $row[1]["total"]=$row[1]["total"]+$split_j[1];
                    }else if($split_j[0]==2){
                        $row[2]["total"]=$row[2]["total"]+$split_j[1];
                    }
                }
                if($data[$i]["iqd"]==0 && $data[$i]["usd"]>0){
                    $row[0]["usd"]=$row[0]["total"];
                    $row[1]["usd"]=$row[1]["total"];
                    $row[2]["usd"]=$row[2]["total"];
                }else if($data[$i]["iqd"]>0 && $data[$i]["usd"]==0){
                    $row[0]["iqd"]=$row[0]["total"]*$data[$i]["PNTUSDRate"];
                    $row[1]["iqd"]=$row[1]["total"]*$data[$i]["PNTUSDRate"];
                    $row[2]["iqd"]=$row[2]["total"]*$data[$i]["PNTUSDRate"];
                }else if($data[$i]["iqd"]>0 && $data[$i]["usd"]>0){
                    $iqd=$data[$i]["iqd"];
                    $usd=$data[$i]["usd"];
                    for ($x=0,$xL=count($row); $x < $xL; $x++) { 
                        if($usd==0){
                            break;
                        }
                        if($row[$x]["total"]<=$usd){
                            $usd=$usd-$row[$x]["total"];
                            $row[$x]["usd"]=$row[$x]["total"];
                            $row[$x]["total"]=0;
                            $row[$x]["state"]=1;
                        }else{
                            $row[$x]["total"]=$row[$x]["total"]-$usd;
                            $row[$x]["usd"]=$usd;
                            $row[$x]["state"]=-1;
                            $usd=0;
                        }
                    }
                    for ($x=0,$xL=count($row); $x < $xL; $x++) { 
                        if($iqd==0){
                            break;
                        }
                        if($row[$x]["state"]==1){
                            continue;
                        }
                        // if(($row[$i]["total"]*$data[$i]["PNTUSDRate"])<=$iqd) ==> don't need if statement because must always $iqd greater than ($row[$i]["total"]*$data[$i]["PNTUSDRate"])
                        $iqd=$iqd-($data[$i]["PNTUSDRate"]*$row[$x]["total"]);
                        $row[$x]["iqd"]=$row[$x]["total"]*$data[$i]["PNTUSDRate"];
                        $row[$x]["total"]=0;
                        $row[$x]["state"]=1;
                        
                    }
                }
                if($data[$i]["iqd"]>$data[$i]["origniqd"]){
                    $extra=$data[$i]["iqd"]-$data[$i]["origniqd"];
                    for ($k=0,$kl=count($row); $k <$kl ; $k++) { 
                        if($row[$k]["iqd"]>0){
                            $row[$k]["iqd"]+=$extra;
                            break;
                        }
                    }
                }
                for ($j=0,$jL=count($row); $j < $jL; $j++) { 
                    if($row[$j]["usd"]>0 || $row[$j]["iqd"]>0){
                        array_push($full_data,$row[$j]);
                    }
                }
            }
            return $full_data;
        }
	}
?>