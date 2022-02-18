<?php 
    $fileName=__FILE__;
	include_once "header.php";
	$userPermission=json_decode(html_entity_decode($_SESSION["userPermission"]),true)[4];
?>
<div class="profile-cover">

	<?php
		if($_SESSION['cover_image']==null){
			echo '<div class="profile-cover-img" style="background-image: url(_general/image/profile/img_same_dimension_2.jpg)"></div>';
		}else{
			echo '<div class="profile-cover-img" style="background-image: url(_general/image/profile/'.$_SESSION['cover_image'].')"></div>';
		}
	?>
	<div class="media">
		<div class="media-left">
			<form id="imageUploadImageForm" enctype="multipart/form-data">
				<span class="btn btn-default btn-file" style="box-shadow: 0 0 3px rgba(0, 0, 0, 0); background-color: rgba(245, 245, 245, 0);"><a
					href="#" id="profileLink" class="profile-thumb">
					<?php
						if($_SESSION['profile_image']==null){
							echo '<img src="_general/image/profile/noimg.jpg" class="img-circle img-xl" alt="">';
						}else{
							echo '<img src="_general/image/profile/'.$_SESSION['profile_image'].'" class="img-circle img-xl" alt="">';
						}
						
						if($_SESSION["STFProfileType"]==1 || $_SESSION["STFProfileType"]==2 || $userPermission["buttons"]["PRFPI"]==1){
							echo '<input type="file" id="profile_image" name="profile_image" class="bra">';
						}
					?>
				</a></span>
			</form>
		</div>
		<div class="media-body">
			<h1>
				<?php echo $_SESSION['full_name']; ?> <small class="display-block">
				<?php echo $_SESSION['username']; ?></small>
			</h1>
		</div>
		<div class="media-right media-middle">
			<ul class="list-inline list-inline-condensed no-margin-bottom text-nowrap">
				<li>
					<form id="imageUploadCoverImageForm" enctype="multipart/form-data">
						<span class="btn btn-default btn-file"><i class="icon-file-picture position-left"></i> <span class="multi_lang">Cover
								Photo</span>
							
							<?php
								if($_SESSION["STFProfileType"]==1 || $_SESSION["STFProfileType"]==2 || $userPermission["buttons"]["PRFCI"]==1){
									echo '<input type="file" id="cover_image" name="cover_image" class="bra">';
								}
							?>
						</span>
					</form>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="navbar navbar-default navbar-xs navbar-component no-border-radius-top">
	<ul class="nav navbar-nav visible-xs-block">
		<li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
	</ul>
	<div class="navbar-collapse collapse" id="navbar-filter">
		<ul class="nav navbar-nav">
		<?php
			$profileActive=$passwordActive="";
			if($_SESSION["STFProfileType"]==1 || $_SESSION["STFProfileType"]==2 ||($userPermission["buttons"]["PRFIP"]==1 && $userPermission["buttons"]["PRFCP"]==1)){
				echo '
					<li class="active">
						<a href="#activity" data-toggle="tab">
							<i class="icon-menu7 position-left"></i>
							<span class="multi_lang">PersonalInformation </span>
						</a>
					</li>
					<li>
						<a href="#schedule" data-toggle="tab">
							<i class="icon-key position-left"></i> 
							<span class="multi_lang">Change Password</span>
						</a>
					</li>
				';
				$profileActive="active in";
			}else if($userPermission["buttons"]["PRFIP"]==1){
				echo '
					<li class="active">
						<a href="#activity" data-toggle="tab">
							<i class="icon-menu7 position-left"></i>
							<span class="multi_lang">PersonalInformation </span>
						</a>
					</li>
				';
				$profileActive="active in";
			}else if($userPermission["buttons"]["PRFCP"]==1){
				echo '
					<li class="active">
						<a href="#schedule" data-toggle="tab">
							<i class="icon-key position-left"></i> 
							<span class="multi_lang">Change Password</span>
						</a>
					</li>
				';
				$passwordActive="active in";
			}
		?>
		</ul>
		
	</div>
</div>

<div class="tabbable">
	<div class="tab-content">
		<div class="tab-pane fade <?php echo $profileActive;?>" id="activity">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title multi_lang">Personal Information</h6>
				</div>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
					<input type="hidden" name="language_id" id="language_id" value="<?php echo $_SESSION["language_id"];?>">
						<form id="profileform">
							<input type="hidden" name="selectedLanguage" id="selectedLanguage" value="<?php echo $_SESSION['language_id'];?>">
							<?php 
								$res = $database->return_data2(array(
									"tablesName"=>array("languages"),
									"columnsName"=>array("LANID,LANName"),
									"conditions"=>array(
										array("columnName"=>"LANDeleted","operation"=>"=","value"=>0,"link"=>""),
									),
									"others"=>"",
									"returnType"=>"key_all"
								));
								$selectData=array();
								for($i=0;$i<count($res);$i++){
									$selectData[$res[$i]["LANID"]]=$res[$i]["LANName"];
								}

								//echo input2("",$selectData,"Profile Type","LANName_USZ","required","icon-earth");
							?>
							<div class="form-group"><?php 
								echo input1("","text","User Name","STFUsername_USZ","required","icon-user",$_SESSION['username']);
							?></div>
							<div class="form-group"><?php 
								echo input1("","text","Full Name","STFFullname_USZ","required","icon-vcard",$_SESSION['full_name']);
							?></div>
							<div class="form-group"><?php 
								echo input1("","text","Phone No.","STFPhoneNumber_UPZ","","icon-phone2",$_SESSION['phone_number']);
							?></div>
							<div class="form-group"><?php 
								echo input1("","email","E-Mail","STFEmail_UEZ","","icon-mail5",$_SESSION['email']);
							?></div>
							<div class="form-group"><?php 
								echo button1("updateprofile","submit","Update","icon-circle-right2");
							?></div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade <?php echo $passwordActive;?>" id="schedule">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title multi_lang">Change Password</h6>
				</div>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<form id="profileformpass">
							<div class="form-group"><?php 
								echo input1("","password","New Password","STFPassword_UWZ","required","icon-key");
							?></div>
							<div class="form-group"><?php 
								echo input1("","password","Re-Type Password","STFPassword_UWW","required","icon-key");
							?></div>
							<div class="form-group"><?php 
								echo button1("updatePassword","submit","Password","icon-circle-right2");
							?></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="controllers/profile.js?random=<?php echo uniqid(); ?>"></script>