<?php 
	$fileName=__FILE__;
	include_once "header.php";
?>
<div id="addadminCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
	<div class="panel-body">
		<form class="form-horizontal" method="post" id="add_form" enctype="multipart/form-data">
			<div>
				<div class="col-sm-12">
					<div class="form-group"><?php 
						echo input1("col-sm-6","text","User Name","STFUsername_ISZ","required","icon-user");
						echo input1("col-sm-6","text","Full Name","STFFullname_ISZ","required","icon-users2");
					?></div>
					<div class="form-group"><?php 
						echo input1("col-sm-4","email","E-Mail","STFEmail_IEZ","","icon-envelop5");
						echo input1("col-sm-4","text","Phone","STFPhoneNumber_IPZ","","icon-phone-plus");
						echo input2("col-sm-4",array(""=>"",0=>"permision",1=>"admin"),"Profile Type","STFProfileType_ICZ","","icon-unlocked2",0,"select2Class","","Please Select User Type...");
					?></div>
					<div class="form-group"><?php 
						echo input1("col-sm-6","password","Password","STFPassword_IWZ","required","icon-key");
						echo input1("col-sm-6","password","Re-Type Password","STFPassword_IWW","required","icon-key");
					?></div>
				</div>
				
			</div>
			<div class="text-right">
				<button type="submit" id="submit_add_btn" class="btn btn-primary btn-labeled btn-labeled-left">
					<span class="multi_lang">Save</span> <b><i class="icon-floppy-disk"></i></b>
				</button>
				<a href="#addadminCollapse" id="add_form_cancel" class="btn btn-labeled btn-labeled-left bg-danger heading-btn" data-toggle="collapse">
					<span class="multi_lang">Close</span> <b><i class="icon-cross"></i></b>
				</a>
			</div>
		</form> 
	</div>
</div>
<div id="edit_form_cont" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<form class="form-horizontal"  name="edit_form" method="post" id="edit_form" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
					<h4 class="modal-title multi_lang">Edit</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="STFID_UIZP" id="STFID_UIZP" value="">
					<div>
						<div class="col-sm-12">
							<div class="form-group"><?php 
								echo input1("col-sm-6","text","User Name","STFUsername_USZ","required","icon-user");
								echo input1("col-sm-6","text","Full Name","STFFullname_USZ","required","icon-users2");
							?></div>
							<div class="form-group"><?php 
								echo input1("col-sm-4","email","E-Mail","STFEmail_UEZ","","icon-envelop5");
								echo input1("col-sm-4","text","Phone","STFPhoneNumber_UPZ","","icon-phone-plus");
								echo input2("col-sm-4",array(""=>"",0=>"permision",1=>"admin"),"Profile Type","STFProfileType_UCZ","","icon-unlocked2",0,"select2Class","","Please Select User Type...");
							?></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<?php
						echo button2("submit_edit_btn","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
						echo button2("closePasswordModal","button","Close","","btn btn-warning",'data-dismiss="modal"');
					?>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="edit_role" class="modal fade" style="display: none;">
		<div class="modal-dialog modal-full">
		<form class="form-horizontal"  name="edit_form_role" method="post" id="edit_form_role" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
					<h4 class="modal-title multi_lang">Change User Rules</h4>
				</div>
				<div class="modal-body">	
					<input type="hidden" name="STFID_Role" id="STFID_Role" value="">
					<?php
						$data=json_decode($_returnDataFromFile->returnALLPermissionPage(),true);
						$user=json_decode($_returnDataFromFile->returnALLPermissionButton(),true);
						echo '<input type="hidden" id="userActionData" name="userActionData" value="'.htmlspecialchars(json_encode($user)).'">';
						
						$counter=0;
						for($i=0;$i<count($data);$i++){

							if($i%2==0){
								echo '<div class="row">';
							}
							$option="";
							for($j=0;$j<count($data[$i]["buttons"]);$j++){
								$option.='<option value="'.$data[$i]["buttons"][$j]["id"].'" '.($data[$i]["buttons"][$j]["default"]==1?'selected="selected"':'').' ">'.$data[$i]["buttons"][$j]["text"].'</option>';
							}
							echo '
								<div class="col-md-3">
									<div class="input-group">
										<span class="label label-flat border-info label-striped" style="font-size:16px;padding:4px;">'.$data[$i]["name"].'</span>
										<input type="checkbox" value="0" style="dir:ltr;" data-on-color="danger" data-off-color="primary" data-on-text="No" data-off-text="Yes" data-size="large"
										class="switch alert-status" id="switch_'.$data[$i]["id"].'">
									</div>
								</div>
								<div class="col-md-3">
									<div class="input-group">
										<span class="input-group-addon"><i class="'.$data[$i]["icon"].'"></i></span>
										<div class="multi-select-full">
											<select class="multiselect" multiple="multiple" tabindex="2" id="select_'.$data[$i]["id"].'">
												'.$option.'
											</select>
										</div>
									</div>
								</div>
							';
							if( $i%2 != 0 || count($data)-1==$i){
								echo '</div><hr/>';
							}
						}
					?>	
				</div>
				<br/>
				<br/>
				<hr/>
				<div class="modal-footer">
					<button type="submit" id="saveChangeRoleButton" class="btn btn-labeled btn-labeled-left heading-btn btn-primary"> <span class="multi_lang">Save</span> <b><i class="icon-floppy-disk"></i></b>
					</button>
					<button type="button" class="btn btn-warning" data-dismiss="modal"> <span class="multi_lang">Close</span> 
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="panel panel-flat">
	<table class="table" id="datatableAdminView">
		<thead>
			<tr>
	            <th width="5%">ID</th>
	            <th class="multi_lang">User Name</th>
	            <th class="multi_lang">Full Name</th>
	            <th class="multi_lang">E-Mail</th>
	            <th class="multi_lang">Phone</th>
	            <th class="multi_lang">Profile</th>
	            <th class="multi_lang">Status</th>
	            <th class="text-center multi_lang" width="15%">Actions</th>
	        </tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="controllers/admin.js?random=<?php echo uniqid(); ?>"></script>