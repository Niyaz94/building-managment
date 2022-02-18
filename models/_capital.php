<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                SELECT
                    capital.*
                FROM
                    capital
                WHERE
                    CPTDeleted=0
            ) as table1";
            $primaryKey = "CPTID";
            $where="";
            $columns =  array(
                array( "db" => "CPTID", "dt" => 0 ),
                array( "db" => "CPTOperationType", "dt" => 1 ),
                array( "db" => "CPTMoney", "dt" => 2 ),
                array( "db" => "CPTMoneyType", "dt" => 3 ),
                array( "db" => "CPTUSDRate", "dt" => 4 ),
                array( "db" => "CPTPaidType", "dt" => 5 ),
                array( "db" => "CPTTransactionType", "dt" => 6 )
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") { 
            $validation=new class_validation($_POST,"CPT");
            $data=$validation->returnLastVersion();
            $data["CPTTransactionType"]=6;

            $capital_id = $database->insert_data2("capital",$data);
           
            if ($capital_id>0) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {   
            $total=totalAllType($database);
            $capitalOldInfo = $database->return_data3(array(
                "tablesName"=>array("'capital'"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"CPTID","operation"=>"=","value"=>$_POST["CPTID_UIZP"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            if( $capitalOldInfo["CPTMoneyType"]==0 && $_POST["CPTMoneyType_UHRN"]==0){
                $total["income_usd"]=$total["income_usd"]-$capitalOldInfo["CPTMoney"]+$_POST["CPTMoney_UIRN"];
            // expense ==> expense   usd ==> iqd
            }else if( $capitalOldInfo["CPTMoneyType"]==0 && $_POST["CPTMoneyType_UHRN"]==1){
                $total["income_usd"]=$total["income_usd"]-$capitalOldInfo["CPTMoney"];
                $total["income_iqd"]=$total["income_iqd"]+$_POST["CPTMoney_UIRN"];
            // expense ==> expense   iqd ==> usd
            }else if( $capitalOldInfo["CPTMoneyType"]==1 && $_POST["CPTMoneyType_UHRN"]==0){
                $total["income_usd"]=$total["income_usd"]+$_POST["CPTMoney_UIRN"];
                $total["income_iqd"]=$total["income_iqd"]-$capitalOldInfo["CPTMoney"];
            // expense ==> expense   iqd ==> iqd
            }else if( $capitalOldInfo["CPTMoneyType"]==1 && $_POST["CPTMoneyType_UHRN"]==1){
                $total["income_iqd"]=$total["income_iqd"]-$capitalOldInfo["CPTMoney"]+$_POST["CPTMoney_UIRN"];
            }
            if($total["expense_usd"]>$total["income_usd"] || $total["expense_iqd"]>$total["income_iqd"]){
                echo jsonMessages(false,16);
                exit;
            }
            $validation=new class_validation($_POST,"CPT");
            $data=$validation->returnLastVersion();
            $update_capital=$database->update_data2(array(
                "tablesName"=>"capital",
                "userData"=>$data,
                "conditions"=>array()
            ));
            if ($update_capital) {
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "delete") {
            $total=totalAllType($database);
            if($_POST["CPTMoneyType_UIZN"]==0){
                $total["income_usd"]=$total["income_usd"]+$_POST["CPTMoney_UIZN"];
                if($total["income_usd"]<$total["expense_usd"]){
                    echo jsonMessages(false,17);
                    exit;
                }
            }else if($_POST["CPTMoneyType_UIZN"]==1){
                $total["income_iqd"]=$total["income_iqd"]+$_POST["CPTMoney_UIZN"];
                if($total["income_iqd"]<$total["expense_iqd"]){
                    echo jsonMessages(false,17);
                    exit;
                }
            }
            $validation=new class_validation($_POST,"CPT");
			$data=$validation->returnLastVersion();
            extract($data);	
			$res = $database->delete_data2(array(
				"tablesName"=>"capital",
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