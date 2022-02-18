<?php 
	$fileName=__FILE__;
	include_once "header.php";
?>
<div id="addshopCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
	<div class="panel-body">
    	<input type="hidden" id="PageName" name="PageName" value="<?php echo __FILE__;?>">
		<form class="form-horizontal" method="post" id="add_form" enctype="multipart/form-data">
			<div class="col-sm-12">
				<div class="form-group"><?php 
					echo input1("col-sm-6","text","Shop Number","SOPNumber_ISZN","required","icon-seven-segment-3");
					echo input2("col-sm-6",array(
						0=>"Base Floor",
						1=>"Ground Floor",
						2=>"First Floor",
						3=>"Second Floor",
						4=>"Third Floor",
						5=>"Forth Floor",
						6=>"Fifth Floor",
						7=>"Sixth Floor",
						8=>"Seventh Floor",
						9=>"Eighth Floor",
						10=>"Ninth Floor"
					),"Shop Category","SOPFloor_IHZN","required","icon-sort-numberic-desc",0,"select2Class","","Please Select Shop Floor...");
				?></div>
				<div class="form-group"><?php 
					echo input1("col-sm-4","number","Shop Area","SOPArea_IIZN","required"," icon-rulers");
					echo input2("col-sm-4",array(""=>"",0=>"shop",1=>"stand",2=>"office"),"Shop Category","SOPCategory_IHZN","required","icon-graph",0,"select2Class","","Please Select Shop Type...");
					echo input1("col-sm-4","number","Electric Money","SOPElectricRate_IJZN","required"," icon-cash3","","","",'min=0 step="any"');
				?></div>
				<div class="form-group"><?php
					echo input3("col-sm-12","","SOPNote_IAZN","editors");
				?></div>
			</div>	
			<div class="text-right">
				<?php
					echo button2("submit_add_btn","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
					echo button3("add_form_cancel","#addshopCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
				?>
			</div>
		</form> 
	</div>
</div>
<br>
<div id="editShopModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editShopForm" method="post" id="editShopForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Change Shop Information</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="SOPID_UIZP" id="SOPID_UIZP" value="">
					<div class="col-sm-12">
						<div class="form-group"><?php 
							echo input1("col-sm-6","text","Shop Number","SOPNumber_USRN","required","icon-seven-segment-3");
							echo input2("col-sm-6",array(
        						0=>"Base Floor",
        						1=>"Ground Floor",
        						2=>"First Floor",
        						3=>"Second Floor",
        						4=>"Third Floor",
        						5=>"Forth Floor",
        						6=>"Fifth Floor",
        						7=>"Sixth Floor",
        						8=>"Seventh Floor",
        						9=>"Eighth Floor",
        						10=>"Ninth Floor"
        					),"Shop Category","SOPFloor_UHRN","required","icon-sort-numberic-desc",0,"select2Class","","Please Select Shop Floor...");
						?></div>
						<div class="form-group"><?php 
							echo input1("col-sm-4","number","Shop Area","SOPArea_UIR","required"," icon-rulers");
							echo input2("col-sm-4",array(""=>"",0=>"shop",1=>"stand",2=>"office"),"Shop Category","SOPCategory_UHRN","required","icon-graph",0,"select2Class","Please Select Shop Type...");
							echo input1("col-sm-4","number","Electric Money","SOPElectricRate_UJRN","required"," icon-cash3","","","",'step="any"');
						?></div>
						<div class="form-group"><?php
							echo input3("col-sm-12","","SOPNote_UARN","editors");
						?></div>
					</div>	
                    
                </div>
                <div class="modal-footer">
					<?php
						echo button2("editShopFormButton","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
						echo button2("showEditCancel","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
					?>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
	<table class="table" id="datatableShopView">
		<thead>
			<tr>
	            <th width="5%">ID</th>
	            <th class="multi_lang">Rent Number</th>
	            <th class="multi_lang">Area</th>
	            <th class="multi_lang">Floor Number</th>
	            <th class="multi_lang">Rent Type</th>
	            <th class="multi_lang">Rent Stiuation</th>
	            <th class="text-center multi_lang" width="15%">Actions</th>
	        </tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
			<tr>
                <td style="border-bottom:2px;"></td>
	            <td></td>
	            <td></td>
	            <td></td>
	            <td></td>
	            <td></td>
	            <td></td>
	        </tr>
		</tfoot>
	</table>
</div>
<script type="text/javascript" src="controllers/shop.js?random=<?php echo uniqid(); ?>"></script>
                        