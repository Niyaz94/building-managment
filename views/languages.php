<?php 
	$fileName=__FILE__;
	include_once "header.php";
	$_returnDataForLanguage=new _returnDataFromFile("language");
	$languages_array=$_returnDataForLanguage->returnFullData();
?>
<div id="addlanguagesCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
	<div class="panel-body">
		<form class="form-horizontal" method="post" id="add_form" enctype="multipart/form-data">
			<div class="col-sm-12">
				<input type="hidden" id="type" name="type" value="create">
				<input type="hidden" id="LANName_ISZ" name="LANName_ISZ" value="">
				<input type="hidden" id="LANLocal_ISZ" name="LANLocal_ISZ" value="">
				<div class="form-group">
					<div class="col-sm-4">
						<div class="input-group ">
							<span class="input-group-addon">Language Name</i></span>
							<select class=" select2Class"  data-placeholder="Please Select Language Name..." name="LANSymbol_ISZ" id="LANSymbol_ISZ" data-width="100%" required="required">
									<?php
										foreach ($languages_array as $key => $value) {
										echo '<option 
													value="'.$key.'" 
													data-lang-name="'.$value["name"].'" 
													data-lang-native-name="'.$value["nativeName"].'"
												>'.
													$value["nativeName"]
												.'</option>';
											}
									?>
							</select>
							<span class="input-group-addon"><i class="icon-keyboard"></i></span>
						</div>
					</div>
					<?php 
						echo input2("col-sm-4",array(""=>"","ltr"=>"Left to Right","rtl"=>"Right to Left"),"Language Direction","LANDir_ISZ","required"," icon-brush",0,"select2Class","","Please Select Language Dirction...");
						echo input2("col-sm-4",array(""=>"","0"=>"No","1"=>"Yes"),"Defualt Language","LANISDefualt_IBZ","required"," icon-brush",0,"select2Class","","Please Select Default Language...");
					?>	
				</div>
			</div>
			<div class="text-right">
				<button type="submit" id="submit_add_btn" class="btn btn-primary btn-labeled btn-labeled-left">
					<span class="multi_lang">Save</span> <b><i class="icon-floppy-disk"></i></b>
				</button>
				<a href="#addlanguagesCollapse" id="add_form_cancel" class="btn btn-labeled btn-labeled-left bg-danger heading-btn" data-toggle="collapse">
					<span class="multi_lang">Close</span>  <b><i class="icon-cross"></i></b>
				</a>
			</div>
		</form>
	</div>
</div>
<div id="edit_form_cont" class="modal fade" style="display: none;">
		<div class="modal-dialog modal-full">
		<form class="form-horizontal"  name="edit_form" method="post" id="edit_form" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
					<h4 class="modal-title multi_lang"> Edit </h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="LANID_UIZP" id="LANID_UIZP" value="">
					<input type="hidden" id="type" name="type" value="update">
					<input type="hidden" id="LANName_USRN" name="LANName_USRN" value="">
					<input type="hidden" id="LANLocal_USRN" name="LANLocal_USRN" value="">
					<div class="col-sm-12">
						<div class="form-group">
							<div class="col-sm-4">
								<div class="input-group ">
									<span class="input-group-addon">Language Name</i></span>
									<select class=" select2Class"  data-placeholder="Please Select Language Name..." name="LANSymbol_UHRN" id="LANSymbol_UHRN" data-width="100%" required="required">
											<?php
												foreach ($languages_array as $key => $value) {
												echo '<option 
															value="'.$key.'" 
															data-lang-name="'.$value["name"].'" 
															data-lang-native-name="'.$value["nativeName"].'"
														>'.
															$value["nativeName"]
														.'</option>';
													}
											?>
									</select>
									<span class="input-group-addon"><i class="icon-keyboard"></i></span>
								</div>
							</div>
							<?php 
								echo input2("col-sm-4",array(""=>"Language Direction","ltr"=>"Left to Right","rtl"=>"Right to Left"),"Language Direction","LANDir_UHRN","required"," icon-brush",0,"select2Class","","Please Select Language Dirction...");
								echo input2("col-sm-4",array(""=>"Defualt Language","0"=>"No","1"=>"Yes"),"Defualt Language","LANISDefualt_UHRN","required"," icon-brush",0,"select2Class","","Please Select Default Language...");
							?>											
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="submit_edit_btn" class="btn btn-labeled btn-labeled-left heading-btn btn-primary"> <span class="multi_lang">Save</span> <b><i class="icon-floppy-disk"></i></b>
					</button>
					<button type="button" class="btn btn-warning" data-dismiss="modal"> <span class="multi_lang">Close</span>
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="panel panel-flat">	
	<table class="table datatable-column-search-inputs" id="dataTableLanguageView">
		<thead><tr>
            <th>ID</th>
            <th class="multi_lang">Local Language Name</th>
            <th class="multi_lang">International Name</th>
            <th class="multi_lang">Symbol</th>
            <th class="multi_lang">Direction</th>
            <th class="multi_lang">Type</th>
            <th  class="text-center multi_lang"> Actions </th>
        </tr></thead><tbody></tbody><tfoot><tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr></tfoot>
	</table>
</div>
<script type="text/javascript" src="controllers/languages.js?random=<?php echo uniqid(); ?>"></script>