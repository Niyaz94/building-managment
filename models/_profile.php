<?php
	include_once "../_general/backend/_header.php";

	if($_POST['type']=='updateProfileImage'){
		$fileSaveName = rand(1000,100000) . "-" . $_FILES["profile_image"]['name'];
		$fileUpload=uploadingImageFile($_FILES,"../image/profile/","profile_image",$fileSaveName);
		if($fileUpload=="false9"){
			echo jsonMessages(false,9);
		}else if($fileUpload=="false10"){
			echo jsonMessages(false,10);
		}else if($fileUpload=="false11"){
			echo jsonMessages(false,11);
		}else if($fileUpload=="true1"){
			$_POST["STFID_UIZP"]=$_SESSION['user_id'];
			$validation=new class_validation($_POST,"STF");
			$data=$validation->returnLastVersion();
			$data["STFProfileImg"]=$fileSaveName;
			//testData($data,0);
			$res = $database->update_data2(array(
				"tablesName"=>"staff",
				"userData"=>$data,
				"conditions"=>array()
			));
			if ($res) {
				updateSession($database);
				echo jsonMessages(true,1);
			}else{
				echo jsonMessages(false,1);
			}
		}else{
			echo jsonMessages(false,1);
		}
		exit;					
	}
	if($_POST['type']=='updateProfileCoverImage'){
		
		$fileSaveName = rand(1000,100000) . "-" . $_FILES["cover_image"]['name'];
		$fileUpload=uploadingImageFile($_FILES,"../image/profile/","cover_image",$fileSaveName);
		if($fileUpload=="false9"){
			echo jsonMessages(false,9);
		}else if($fileUpload=="false10"){
			echo jsonMessages(false,10);
		}else if($fileUpload=="false11"){
			echo jsonMessages(false,11);
		}else if($fileUpload=="true1"){
			$_POST["STFID_UIZP"]=$_SESSION['user_id'];
			$validation=new class_validation($_POST,"STF");
			$data=$validation->returnLastVersion();
			$data["STFCoverImg"]=$fileSaveName;
			$res = $database->update_data2(array(
				"tablesName"=>"staff",
				"userData"=>$data,
				"conditions"=>array()
			));
			if ($res) {
				updateSession($database);
				echo jsonMessages(true,1);
			}else{
				echo jsonMessages(false,1);
			}
		}else{
			echo jsonMessages(false,1);
		}
		exit;
	}
	if($_POST['type']=='updateProfile'){
		//testData($_POST,0);
		$_POST["STFID_UIZP"]=$_SESSION['user_id'];
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
			updateSession($database);
			echo jsonMessages(true,1);
			exit;
		}else{
			echo jsonMessages(false,1);
			exit;
		}
	}
	if($_POST['type']=='updatePassword'){
		//testData($_POST,0);
		if(valatador::checkForTheSame($_POST)=="false"){
			echo jsonMessages(false,4);
			exit;
		}
		$_POST["STFID_UIZP"]=$_SESSION['user_id'];
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
	function updateSession($database){
		$Records = $database->return_data2(array(
			"tablesName"=>array("staff"),
			"columnsName"=>array("*"),
			"conditions"=>array(
				array("columnName"=>"STFID","operation"=>"=","value"=>$_SESSION['user_id'],"link"=>"")
			),
			"others"=>"",
			"returnType"=>"key"
		));
		$_SESSION['username'] 		= $Records['STFUsername'];
		$_SESSION['full_name'] 		= $Records['STFFullname'];
		$_SESSION['email'] 			= $Records['STFEmail'];
		$_SESSION['profile_image'] 	= $Records['STFProfileImg'];
		$_SESSION['cover_image'] 	= $Records['STFCoverImg'];
		$_SESSION['phone_number'] 	= $Records['STFPhoneNumber'];
	}
?>
