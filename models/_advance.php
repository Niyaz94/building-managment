<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                select
                    advance.*,
                    ifnull((
                        SELECT
                            sum(CPTMoney)
                        FROM
                            capital
                        WHERE
                            CPTOperationType=0 AND
                            CPTTransactionType=4 AND
                            CPTDeleted=0 AND
                            CPTFORID=ADVID
                    ),0) as total_return
                from
                    advance
                where
                    ADVDeleted=0
            )as table1";
            $primaryKey = "ADVID";
            $where="";
            $columns =  array(
                array( "db" => "ADVID", "dt" => 0 ),  
                array( "db" => "ADVForPerson", "dt" => 1 ),  
                array( "db" => "ADVMoney", "dt" => 2 ),  
                array( "db" => "ADVDate", "dt" => 3),  
                array( "db" => "ADVMoneyType", "dt" => 4 ),  
                array( "db" => "total_return", "dt" => 5 ),  
                array( "db" => "ADVDeleted", "dt" => 6),  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {
            $total=totalAllType($database);
            if($_POST["ADVMoneyType_IHZN"]==0){
                $total["expense_usd"]=$total["expense_usd"]+$_POST["ADVMoney_IIZN"];
                if($total["income_usd"]<$total["expense_usd"]){
                    echo jsonMessages(false,17);
                    exit;
                }
            }else if($_POST["ADVMoneyType_IHZN"]==1){
                $total["expense_iqd"]=$total["expense_iqd"]+$_POST["ADVMoney_IIZN"];
                if($total["income_iqd"]<$total["expense_iqd"]){
                    echo jsonMessages(false,17);
                    exit;
                }
            }
            $validation=new class_validation($_POST,"ADV");
            $data=$validation->returnLastVersion();
            extract($data);
            $advance_id = $database->insert_data2("advance",$data);
            $database->insert_data2("capital",array(
                "PageName"=>$PageName,
                "CPTOperationType"=>1,
                "CPTDate"=>$ADVDate,
                "CPTTransactionType"=>3,
                "CPTPaidType"=>0,
                "CPTFORID"=>$advance_id,
                "CPTMoneyType"=>$ADVMoneyType,
                "CPTMoney"=>$ADVMoney,
                "CPTUSDRate"=>$ADVUSDRate,
                "CPTNote"=>$ADVNote
            ));
            if ($advance_id>0) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            //testData($_POST,0);
            $total=totalAllType($database);
            $capitalOldInfo = $database->return_data3(array(
                "tablesName"=>array("'capital'"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"CPTFORID","operation"=>"=","value"=>$_POST["ADVID_UIZP"],"link"=>"and"),
                    array("columnName"=>"CPTTransactionType","operation"=>"=","value"=>3,"link"=>"and"),
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            if( $capitalOldInfo["CPTMoneyType"]==0 && $_POST["ADVMoneyType_UHRN"]==0){
                $total["expense_usd"]=$total["expense_usd"]-$capitalOldInfo["CPTMoney"]+$_POST["ADVMoney_UIRN"];
            // expense ==> expense   usd ==> iqd
            }else if( $capitalOldInfo["CPTMoneyType"]==0 && $_POST["ADVMoneyType_UHRN"]==1){
                $total["expense_usd"]=$total["expense_usd"]-$capitalOldInfo["CPTMoney"];
                $total["expense_iqd"]=$total["expense_iqd"]+$_POST["ADVMoney_UIRN"];
            // expense ==> expense   iqd ==> usd
            }else if( $capitalOldInfo["CPTMoneyType"]==1 && $_POST["ADVMoneyType_UHRN"]==0){
                $total["expense_usd"]=$total["expense_usd"]+$_POST["ADVMoney_UIRN"];
                $total["expense_iqd"]=$total["expense_iqd"]-$capitalOldInfo["CPTMoney"];
            // expense ==> expense   iqd ==> iqd
            }else if( $capitalOldInfo["CPTMoneyType"]==1 && $_POST["ADVMoneyType_UHRN"]==1){
                $total["expense_iqd"]=$total["expense_iqd"]-$capitalOldInfo["CPTMoney"]+$_POST["ADVMoney_UIRN"];
            }
            if($total["expense_usd"]>$total["income_usd"] || $total["expense_iqd"]>$total["income_iqd"]){
                echo jsonMessages(false,16);
                exit;
            }
            $validation=new class_validation($_POST,"ADV");
            $data=$validation->returnLastVersion();
            extract($data);
            $update_advance = $database->update_data2(array(
                "tablesName"=>"advance",
                "userData"=>$data,
                "conditions"=>array()
            ));
            $database->update_data2(array(
                "tablesName"=>"capital",
                "userData"=>array(
                    "CPTMoneyType"=>$ADVMoneyType,
                    "CPTDate"=>$ADVDate,
                    "CPTMoney"=>$ADVMoney,
                    "CPTUSDRate"=>$ADVUSDRate,
                    "CPTNote"=>$ADVNote,
                    "PageName"=>$PageName,
                    "primaryKey"=>array("key"=>"CPTID","value"=>$capitalOldInfo["CPTID"])
                ),
                "conditions"=>array()
            ));
            if ($update_advance) {
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "delete") {	
            $validation=new class_validation($_POST,"ADV");
			$data=$validation->returnLastVersion();
            extract($data);	
            $database->delete_data3(array(
                "tablesName"=>"capital",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"CPTFORID","value"=>$ADVID)
                ),
                "conditions"=>array(
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"CPTTransactionType","operation"=>"=","value"=>3,"link"=>"and")
                ),
                "symbol"=>"CPT"
            ));
			$res = $database->delete_data2(array(
				"tablesName"=>"advance",
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