<?php
	include_once "../_general/backend/_header.php";

	if (isset($_POST['type']) || isset($_GET['type'])){
		//change by Niyaz
		if( isset($_GET['type']) && ($_GET['type'] == "load")){
			$table = 'languages';
			$primaryKey = 'LANID';
			$columns =  array(
				array( 'db' => 'LANID', 'dt' => 0 ),
			    array( 'db' => 'LANName', 'dt' => 1 ),
			    array( 'db' => 'LANLocal', 'dt' => 2 ),
			    array( 'db' => 'LANSymbol',   'dt' => 3  ),
				array( 'db' => 'LANDir',   'dt' => 4 ),
				array( 'db' => 'LANISDefualt',   'dt' => 5 )
			);
			echo json_encode(
			    SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, " LANDeleted=0" )
			);
			exit;
		}
		//this condition used from another php files :)
		if ($_POST['type']=="get") {
			$row = $database->return_data2(array(
				"tablesName"=>array("languages"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"LANDeleted","operation"=>"=","value"=>0,"link"=>""),
				),
				"others"=>"",
				"returnType"=>"key_all"
			));
			if (count($row)>0) {
				$op  = array();
				for ($i=0;$i<count($row);$i++) {
					$result[0] = $row[$i]['LANID'];
					$result[1] = $row[$i]['LANName'];
					$result[2] = $row[$i]['LANSymbol'];
					array_push($op,$result);
				}
				echo json_encode($op);
				exit;
			}else{
				echo 0;
				exit;
			}
		}
		if ($_POST['type'] == "update") {
			//testData($_POST,0);
			$validation=new class_validation($_POST,"LAN");
			$data=$validation->returnLastVersion();
			extract($data);
			$res = $database->return_data2(array(
				"tablesName"=>array("languages"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"LANSymbol","operation"=>"=","value"=>$LANSymbol,"link"=>"AND"),
					array("columnName"=>"LANDeleted",  "operation"=>"=","value"=>0  ,"link"=>"AND"),
					array("columnName"=>"LANLocal",  "operation"=>"=","value"=>$LANLocal  ,"link"=>"AND"),
					array("columnName"=>"LANID", "operation"=>"!=","value"=>$LANID ,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"row_count"
			));
			if ($res>0) {
				echo jsonMessages(false,2);
				exit;
			}
			$res = $database->update_data2(array(
				"tablesName"=>"languages",
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
		//change by Niyaz
		if ($_POST['type']=="delete") {
			$validation=new class_validation($_POST,"LAN");
			$data=$validation->returnLastVersion();
			extract($data);

			/*print_r($data);
			exit;*/
			$res = $database->return_data2(array(
				"tablesName"=>array("languages_keywords"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"LAKDeleted","operation"=>"=","value"=>0,"link"=>"AND"),
					array("columnName"=>"LAKFORIDLAN",  "operation"=>"=","value"=>$LANID  ,"link"=>""),
				),
				"others"=>"",
				"returnType"=>"row_count"
			));
			if ($res>0) {
				echo jsonMessages(false,3);
				exit;
			}
			//$res = $database->update_data("UPDATE languages SET LANDeleted = 1  WHERE LANID=$id");
			  
			$res = $database->delete_data2(array(
				"tablesName"=>"languages",
				"userData"=>$data,
				"conditions"=>array()
			));
	     	if ($res){
				echo jsonMessages(true,3);
	     	}else{
				echo jsonMessages(false,1);
			}
			exit;
		}
		//change by Niyaz
		if ($_POST['type'] == "create") {
			$validation=new class_validation($_POST,"LAN");
			$data=$validation->returnLastVersion();
			extract($data);

			$res = $database->return_data2(array(
				"tablesName"=>array("languages"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"LANSymbol","operation"=>"=","value"=>$LANSymbol,"link"=>"AND"),
					array("columnName"=>"LANName",  "operation"=>"=","value"=>$LANName  ,"link"=>"AND"),
					array("columnName"=>"LANDeleted",  "operation"=>"=","value"=>0  ,"link"=>"AND"),
					array("columnName"=>"LANLocal", "operation"=>"=","value"=>$LANLocal ,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"key_all"
			));

			if (count($res)>0) {
				echo jsonMessages(false,2);
				exit;
			}else{	
				$res = $database->insert_data2("languages",$data);
				if ($res) {	
					echo jsonMessages(true,2);
					exit;
				}else{
					echo jsonMessages(false,1);
					exit;
				}
			}
		}
	}
	else{
		header("Location:../");
		exit;
	}
?>

