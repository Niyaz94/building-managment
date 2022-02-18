<?php
	
	if(!isset($_SESSION)) { 
		session_start(); 
		//$_SESSION=array();
		//session_destroy();
	}
	
	if(!isset($_SESSION['is_login']) ){
		header("location: login.php");
		exit;
	}else{
		include_once '_general/backend/languageSetting.php'; 
		include_once '_general/backend/headerJS.php';	
	}
?>
<!DOCTYPE html>
<html lang="en" dir="<?php echo $_SESSION["languageSetting"]["DIR"]; ?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Accountant</title>
		<noscript>
			<META HTTP-EQUIV="Refresh" CONTENT="0;URL=java_disabled.html">
		</noscript>
		<link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
		<link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/core.css" rel="stylesheet" type="text/css">
		<link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/components.css" rel="stylesheet" type="text/css">
		<link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/colors.css" rel="stylesheet" type="text/css">

            
		<script type="text/javascript" src="../API/assets/js/plugins/loaders/pace.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/core/libraries/jquery.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/core/libraries/bootstrap.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/loaders/blockui.min.js"></script>

		<script type="text/javascript" src="../API/assets/js/plugins/forms/styling/uniform.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/ui/nicescroll.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/ui/drilldown.js"></script>
		<?php 
			$pageName = '';
			if(!isset($_GET['p'])){
				echo '<script>window.location="starter.php";</script>';
			}else{
				$pageName = $_GET['p'];
			}			
			$pageInfo=json_decode($_returnDataFromFile->returnDataByPageName($pageName),true);
			if(empty($pageInfo)){
				echo '<script>window.location="starter.php";</script>';
			}	
			echo headerJS($pageName);
		?>

	</head>
	
	<body style=" font-family: 'Noto Kufi Arabic', Times, serif;">
		<script >
			var CURRENT_BTN_ICON = '<?php echo $pageInfo['icon'] ; ?>';
			var PAGE_TITLE 	 	 = '<?php echo $pageInfo['name'] ; ?>';
			var IMG_PATH 	 	 = '<?php echo $_SESSION['image_path'] ; ?>';
			var COMPANY_ADDRESS	 = '<?php echo $_SESSION['company_address'] ; ?>';
			var COMPANY_PHONE 	 = '<?php echo $_SESSION['company_phone'] ; ?>';
			var COMPANY_EMAIL 	 = '<?php echo $_SESSION['company_email'] ; ?>';
			var COMPANY_LOGO 	 = '<?php echo $_SESSION['company_logo'] ; ?>';
			var COMPANY_NAME 	 = '<?php echo $_SESSION['company_name'] ; ?>';
			$("#index_page_title").html(Translation(PAGE_TITLE));
			var CAT_ID = 0;
		</script>
		<?php
			include('models/navbar.php');
		?>
		<div class="page-header">
			<div class="page-header-content">
				<div class="page-title">		
				</div>	
			</div>
		</div>
		<div class="page-container">
			<div class="page-content">
				<?php 
					if($pageInfo["page_type"]=="normal"){
						include('views/'.$pageName.'.php'); 
					}else if($pageInfo["page_type"]=="report"){
						include('views/report/'.$pageName.'.php'); 
					}
				?>
				</div>
			</div>
		</div>
		<script >
			Auto_Translate()
		</script>

	</body>
</html>
