<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                select
                    EXPID,
                    EXPForPerson,
                    EXPMoney,
                    EXPMoneyType,
                    EXPUSDRate,
                    EXGName,
                    EXPDate,
                    EXPDeleted
                from
                    expenses,
                    expenses_group
                where
                    EXGID=EXPEXGFORID and
                    EXGDeleted=0 and
                    EXPDeleted=0
            ) as table1";
            $primaryKey = "EXPID";
            $where="";
            $columns =  array(
                array( "db" => "EXPID", "dt" => 0 ),  
                array( "db" => "EXPForPerson", "dt" => 1 ),  
                array( "db" => "EXGName", "dt" => 2 ),  
                array( "db" => "EXPMoney", "dt" => 3 ),  
                array( "db" => "EXPDate", "dt" => 4 ),  
                array( "db" => "EXPMoneyType", "dt" => 5 ),  
                array( "db" => "EXPUSDRate", "dt" => 6 ),  
                array( "db" => "EXPDeleted", "dt" => 7 ),  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            $total=totalAllType($database);
            if($_POST["EXPMoneyType_IHZN"]==0){
                $total["expense_usd"]=$total["expense_usd"]+$_POST["EXPMoney_IIZN"];
                if($total["income_usd"]<$total["expense_usd"]){
                    echo jsonMessages(false,17);
                    exit;
                }
            }else if($_POST["EXPMoneyType_IHZN"]==1){
                $total["expense_iqd"]=$total["expense_iqd"]+$_POST["EXPMoney_IIZN"];
                if($total["income_iqd"]<$total["expense_iqd"]){
                    echo jsonMessages(false,17);
                    exit;
                }
            }
            $validation=new class_validation($_POST,"EXP");
            $data=$validation->returnLastVersion();
            extract($data);
            $expenses_id = $database->insert_data2("expenses",$data);

            $database->insert_data2("capital",array(
                "PageName"=>$PageName,
                "CPTTransactionType"=>1,
                "CPTOperationType"=>1,
                "CPTUse"=>1,
                "CPTDate"=>$EXPDate,
                "CPTFORID"=>$expenses_id,
                "CPTMoneyType"=>$EXPMoneyType,
                "CPTMoney"=>$EXPMoney,
                "CPTUSDRate"=>$EXPUSDRate,
                "CPTNote"=>$EXPNote
            ));

            if ($expenses_id>0) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            //testData($_POST,0);
            // expense ==> expense   usd ==> usd
            $total=totalAllType($database);
            $capitalOldInfo = $database->return_data3(array(
                "tablesName"=>array("'capital'"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"CPTTransactionType","operation"=>"=","value"=>1,"link"=>"and"),
                    array("columnName"=>"CPTFORID","operation"=>"=","value"=>$_POST["EXPID_UIZP"],"link"=>"and"),
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            if( $capitalOldInfo["CPTMoneyType"]==0 && $_POST["EXPMoneyType_UHRN"]==0){
                $total["expense_usd"]=$total["expense_usd"]-$capitalOldInfo["CPTMoney"]+$_POST["EXPMoney_UIRN"];
            // expense ==> expense   usd ==> iqd
            }else if( $capitalOldInfo["CPTMoneyType"]==0 && $_POST["EXPMoneyType_UHRN"]==1){
                $total["expense_usd"]=$total["expense_usd"]-$capitalOldInfo["CPTMoney"];
                $total["expense_iqd"]=$total["expense_iqd"]+$_POST["EXPMoney_UIRN"];
            // expense ==> expense   iqd ==> usd
            }else if( $capitalOldInfo["CPTMoneyType"]==1 && $_POST["EXPMoneyType_UHRN"]==0){
                $total["expense_usd"]=$total["expense_usd"]+$_POST["EXPMoney_UIRN"];
                $total["expense_iqd"]=$total["expense_iqd"]-$capitalOldInfo["CPTMoney"];
            // expense ==> expense   iqd ==> iqd
            }else if( $capitalOldInfo["CPTMoneyType"]==1 && $_POST["EXPMoneyType_UHRN"]==1){
                $total["expense_iqd"]=$total["expense_iqd"]-$capitalOldInfo["CPTMoney"]+$_POST["EXPMoney_UIRN"];
            }
            if($total["expense_usd"]>$total["income_usd"] || $total["expense_iqd"]>$total["income_iqd"]){
                echo jsonMessages(false,16);
                exit;
            }

            $validation=new class_validation($_POST,"EXP");
            $data=$validation->returnLastVersion();
            extract($data);
            $update_expenses = $database->update_data2(array(
                "tablesName"=>"expenses",
                "userData"=>$data,
                "conditions"=>array()
            ));
            $database->update_data2(array(
                "tablesName"=>"capital",
                "userData"=>array(
                    "CPTMoneyType"=>$EXPMoneyType,
                    "CPTMoney"=>$EXPMoney,
                    "CPTDate"=>$EXPDate,
                    "CPTUSDRate"=>$EXPUSDRate,
                    "CPTNote"=>$EXPNote,
                    "PageName"=>$PageName,
                    "primaryKey"=>array("key"=>"CPTID","value"=>$capitalOldInfo["CPTID"])
                ),
                "conditions"=>array()
            ));
            if ($update_expenses) {
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "delete") {	
            $validation=new class_validation($_POST,"EXP");
			$data=$validation->returnLastVersion();
            extract($data);	
            $database->delete_data3(array(
                "tablesName"=>"capital",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"CPTFORID","value"=>$EXPID)
                ),
                "conditions"=>array(
                    array("columnName"=>"CPTTransactionType","operation"=>"=","value"=>1,"link"=>"and"),
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"CPT"
            ));
			$res = $database->delete_data2(array(
				"tablesName"=>"expenses",
				"userData"=>$data,
				"conditions"=>array()
			));
			if ($res) {
				echo jsonMessages(true,1);
				exit;
			}else{
				echo jsonMessages(false,1);
				exit;
			} 
        }
    }else{
        header("Location:../");
        exit;
    }
?>
    