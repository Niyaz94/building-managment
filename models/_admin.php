<?php
	include_once "../_general/backend/_header.php";
	if (isset($_POST['type']) || isset($_GET['type'])){
		//change by niyaz
		if( isset($_GET['type']) && ($_GET['type'] == "load")){
			$table = 'staff';
			$primaryKey = 'STFID';
			$columns =  array(
				array( 'db' => 'STFID', 'dt' => 0 ),
				array( 'db' => 'STFUsername', 'dt' => 1 ),
				array( 'db' => 'STFFullname',  'dt' => 2 ),
				array( 'db' => 'STFEmail',   'dt' => 3 ),
				array( 'db' => 'STFPhoneNumber',   'dt' => 4 ),
				array( 'db' => 'STFProfileType',   'dt' => 5 ),
				array( 'db' => 'STFDeleted',   'dt' => 6 )
			);
			echo json_encode(
			    SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, " STFDeleted !=1 and STFProfileType<2" )
			);
			exit;
		}
		//change by niyaz
		if ($_POST['type'] == "update") {
			//testData($_POST,0);
			$validation=new class_validation($_POST,"STF");
			$data=$validation->returnLastVersion();
			extract($data);
			$res = $database->return_data2(array(
				"tablesName"=>array("staff"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"STFUsername","operation"=>"=","value"=>$STFUsername,"link"=>"and"),
					array("columnName"=>"STFDeleted","operation"=>"=","value"=>0,"link"=>"and"),
					array("columnName"=>"STFID","operation"=>"!=","value"=>$STFID,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"row_count"
			));
			if($res>0){
				echo jsonMessages(false,7);
				exit;
			}
			$res = $database->update_data2(array(
				"tablesName"=>"staff",
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
		//change by niyaz
		if ($_POST['type']=="delete"){
			//testData($_POST,0);
			if($_SESSION['STFProfileType'] != 2 && $_SESSION['STFProfileType'] != 1){
				echo jsonMessages(false,5);
				exit;
			}
			$validation=new class_validation($_POST,"STF");
			$data=$validation->returnLastVersion();
			extract($data);	
			if($_SESSION['STFProfileType'] == 1){//if the user admin
				$res = $database->return_data2(array(
					"tablesName"=>array("staff"),
					"columnsName"=>array("*"),
					"conditions"=>array(
						array("columnName"=>"STFID","operation"=>"=","value"=>$STFID,"link"=>"And"),
						array("columnName"=>"STFProfileType","operation"=>"=","value"=>1,"link"=>"And"),
						array("columnName"=>"STFDeleted","operation"=>"=","value"=>0,"link"=>""),
					),
					"others"=>"",
					"returnType"=>"row_count"
				));
				if($res<=1){
					echo jsonMessages(false,6);
					exit;
				}
			}
			$res = $database->delete_data2(array(
				"tablesName"=>"staff",
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
		//change by niyaz
		if ($_POST['type']=="deactive") {
			if($_SESSION['STFProfileType'] != 2 && $_SESSION['STFProfileType'] != 1){
				echo jsonMessages(false,5);
				exit;
			}
			$validation=new class_validation($_POST,"STF");
			$data=$validation->returnLastVersion();
			extract($data);	
			if($_SESSION['STFProfileType'] == 1){//if the user admin
				$res = $database->return_data2(array(
					"tablesName"=>array("staff"),
					"columnsName"=>array("*"),
					"conditions"=>array(
						array("columnName"=>"STFID","operation"=>"=","value"=>$STFID,"link"=>"And"),
						array("columnName"=>"STFProfileType","operation"=>"=","value"=>1,"link"=>"And"),
						array("columnName"=>"STFDeleted","operation"=>"=","value"=>0,"link"=>""),
					),
					"others"=>"",
					"returnType"=>"row_count"
				));
				if($res<=1){
					echo jsonMessages(false,6);
					exit;
				}
			}
			$res = $database->update_data2(array(
				"tablesName"=>"staff",
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
		//change by niyaz
		if ($_POST['type']=="active") {
			if($_SESSION['STFProfileType'] != 2 && $_SESSION['STFProfileType'] != 1){
				echo jsonMessages(false,5);
				exit;
			}
			$validation=new class_validation($_POST,"STF");
			$data=$validation->returnLastVersion();
			extract($data);	
			$res = $database->update_data2(array(
				"tablesName"=>"staff",
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
		//change by niyaz
		if ($_POST['type'] == "create") {	
			//testData($_POST,0);
			if(valatador::checkForTheSame($_POST)=="false"){
				echo jsonMessages(false,4);
				exit;
			}
			$validation=new class_validation($_POST,"STF");
			$data=$validation->returnLastVersion();
			extract($data);

			$res = $database->return_data2(array(
				"tablesName"=>array("staff"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"STFUsername","operation"=>"=","value"=>$STFUsername,"link"=>"and"),
					array("columnName"=>"STFDeleted","operation"=>"=","value"=>0,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"row_count"
			));
			if($res>0){
				echo jsonMessages(false,7);
				exit;
			}

			$res = $database->insert_data2("staff",$data);
			if ($res) {	
				echo jsonMessages(true,2);
				exit;
			}else{
				echo jsonMessages(false,1);
				exit;
			}
		}
		//change by niyaz
		if ($_POST['type']=="role") {
			$validation=new class_validation($_POST,"STF");
			$data=$validation->returnLastVersion();
			extract($data);
			$res = $database->update_data2(array(
				"tablesName"=>"staff",
				"userData"=>$data,
				"conditions"=>array()
			));
			if ($res) {
				if($_SESSION['user_id']==$_POST["STFID_UIZP"]){
					echo -1;
					exit;
				}
				echo jsonMessages(true,1);
				exit;
			}else{
				echo jsonMessages(false,1);
				exit;
			}
		}
		if ($_POST['type']=="getRoles") {
			//testData($_POST,0);
			extract($_POST);
			$res = $database->return_data2(array(
				"tablesName"=>array("staff"),
				"columnsName"=>array("STFProfilePermission"),
				"conditions"=>array(
					array("columnName"=>"STFID","operation"=>"=","value"=>$STFID,"link"=>"and"),
					array("columnName"=>"STFDeleted","operation"=>"=","value"=>0,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"key"
			));
			if(!$res){
				echo jsonMessages(false,7);
				exit;
			}else{
				echo jsonMessages2(true,strlen($res["STFProfilePermission"])>0?html_entity_decode($res["STFProfilePermission"]):"{}");
				exit;
			}
		}
	}
	else{
		header("Location:../");
		exit;
	}
?>
