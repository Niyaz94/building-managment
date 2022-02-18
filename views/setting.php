<?php 
    $fileName=__FILE__;
	include_once "header.php";
	$_returnDataForTimeZone=new _returnDataFromFile("timezone");
	$timezones=$_returnDataForTimeZone->returnFullData();
	$settingPermssion=json_decode($_SESSION["userPermission"],true);
?>
<link rel="stylesheet" href="assets/csspages/setting.css">
<div class="navbar navbar-default navbar-xs navbar-component no-border-radius-top">
	<ul class="nav navbar-nav visible-xs-block">
		<li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
	</ul>
	<div class="navbar-collapse collapse" id="navbar-filter">
		<ul class="nav navbar-nav">
			
			<?php
			$systemActive=$companyActive="";
			if($_SESSION["STFProfileType"]==1 || $_SESSION["STFProfileType"]==2 || ($settingPermssion["buttons"]["LOGV_PG"]==1 && $settingPermssion["buttons"]["LOGF_PG"]==1)){
				echo '
					<li class="active">
						<a href="#activity" data-toggle="tab">
							<i class="icon-menu7 position-left">
							</i> <span class="multi_lang">System Setting</span>
						</a>
					</li>
					<li>
						<a href="#schedule" data-toggle="tab">
							<i class="icon-certificate position-left"></i>
							<span class="multi_lang">Company Info.</span> 
						</a>
					</li>	
				';
				$systemActive="active in";
			}else if($settingPermssion["buttons"]["LOGV_PG"]==1){
				echo '
					<li class="active">
						<a href="#activity" data-toggle="tab">
							<i class="icon-menu7 position-left">
							</i> <span class="multi_lang">System Setting</span>
						</a>
					</li>
				';
				$systemActive="active in";
			}else if($settingPermssion["buttons"]["LOGF_PG"]==1){
				echo '
					<li class="active">
						<a href="#schedule" data-toggle="tab">
							<i class="icon-certificate position-left"></i>
							<span class="multi_lang">Company Info.</span> 
						</a>
					</li>
				';
				$companyActive="active in";
			}
		?>
		</ul>
	</div>
</div>
<input type="hidden" id="PageName" name="PageName" value="<?php echo __FILE__;?>">
<input type="hidden" id="SystemTimeZone" name="SystemTimeZone" value="<?php echo $_SESSION['SYSTimezone'];?>">
<div class="tabbable">
	<div class="tab-content">	
		<div class="tab-pane fade <?php echo $systemActive;?>" id="activity">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title multi_lang">General Settings</h6>
				</div>
				<div class="row">
					<div class="col-md-2">
						<i class="icon-office position-right text-primary" style="font-size: 100px; margin-left: 20px;"></i>
					</div>
					<div class="col-md-8">
						<form id="system_info_form">
							<input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['user_id']; ?>">
							<div class="form-group"><?php 
								echo input1("","text","User Name","SYSName_RRR","required","icon-credit-card",$_SESSION['SYSName'],"readonly");
							?></div>
							<div class="form-group"><?php 
								echo input1("","text","Version","SYSVersion_RRR","required","icon-versions",$_SESSION['SYSVersion'],"readonly");
							?></div>
							<div class="form-group"><?php 
								echo input1("","text","Setup Date","SYSSetupDate_RRR","required","icon-calendar52",$_SESSION['SYSSetupDate'],"readonly");
							?></div>
							<div class="form-group"><?php 
								echo input1("","number","USD Change Rate (1$)","SYSUSDRATE_UIZ","required","icon-cash",$_SESSION['USD_TO_IQD']);
							?></div>
							<div class="form-group"><?php
								echo input2("",$timezones,"Time Zone","SYSTimezone_USZ","required","icon-sphere",1);
							?></div>
							<div class="form-group"><?php 
								echo button1("updateprofile","submit","Update","icon-circle-right2");
							?></div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade <?php echo $companyActive;?>" id="schedule">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title multi_lang">Company Information</h6>
				</div>
				<div class="row">
					<div class="col-md-2">
					<div class="media-left">
						<form  id="imageUploadFormPic"  enctype="multipart/form-data">
							<span class="btn btn-default btn-file" style="box-shadow: 0 0 3px rgba(0, 0, 0, 0); background-color: rgba(245, 245, 245, 0);">
								<a href="#" id="profileLink" class="profile-thumb">
									<?php if($_SESSION['company_logo']==null){  ?>
										<img src="_general/image/profile/noimg.jpg" class="img-circle img-xl" alt="">
									<?php }else{ ?>
										<img src="_general/image/sys/<?php echo $_SESSION['company_logo']; ?>" class="img-circle img-xl" alt="" width="150">
									<?php } ?>
									<input type="file" id="SYSCompanyLogo" name="SYSCompanyLogo" class="bra">
								</a>
						 </span>
						</form>
					</div>
				</div>
				<div class="col-md-8">
					<form id="company_info_form">
						<input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['user_id']; ?>">
						<input type="hidden" id="access_token" name="access_token" value="">
						<div class="form-group"><?php
							echo input1("","text","Company Name","SYSCompanyName_USZ","required","icon-office",$_SESSION['company_name']);
						?></div>
						<div class="form-group"><?php 
							echo input1("","text","Company Manager Name","SYSCompanyManager_USZ","required","icon-user-tie",$_SESSION['company_mngr']);
						?></div>
						<div class="form-group"><?php 
							echo input1("","text","Company Address","SYSCompanyAddress_USZ","required","icon-location3",$_SESSION['company_address']);
						?></div>
						<div class="form-group"><?php 
							echo input1("","text","Phone","SYSCompanyPhone_UIZ","required","icon-phone",$_SESSION['company_phone']);
						?></div>
						<div class="form-group"><?php 
							echo input1("","text","E-Mail","SYSCompanyEmail_UIZ","required","icon-mail5",$_SESSION['company_email']);
						?></div>
						<div class="form-group"><?php 
							echo button1("updateCompanyInfo","submit","Update","icon-circle-right2");
						?></div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<script type="text/javascript" src="controllers/setting.js?random=<?php echo uniqid(); ?>"></script>

<script >
	$("#time_zone").val('<?php echo $_SESSION['SYSTimezone']; ?>');
</script>