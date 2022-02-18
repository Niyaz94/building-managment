<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){       
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $max = $database->return_data2(array(
                "tablesName"=>array("rent_payment"),
                "columnsName"=>array("IFNULL(max(PNTID),0) as max"),
                "conditions"=>array(
                    array("columnName"=>"PNTAGRFORID","operation"=>"=","value"=>$_GET["AGRID"],"link"=>"and"),
                    array("columnName"=>"PNTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["max"];
            $table = "
                (
                    select
                        rent_payment.*,
                        CASE 
                            WHEN PNTID = $max  THEN '1' 
                            WHEN PNTID <> $max  THEN '0' 
                        END AS edit_last
                    from
                        rent_payment
                    where
                        PNTDeleted=0 and
                        PNTAGRFORID=".$_GET["AGRID"]."
                ) as table1
            ";
            $primaryKey = "PNTID";
            $where="";
            $columns =  array(
                array( "db" => "PNTID", "dt" => 0 ), 
                array( "db" => "PNTTotalMoney", "dt" => 1 ),  
                array( "db" => "PNTTotalUSD", "dt" => 2 ),  
                array( "db" => "PNTTotalIQD", "dt" => 3 ),  
                array( "db" => "PNTUSDRate", "dt" => 4 ),  
                array( "db" => "edit_last", "dt" => 5 ),   
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") { 
            if($_POST["PNTTotalMoney_IIZN"]<=0){
                echo jsonMessages(false,1);
                exit;
            }
            $tableInfo=json_decode($_POST["tableInfo"],true);
            $validation=new class_validation($_POST,"PNT");
            $data=$validation->returnLastVersion();
            extract($data);
            $insertID = $database->insert_data2("rent_payment",$data);
            $tableInfoLen=count($tableInfo);
            for($i=0;$i<$tableInfoLen;++$i){    
                $database->update_data2(array(//update current agreementrent
                    "tablesName"=>"agreementrent",
                    "userData"=>array(
                        "ARTPaidType"=>2,
                        "PageName"=>$PageName,
                        "primaryKey"=>array("key"=>"ARTID","value"=>$tableInfo[$i]["ARTID"])),
                    "conditions"=>array()
                ));
                $database->insert_data2("rent_payment_detail",array(
                    "RPDPNTFORID"=>$insertID,
                    "RPDARTFORID"=>$tableInfo[$i]["ARTID"],
                    "RPDMoney"=>$tableInfo[$i]["paidBasic"],
                    "PageName"=>$PageName
                ));  
            }
            if($PNTTotalUSD>0){
                $database->insert_data2("capital",array(
                    "CPTTransactionType"=>0,
                    "CPTFORID"=>$insertID,
                    "CPTDate"=>$PNTDate,
                    "CPTMoneyType"=>0,
                    "CPTMoney"=>$PNTTotalUSD,
                    "CPTUSDRate"=>$PNTUSDRate,
                    "CPTPaidType"=>$_POST["PNTPaidType_IHZN"],
                    "PageName"=>$PageName
                ));
            }
            if($PNTTotalIQD>0){
                $database->insert_data2("capital",array(
                    "CPTTransactionType"=>0,
                    "CPTFORID"=>$insertID,
                    "CPTMoneyType"=>1,
                    "CPTDate"=>$PNTDate,
                    "CPTMoney"=>$PNTTotalIQD,
                    "CPTUSDRate"=>$PNTUSDRate,
                    "CPTPaidType"=>$_POST["PNTPaidType_IHZN"],
                    "PageName"=>$PageName
                ));
                if($PNTExtraIQD>$PNTTotalIQD){
                    $database->insert_data2("capital",array(
                        "CPTTransactionType"=>5,
                        "CPTFORID"=>$insertID,
                        "CPTMoneyType"=>1,
                        "CPTDate"=>$PNTDate,
                        "CPTMoney"=>($PNTExtraIQD-$PNTTotalIQD),
                        "CPTUSDRate"=>$PNTUSDRate,
                        "CPTPaidType"=>$_POST["PNTPaidType_IHZN"],
                        "PageName"=>$PageName
                    ));
                }
            }
            if ($insertID>0) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            if($_POST["PNTTotalMoney_UIRN"]<=0){
                echo jsonMessages(false,1);
                exit;
            }
            $capitalType=array("USD"=>0,"IQD"=>0);  
            $total=totalAllType($database);
            $capital = $database->return_data2(array(
                "tablesName"=>array("capital"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"CPTTransactionType","operation"=>"in","value"=>"0,5","link"=>"and"),
                    array("columnName"=>"CPTFORID","operation"=>"=","value"=>$_POST["PNTID_UIZP"],"link"=>"and"),
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            if($_POST["PNTPaidType_UHRN"]==0 && $_POST["oldPNTPaidType"]==0){
                for($i=0;$i<count($capital);++$i){
                    if($capital[$i]["CPTMoneyType"]==0){
                        $capitalType["USD"]=$capitalType["USD"]+$capital[$i]["CPTMoney"];
                    }else if($capital[$i]["CPTMoneyType"]==1){
                        $capitalType["IQD"]=$capitalType["IQD"]+$capital[$i]["CPTMoney"];                    
                    }
                }	
                $total["income_usd"]=$total["income_usd"]-$capitalType["USD"]+$_POST["PNTTotalUSD_UIRN"];
                $total["income_iqd"]=$total["income_iqd"]-$capitalType["IQD"]+$_POST["PNTTotalIQD_UIRN"];
                if($total["expense_usd"]>$total["income_usd"] || $total["expense_iqd"]>$total["income_iqd"]){
                    echo jsonMessages(false,16);
                    exit;
                }
            }else if($_POST["PNTPaidType_UHRN"]!=0 && $_POST["oldPNTPaidType"]==0){
                for($i=0;$i<count($capital);++$i){
                    if($capital[$i]["CPTMoneyType"]==0){
                        $capitalType["USD"]=$capitalType["USD"]+$capital[$i]["CPTMoney"];
                    }else if($capital[$i]["CPTMoneyType"]==1){
                        $capitalType["IQD"]=$capitalType["IQD"]+$capital[$i]["CPTMoney"];                    
                    }
                }	
                $total["income_usd"]=$total["income_usd"]-$capitalType["USD"];
                $total["income_iqd"]=$total["income_iqd"]-$capitalType["IQD"];
                if($total["expense_usd"]>$total["income_usd"] || $total["expense_iqd"]>$total["income_iqd"]){
                    echo jsonMessages(false,16);
                    exit;
                }
            }
            $tableInfo=json_decode($_POST["tableInfoEditUpdate"],true);
            $tableInfo=find_difference($database,$_POST["PNTID_UIZP"],$tableInfo);

            $validation=new class_validation($_POST,"PNT");
            $data=$validation->returnLastVersion();
            extract($data);
            $rent_payment = $database->update_data2(array(
                "tablesName"=>"rent_payment",
                "userData"=>$data,
                "conditions"=>array()
            ));
            for($i=0,$iL=count($tableInfo);$i<$iL;++$i){
                if($tableInfo[$i]["type"]==1){//new rows
                    $database->update_data2(array(
                        "tablesName"=>"agreementrent",
                        "userData"=>array(
                            "ARTPaidType"=>2,
                            "PageName"=>$PageName,
                            "primaryKey"=>array("key"=>"ARTID","value"=>$tableInfo[$i]["ARTID"])
                        ),
                        "conditions"=>array()
                    ));
                    $database->insert_data2("rent_payment_detail",array(
                        "RPDPNTFORID"=>$PNTID,
                        "RPDARTFORID"=>$tableInfo[$i]["ARTID"],
                        "RPDMoney"=>$tableInfo[$i]["paidBasic"],
                        "PageName"=>$PageName
                    ));
                }else if($tableInfo[$i]["type"]==2){//delete rows
                    $database->update_data2(array(
                        "tablesName"=>"agreementrent",
                        "userData"=>array(
                            "ARTPaidType"=>0,
                            "PageName"=>$PageName,
                            "primaryKey"=>array("key"=>"ARTID","value"=>$tableInfo[$i]["ARTID"])
                        ),
                        "conditions"=>array()
                    ));
                    $database->delete_data2(array(
                        "tablesName"=>"rent_payment_detail",
                        "userData"=>array(
                            "RPDDeleted"=>1,
                            "PageName"=>$PageName,
                            "primaryKey"=>array("key"=>"RPDID","value"=>$tableInfo[$i]["RPDID"])
                        ),
                        "conditions"=>array()
                    ));
                }
            }
            $capitalCheck=array("USD"=>0,"IQD"=>0,"extraIQD"=>0);
            for($i=0;$i<count($capital);++$i){
                if($capital[$i]["CPTMoneyType"]==0){//if the money is USD
                    if($PNTTotalUSD==0){
                        $database->delete_data2(array(
                            "tablesName"=>"capital",
                            "userData"=>array(
                                "CPTDeleted"=>1,
                                "PageName"=>$PageName,
                                "primaryKey"=>array("key"=>"CPTID","value"=>$capital[$i]["CPTID"])
                            ),
                            "conditions"=>array()
                        ));
                    }else if($PNTTotalUSD>0){
                        $database->update_data2(array(
                            "tablesName"=>"capital",
                            "userData"=>array(
                                "CPTMoney"=>$PNTTotalUSD,
                                "CPTDate"=>$PNTDate,
                                "PageName"=>$PageName,
                                "CPTPaidType"=>$_POST["PNTPaidType_UHRN"],
                                "primaryKey"=>array("key"=>"CPTID","value"=>$capital[$i]["CPTID"])
                            ),
                            "conditions"=>array()
                        ));
                    }
                    $capitalCheck["USD"]=1;
                }else if($capital[$i]["CPTMoneyType"]==1 && $capital[$i]["CPTTransactionType"]==0){//if the money is IQD...
                    if($PNTTotalIQD==0){
                        $database->delete_data2(array(
                            "tablesName"=>"capital",
                            "userData"=>array(
                                "CPTDeleted"=>1,
                                "PageName"=>$PageName,
                                "primaryKey"=>array("key"=>"CPTID","value"=>$capital[$i]["CPTID"])
                            ),
                            "conditions"=>array()
                        ));
                    }else if($PNTTotalIQD>0){
                        $database->update_data2(array(
                            "tablesName"=>"capital",
                            "userData"=>array(
                                "CPTMoney"=>$PNTTotalIQD,
                                "CPTDate"=>$PNTDate,
                                "PageName"=>$PageName,
                                "CPTPaidType"=>$_POST["PNTPaidType_UHRN"],
                                "primaryKey"=>array("key"=>"CPTID","value"=>$capital[$i]["CPTID"])
                            ),
                            "conditions"=>array()
                        ));
                    }
                    $capitalCheck["IQD"]=1;                    
                }else if($capital[$i]["CPTMoneyType"]==1 && $capital[$i]["CPTTransactionType"]==5){//if the money is IQD and type of money is extra
                    if($PNTTotalIQD==$PNTExtraIQD){
                        $database->delete_data2(array(
                            "tablesName"=>"capital",
                            "userData"=>array(
                                "CPTDeleted"=>1,
                                "PageName"=>$PageName,
                                "primaryKey"=>array("key"=>"CPTID","value"=>$capital[$i]["CPTID"])
                            ),
                            "conditions"=>array()
                        ));
                    }else if($PNTTotalIQD<$PNTExtraIQD){
                        $database->update_data2(array(
                            "tablesName"=>"capital",
                            "userData"=>array(
                                "CPTMoney"=>($PNTExtraIQD-$PNTTotalIQD),
                                "CPTDate"=>$PNTDate,
                                "PageName"=>$PageName,
                                "CPTPaidType"=>$_POST["PNTPaidType_UHRN"],
                                "primaryKey"=>array("key"=>"CPTID","value"=>$capital[$i]["CPTID"])
                            ),
                            "conditions"=>array()
                        ));
                    }
                    $capitalCheck["extraIQD"]=1;// if this field is equal to 1 it mean that we have the extra money before we don't need to check                
                }
            }
            //those three if statement work just work in case we don't have them[usd money, iqd money, extra money] in insert process	
            if($capitalCheck["USD"]==0 && $PNTTotalUSD>0){
                $database->insert_data2("capital",array(
                    "CPTTransactionType"=>0,
                    "CPTFORID"=>$PNTID,
                    "CPTDate"=>$PNTDate,
                    "CPTMoneyType"=>0,
                    "CPTMoney"=>$PNTTotalUSD,
                    "CPTUSDRate"=>$PNTUSDRate,
                    "CPTPaidType"=>$_POST["PNTPaidType_UHRN"],
                    "PageName"=>$PageName
                ));
            }
            if($capitalCheck["IQD"]==0 && $PNTTotalIQD>0){
                $database->insert_data2("capital",array(
                    "CPTTransactionType"=>0,
                    "CPTFORID"=>$PNTID,
                    "CPTDate"=>$PNTDate,
                    "CPTMoneyType"=>1,
                    "CPTMoney"=>$PNTTotalIQD,
                    "CPTUSDRate"=>$PNTUSDRate,
                    "CPTPaidType"=>$_POST["PNTPaidType_UHRN"],
                    "PageName"=>$PageName
                ));
            }
            if($capitalCheck["extraIQD"]==0 && $PNTExtraIQD>$PNTTotalIQD){
                $database->insert_data2("capital",array(
                    "CPTTransactionType"=>5,
                    "CPTFORID"=>$PNTID,
                    "CPTDate"=>$PNTDate,
                    "CPTMoneyType"=>1,
                    "CPTMoney"=>($PNTExtraIQD-$PNTTotalIQD),
                    "CPTUSDRate"=>$PNTUSDRate,
                    "CPTPaidType"=>$_POST["PNTPaidType_UHRN"],
                    "PageName"=>$PageName
                ));
            }
            if ($rent_payment) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "getPaymentDetail"){
            extract($_POST);
            echo jsonMessages2(true,getPaymentDetail($database,$_POST["paidMoney"],$_POST["AGRID"],$_POST["PNTID"]));
        }
        if ($_POST["type"] == "delete") {
            $capitalType=array("USD"=>0,"IQD"=>0);
            $capital = $database->return_data2(array(
                "tablesName"=>array("capital"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"CPTTransactionType","operation"=>"in","value"=>"0,5","link"=>"and"),
                    array("columnName"=>"CPTFORID","operation"=>"=","value"=>$_POST["PNTID_UIZP"],"link"=>"and"),
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            for($i=0;$i<count($capital);++$i){
                if($capital[$i]["CPTMoneyType"]==0){
                    $capitalType["USD"]=$capitalType["USD"]+$capital[$i]["CPTMoney"];
                }else if($capital[$i]["CPTMoneyType"]==1){
                    $capitalType["IQD"]=$capitalType["IQD"]+$capital[$i]["CPTMoney"];                    
                }
            }	
            $total=totalAllType($database);
            $total["income_usd"]=$total["income_usd"]-$capitalType["USD"];
            $total["income_iqd"]=$total["income_iqd"]-$capitalType["IQD"];

            if($total["expense_usd"]>$total["income_usd"] || $total["expense_iqd"]>$total["income_iqd"]){
                echo jsonMessages(false,15);
                exit;
            }

            $validation=new class_validation($_POST,"PNT");
			$data=$validation->returnLastVersion();
            extract($data);	
            $old_json=getPaymentDetail($database,$_POST["paidMoney"],$_POST["AGRID"],$_POST["PNTID_UIZP"]);
            $old_jsonLen=count($old_json);
            for($i=0;$i<$old_jsonLen;++$i){
                $database->update_data2(array(
                    "tablesName"=>"agreementrent",
                    "userData"=>array(
                        "ARTPaidType"=>0,
                        "PageName"=>$PageName,
                        "primaryKey"=>array("key"=>"ARTID","value"=>$old_json[$i]["ARTID"])
                    ),
                    "conditions"=>array()
                ));
            }
			$res = $database->delete_data2(array(
				"tablesName"=>"rent_payment",
				"userData"=>$data,
				"conditions"=>array()
            ));
            $database->delete_data3(array(
                "tablesName"=>"rent_payment_detail",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"RPDPNTFORID","value"=>$PNTID)
                ),
                "conditions"=>array(
                    array("columnName"=>"RPDDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"RPD"
            ));
            $database->delete_data3(array(
                "tablesName"=>"capital",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"CPTFORID","value"=>$PNTID)
                ),
                "conditions"=>array(
                    array("columnName"=>"CPTTransactionType","operation"=>"in","value"=>"0,5","link"=>"and"),
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"CPT"
            ));
			if ($res) {
				echo jsonMessages(true,1);
				exit;
			}else{
				echo jsonMessages(false,1);
				exit;
			} 
        }
        if($_POST["type"] == "totalDebt"){
            echo jsonMessages2(true,$database->return_data("
                SELECT
                    (
                        IFNULL((
                            SELECT
                                sum(ARTRentAftDis)
                            FROM
                                agreementrent
                            WHERE
                                ARTDeleted=0 and 
                                ARTAGRFORID=".$_POST["AGRID"]."
                        ),0)
                        -
                        IFNULL((
                            SELECT
                                SUM(PNTTotalMoney)
                            FROM
                                rent_payment
                            WHERE
                                PNTDeleted=0 and
                                PNTAGRFORID=".$_POST["AGRID"]."
                    ),0)
                    ) as totalDebt
                FROM
                    agreement
                WHERE
                    AGRID=".$_POST["AGRID"]." and 
                    AGRDeleted=0
            ","key")["totalDebt"]*1);
        }
    }else{
        header("Location:../");
        exit;
    }
    function addingTodate($date,$year=0,$month=0,$day=0){
        return date("Y-m-d",strtotime("+".$year." year +".$month." month +".$day." day",strtotime($date)));
    }
    function getPaymentDetail($database,$paidMoney,$AGRID,$PNTID){
        //return rent_payment with this PNTID coming from function
        $rent_payment = $database->return_data2(array(
            "tablesName"=>array("rent_payment"),
            "columnsName"=>array("*"),
            "conditions"=>array(
                array("columnName"=>"PNTID","operation"=>"=","value"=>$PNTID,"link"=>"and"),
                array("columnName"=>"PNTDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"key"
        ));
        //return rent_payment_detail with this PNTID coming from function
        $rent_payment_detail = $database->return_data2(array(
            "tablesName"=>array("rent_payment_detail"),
            "columnsName"=>array("*"),
            "conditions"=>array(
                array("columnName"=>"RPDPNTFORID","operation"=>"=","value"=>$PNTID,"link"=>"and"),
                array("columnName"=>"RPDDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"key_all"
        ));
        $detailLen=count($rent_payment_detail);//find the length of array
        $all_RPDARTFORID=implode(",",array_column($rent_payment_detail,"RPDARTFORID"));//find all agreementrent id that used in this payment
        //find all agreementrent using in this payment
        $old_json = $database->return_data2(array(
            "tablesName"=>array("agreementrent"),
            "columnsName"=>array("ARTID","ARTDate","(ARTRentAftDis) as paidBasic","ARTRentType"),
            "conditions"=>array(
                array("columnName"=>"ARTID","operation"=>"in","value"=>$all_RPDARTFORID,"link"=>"and"),
                array("columnName"=>"ARTDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"key_all"
        ));
        for($i=0;$i<$detailLen;++$i){
            $old_json[$i]["RPDID"]=$rent_payment_detail[$i]["RPDID"];
        }
        return $old_json;
    }
    function find_difference($database,$id,$new_array){
        $old_array = $database->return_data2(array(
            "tablesName"=>array("rent_payment_detail"),
            "columnsName"=>array("RPDID","RPDARTFORID"),
            "conditions"=>array(
                array("columnName"=>"RPDPNTFORID","operation"=>"=","value"=>$id,"link"=>"and"),
                array("columnName"=>"RPDDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"key_all"
        ));
        for ($i=0,$iL=count($new_array); $i < $iL; $i++) { 
            $new_array[$i]["type"]=0;//nothing happen
        }
        for ($i=0,$iL=count($new_array); $i < $iL; $i++) { 
            if(!in_array($new_array[$i]["ARTID"],array_column($old_array, 'RPDARTFORID'))){
                $new_array[$i]["type"]=1;//insert
            }
        }

        for ($i=0,$iL=count($old_array); $i < $iL; $i++) { 
            if(!in_array($old_array[$i]["RPDARTFORID"],array_column($new_array, 'ARTID'))){
                array_push($new_array,array(
                    "type"=>2,
                    "ARTID"=>$old_array[$i]["RPDARTFORID"],
                    "RPDID"=>$old_array[$i]["RPDID"]
                ));
            }
        }
        $return_array=array();
        for ($i=0,$iL=count($new_array); $i < $iL; $i++) { 
            if($new_array[$i]["type"]!=0){
                array_push($return_array,$new_array[$i]);
            }
        }
        return $return_array;
    }
?>
    