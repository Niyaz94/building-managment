<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "capital";
            $primaryKey = "CPTID";
            $where="CPTTransactionType=4 and CPTFORID=".$_GET["id"]." and CPTDeleted=0 ";
            $columns =  array(
                array( "db" => "CPTID", "dt" => 0 ),  
                array( "db" => "CPTMoney", "dt" => 1 ),  
                array( "db" => "CPTUSDRate", "dt" => 2 ),  
                array( "db" => "CPTNote", "dt" => 3 ,"formatter"=>function($d){
                    return html_entity_decode($d);
                }),  
                array( "db" => "CPTDeleted", "dt" => 4 ),  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            $validation=new class_validation($_POST,"CPT");
            $data=$validation->returnLastVersion();
            $data["CPTTransactionType"]=4;
            extract($data);
            $data["CPTDate"]=date("Y-m-d");
            $res = $database->insert_data2("capital",$data);
            if ($res) {	
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
                    array("columnName"=>"CPTFORID","operation"=>"=","value"=>$_POST["CPTID_UIZP"],"link"=>"and"),
                    array("columnName"=>"CPTTransactionType","operation"=>"=","value"=>3,"link"=>"and"),
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            if( $capitalOldInfo["CPTMoneyType"]==0 && $_POST["CPTMoney_UIRN"]==0){
                $total["expense_usd"]=$total["expense_usd"]-$capitalOldInfo["CPTMoney"]+$_POST["CPTMoney_UIRN"];
            }else if( $capitalOldInfo["CPTMoneyType"]==1 && $_POST["CPTMoney_UIRN"]==1){
                $total["expense_iqd"]=$total["expense_iqd"]-$capitalOldInfo["CPTMoney"]+$_POST["CPTMoney_UIRN"];
            }
            if($total["expense_usd"]>$total["income_usd"] || $total["expense_iqd"]>$total["income_iqd"]){
                echo jsonMessages(false,16);
                exit;
            }
            $validation=new class_validation($_POST,"CPT");
            $data=$validation->returnLastVersion();
            extract($data);
            $data["CPTDate"]=date("Y-m-d");
            $capital_update = $database->update_data2(array(
                "tablesName"=>"capital",
                "userData"=>$data,
                "conditions"=>array()
            ));
            if ($capital_update) {
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "delete") {	
            $total=totalAllType($database);
            $capitalOldInfo = $database->return_data3(array(
                "tablesName"=>array("'capital'"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"CPTFORID","operation"=>"=","value"=>$_POST["CPTID_UIZP"],"link"=>"and"),
                    array("columnName"=>"CPTTransactionType","operation"=>"=","value"=>3,"link"=>"and"),
                    array("columnName"=>"CPTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            if( $capitalOldInfo["CPTMoneyType"]==0){
                $total["expense_usd"]=$total["expense_usd"]-$capitalOldInfo["CPTMoney"];
            }else if( $capitalOldInfo["CPTMoneyType"]==1){
                $total["expense_iqd"]=$total["expense_iqd"]-$capitalOldInfo["CPTMoney"];
            }
            if($total["expense_usd"]>$total["income_usd"] || $total["expense_iqd"]>$total["income_iqd"]){
                echo jsonMessages(false,16);
                exit;
            }
            $validation=new class_validation($_POST,"CPT");
			$data=$validation->returnLastVersion();
            extract($data);	
			$delete_capital = $database->delete_data2(array(
				"tablesName"=>"capital",
				"userData"=>$data,
				"conditions"=>array()
			));
			if ($delete_capital) {
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