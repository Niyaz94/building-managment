<?php 
    $fileName=__FILE__;
    include_once "header.php";
    if(!isset($_GET["agrid"]) || !is_numeric($_GET["agrid"])){
        echo '<script>window.location="starter.php";</script>';
    }else{
        $countAgreement = $database->return_data2(array(
            "tablesName"=>array("agreement"),
            "columnsName"=>array("*"),
            "conditions"=>array(
                array("columnName"=>"AGRID","operation"=>"=","value"=>$_GET["agrid"],"link"=>"and "),
                array("columnName"=>"AGRDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"row_count"
        ));
        if($countAgreement!=1){
            echo '<script>window.location="starter.php";</script>';
        }else{
            echo '
                <input type="hidden" name="AGRID" id="AGRID" value="'.$_GET["agrid"].'">
                <input type="hidden" name="AGRTitle" id="AGRTitle" value="'.$_GET["title"].'">
            ';

        }
    }
?>
<div id="editagreementrentModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editagreementrentForm" method="post" id="editagreementrentForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Rent Payment</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="ARTID_UIZP" id="ARTID_UIZP" value="">
                    <div class="form-group"><?php 
                        echo input1("col-sm-6","number","Rent","ARTRent_UIRA","required"," icon-cash3","","readonly","");
                        echo input1("col-sm-6","number","New Rent","ARTRentD_UIRN","required"," icon-scissors","","","discoutClass",'min="0"');
                    ?></div>
                    <div class="form-group"><?php 
                        echo input1("col-sm-12","text","Agreement Start Date","ARTDate_UDRA","required"," icon-calendar","","readonly","dateStyle");
                    ?></div>
                    <div class="form-group"><?php
                        echo input3("col-sm-12","","ARTNote_UARN","editors");
                    ?></div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("saveagreementrentFormModal","submit","Save","icon-floppy-disk","ShowOrNot btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelagreementrentFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="futureRentModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="futureRentForm" method="post" id="futureRentForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Add Rent</h4>
                </div>
                <div class="modal-body">	
                    <div class="form-group"><?php 
                        echo input2("col-sm-4",array(2019=>2019,2020=>2020,2021=>2021,2022=>2022),"Year","future_year","","icon-pencil7",0,"select2Class","","Please Select Year...");
                    ?>
                    <div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">Month</span>
							<div class="multi-select-full" >
								<select class="multiselect" multiple="multiple" tabindex="2" id="future_month">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
								</select>
							</div>
						</div>
					</div>
                    <div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">Type</span>
							<div class="multi-select-full" >
								<select class="multiselect" multiple="multiple" tabindex="2" id="future_type">
									<option value="0" selected="selected">Rent</option>
									<option value="1" selected="selected">Service</option>
									<option value="2" selected="selected">Electric</option>
								</select>
							</div>
						</div>
					</div>
                    
                    </div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("saveFutureRentModal","submit","Save","icon-floppy-disk","ShowOrNot btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelFutureRentModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatableagreementrentView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Date</th>
                <th class="multi_lang">Rent Type</th>
                <th class="multi_lang">Rent</th>
                <th class="multi_lang">Rent After Discount</th>
                <th class="multi_lang">Paid State</th>
                <th class="multi_lang">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
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
<script type="text/javascript" src="controllers/agreementrent.js?random=<?php echo uniqid(); ?>"></script>