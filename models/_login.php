<?php
	if(!isset($_SESSION)){
		session_start();
	}
	$out  = array();
	$Validator = array('is_success'=>false, 'data'=>array());
	date_default_timezone_set('Asia/Baghdad');
	if(empty($_POST['username']) || empty($_POST['password'])){

		$error=" Please Fill User Name and Password!";
        $State=false;
        $out  = array(
			'status'  => 'false',
			'data'  => $error
		);
	}else{
		
		require_once "../_general/backend/db_connect.php";
		$username=$_POST['username'];
		$password=$_POST['password'];
		$username = stripslashes($username);
		$password=hash('sha256',$password);
	    $password= stripslashes($password);
		$Records = $database->return_data2(array(
			"tablesName"=>array("staff"),
			"columnsName"=>array("*"),
			"conditions"=>array(
				array("columnName"=>"STFUsername","operation"=>"=","value"=>$username,"link"=>"And"),
				array("columnName"=>"STFPassword","operation"=>"=","value"=>$password,"link"=>"And"),
				array("columnName"=>"STFDeleted","operation"=>"=","value"=>0,"link"=>""),
			),
			"others"=>"",
			"returnType"=>"key"
		));
		
		if (!$Records) {
		    $error= "Some thing went wrong. Please try again";
		    $State=false;
		}else{
			if($Records){
				$UserID 					= $Records['STFID'];
				$_SESSION['is_login'] 		= true;
				$_SESSION['user_id'] 		= $Records['STFID'];
				$_SESSION['STFProfileType'] 	= $Records['STFProfileType'];
				$_SESSION['access_token'] 	= md5(uniqid(rand(), true));
				$_SESSION['image_path'] 	= 'image/profile/';
				$_SESSION['username'] 		= $Records['STFUsername'];
				$_SESSION['full_name'] 		= $Records['STFFullname'];
				$_SESSION['email'] 			= $Records['STFEmail'];
				$_SESSION['profile_image'] 	= $Records['STFProfileImg'];
				$_SESSION['cover_image'] 	= $Records['STFCoverImg'];
				$_SESSION['phone_number'] 	= $Records['STFPhoneNumber'];
				$_SESSION['userPermission'] 	= $Records['STFProfilePermission'];
				$sysinfo = $database->return_data("SELECT * FROM system_settings","key");			
				$_SESSION['USD_TO_IQD']      = $sysinfo['SYSUSDRATE'];
				$_SESSION['company_logo']    = $sysinfo['SYSCompanyLogo'];
				$_SESSION['company_name']    = $sysinfo['SYSCompanyName'];
				$_SESSION['company_mngr'] = $sysinfo['SYSCompanyManager'];
				$_SESSION['company_address'] = $sysinfo['SYSCompanyAddress'];
				$_SESSION['company_phone']   = $sysinfo['SYSCompanyPhone'];
				$_SESSION['company_email']   = $sysinfo['SYSCompanyEmail'];
				$_SESSION['SYSName']   		 = $sysinfo['SYSName'];
				$_SESSION['SYSVersion']   	 = $sysinfo['SYSVersion']; 
				$_SESSION['SYSSetupDate']    = date('d-M-Y',strtotime($sysinfo['SYSSetupDate'])) ;
				$_SESSION['SYSTimezone']     = $sysinfo['SYSTimezone']; 
		        $error= "Login Success";
		        $State= true;
		        $LoginCount = $Records['STFLoginCount'] +1;
		        $query = $database->update_data("UPDATE staff SET STFLoginCount='$LoginCount' WHERE STFID = '".$UserID."'");
			}else{
		        $error= " User Name or Password Invalid ";
		        $State=false;
			} // if records
		} // if query
	} // if empty
	$Validator['is_success'] = $State;
	$Validator['data'] = $error;

	
	echo json_encode($Validator);
?>