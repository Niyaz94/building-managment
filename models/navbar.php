<?php 
      include_once "_general/backend/generalFunctions.php";
?>
<link rel="stylesheet" href="_general/style/navbar.css">					
<div class="navbar navbar-inverse bg-indigo navbar-fixed-top">
	<?php
            $filePermissionData=$_returnDataFromFile->returnFullData();
			$user=json_decode(html_entity_decode($_SESSION["userPermission"]),true);
			$dropdown=array();
			$single=array();
			$userKeys=checkLoginActive();
			for($i=0;$i<count($filePermissionData);$i++){
				extract($filePermissionData[$i]);
				$showHeader=false;
				if($_SESSION['STFProfileType']==2 && $show!="none" && $show!="starter"){
					$showHeader=true;
				}else if($_SESSION['STFProfileType']==1 && $type!="devloper" && $show!="starter" && $show!="none"){
					$showHeader=true;
				}else if($type!="admin" && $type!="developer" && $show!="header" && in_array($id,$userKeys) && $show!="none"){
					$showHeader=true;
				}else if($type!="admin" && $type!="developer" && $show!="header" && $show!="none" && $page_type=="report"){
					$reportUserPermission=$user[8];
					for ($j=0;$j<count($filePermissionData[8]["buttons"]);$j++) {
						if($reportUserPermission["active"]==1 && $reportUserPermission["buttons"][$page."_RP"]==1){
							$showHeader=true;
						}
					}
			}
			if($showHeader && $navbar["type"]=="dropdown"){
				if(isset($dropdown[$navbar["id"]])){
					$dropdown[$navbar["id"]][0].='
						<li class="'.$color.'">
							<a href="index.php?p='.$page.'">
								<i class="'.$icon.' position-left"></i>
								<span class="multi_lang">'.$name.'</span> 
							</a>
						</li>
					';
				}else{
					$dropdown[$navbar["id"]][0]='
						<li class="'.$color.'">
							<a href="index.php?p='.$page.'">
								<i class="'.$icon.' position-left"></i>
								<span class="multi_lang">'.$name.'</span> 
							</a>
						</li>
					';
					$dropdown[$navbar["id"]][1]=$navbar["title"];
					$dropdown[$navbar["id"]][2]=$navbar["dir"];
				}
          	}else if($showHeader && $navbar["type"]=="single"){
				$single[$navbar["id"]]='
					<a class="navbar-brand" href="index.php?p='.$page.'">
						<i class="'.$icon.' position-left"></i>
						<span class="multi_lang">'.$name.'</span>
					</a>
				';
			}
		}    
    ?>
	<div class="navbar-header">
		<a class="navbar-brand" href="starter.php">
			<i class="icon-home2 position-left"></i>
			<span class="multi_lang">Main</span>
		</a>
		<?php
			foreach($single as $key=>$value){
				echo $value;
			}
		?>	
	</div>
	<div class="navbar-collapse collapse" id="navbar-mobile">
		<ul class="nav navbar-nav">
			<?php
				foreach($dropdown as $key=>$value){
					if($value[2]=="left")
					echo '
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu3 position-left"></i><span class="multi_lang">'.$value[1].'</span> 
								<span class="visible-xs-inline-block position-right"></span>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								'.$value[0].'
							</ul>
						</li>
					';
				}
			?>
			<li class="dropdown ">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-bubbles4" style="font-size:24px;"></i>
					<span class="visible-xs-inline-block position-right">Messages</span>
					<span class="badge bg-warning-400" id="headerNavbar"></span>
				</a>	
				<div class="dropdown-menu dropdown-content width-350" style="min-width:500px;">
					<div class="dropdown-content-heading">
					</div>
					<ul class="media-list dropdown-content-body" id="navbarBody">
						
					</ul>
					<div class="dropdown-content-footer">
						<a href="index.php?p=agreement" data-popup="tooltip" title="Agreement"><i class="icon-menu display-block"></i></a>
					</div>
				</div>
			</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">	
			<?php
				foreach($dropdown as $key=>$value){
					if($value[2]=="right")
					echo '
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu3 position-left"></i><span class="multi_lang">'.$value[1].'</span> 
								<span class="visible-xs-inline-block position-right"></span>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								'.$value[0].'
							</ul>
						</li>
					';
				}
			?>
			<li class="dropdown dropdown-user">
				<a class="dropdown-toggle" data-toggle="dropdown">
					<?php if($_SESSION['profile_image']==null){  ?>
						<img src="_general/image/profile/noimg.png" alt="">
					<?php }else{ ?>
						<img src="<?php echo $_SESSION['image_path'].$_SESSION['profile_image']; ?>" alt="">
					<?php } ?>
					<span><?php echo $_SESSION['username']; ?></span>
					<i class="caret"></i>
				</a>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="index.php?p=profile"><i class="icon-user-plus"></i><span class="multi_lang">My Profile</span> </a></li>
					
					<li class="divider"></li>
					<li><a href="models/logout.php"><i class="icon-switch2"></i><span class="multi_lang">Log-Out</span> </a></li>
				</ul>
			</li>
			<li class="dropdown dropdown-user">
				<a class="dropdown-toggle" data-toggle="dropdown">
					<i style="font-size:36px;" class="icon-earth position-left"></i>
				</a>
				<ul class="dropdown-menu dropdown-menu-right">
					<?php
						$language = $database->return_data2(array(
							"tablesName"=>array("languages"),
							"columnsName"=>array("*"),
							"conditions"=>array(
								array("columnName"=>"LANDeleted","operation"=>"=","value"=>0,"link"=>""),
							),
							"others"=>"",
							"returnType"=>"key_all"
						));
						for($i=0;$i<count($language);++$i){
							echo '<li><a href="#" onclick="changeLanfuage('.$language[$i]["LANID"].',`'.$language[$i]["LANName"].'`,`'.$language[$i]["LANDir"].'`,`'.($language[$i]["LANDir"]=="ltr"?"css_ltr":"css").'`)"><span class="multi_lang">'.$language[$i]["LANName"].'</span> </a></li>';
						}
					?>
				</ul>
			</li>
		</ul>
	</div>
</div>
<script>
	$(document).ready(function () {
		//setInterval(() => {
			$.ajax({
				url: "models/_general.php",
				type: "POST",
				dataType:"json",
				data: {
					"type":"navbarData"
				},
				complete: function () {},
				beforeSend: function () {},
				success: function (res) {
					$("#navbarBody").empty();
					for (let index = 0; index < res.length; index++) {
						$("#navbarBody").append(`
							<li class="media">
								<div class="media-left">
									<a href="index.php?p=agreementrent&agrid=${res[index]["AGRID"]}" class="btn border-success btn-flat btn-rounded btn-icon btn-sm">
										${res[index]["AGRShopTitle"]}
									</a>
								</div>
								<div class="media-body">
									<a href="#" class="media-heading">
										<span class="media-annotation pull-right">${res[index]["date"]}</span>
										<span class="text-muted" >${res[index]["text"]}</span>
									</a>
								</div>
							</li>
						`);					
					}
					$("#headerNavbar").text(res.length);
					
				},
				fail: function (err){},
				always:function(){}
			});
		//}, 6000);
	});
</script>
	