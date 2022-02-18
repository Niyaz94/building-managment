
<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div id="addemployeeCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="add_form" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"> 
                    <?php
                        echo file2("col-sm-3","EMPPhoto_IBZN","_general/image/employee/defualt.jpg");
                    ?>
                    <div class="col-sm-9">
                        <div class="form-group"><?php 
                            echo input1("","text","Full Name","EMPFullName_ISZ","required","icon-pen");
                        ?></div>                        
                        <div class="form-group"><?php 
                            echo input1("","text","Job Title","EMPJobTitle_ISZ","required"," icon-briefcase");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input2("",array(""=>"",0=>"Male",1=>"Female"),"Geneder","EMPGender_ICZ","","icon-man-woman",0,"select2Class","","Please Select Gender...");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("","text","Address","EMPAddress_ISZ","required"," icon-location3");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("","text","Phone Number","EMPPhone_IPZ","required","icon-mobile");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("","number","Basic Salary","EMPSalary_IIZ","required","icon-cash2");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("","text","Employement Date","EMPDateEmployment_IDZ","required","icon-calendar3","","","dateStyle");
                        ?></div>
                    </div>
                </div>
				<div class="form-group"><?php
					echo input3("col-sm-12","","EMPNote_IAZ","editors");
				?></div>
            </div>
            <div class="text-right">
                <?php
					echo button2("submit_add_btn","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
					echo button3("add_form_cancel","#addemployeeCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
				?>
            </div>
        </form> 
    </div>
</div>
<div class="panel panel-flat">
	<table class="table" id="datatableEmployeeView">
		<thead>
			<tr>
	            <th width="5%">ID</th>
                <th class="multi_lang">Photo</th>
	            <th class="multi_lang">Full Name</th>
	            <th class="multi_lang">Job Title</th>
	            <th class="multi_lang">Phone</th>
	            <th class="multi_lang">Gender</th>
	            <th class="multi_lang">Address</th>
	            <th class="multi_lang">Start Work</th>
                <th class="multi_lang">Basic Salary</th>
	            <th class="multi_lang">CM Salary</th>
	            <th class="text-center multi_lang" width="10%">Actions</th>
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
	            <td></td>
	            <td></td>
                <td></td>
	            <td></td>
	        </tr>
		</tfoot>
	</table>
</div>
<div id="editEmployeeSalaryModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editEmployeeSalaryForm" method="post" id="editEmployeeSalaryForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Change Employee Salary</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="PAYEMPFORID_BIZ" id="PAYEMPFORID_BIZ" value="">
                    <input type="hidden" name="PAYID_BIZP" id="PAYID_BIZP" value="-1">
                    
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","number","Basic Salary","PAYBasicSalary_BIR","required","icon-cash2","","readonly");
                            echo input1("col-sm-6","number","Current Salary","PAYMonthSalary_BIR","required","icon-cash2");
                        ?></div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","Note","PAYNote_BAR","editors");
                        ?></div>
                    </div>    
                </div>
                <div class="modal-footer"><?php
						echo button2("editEmployeeSalaryFormButton","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
						echo button2("cancelEmployeeSalaryFormButton","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
				?></div>
                
            </div>
        </form>
    </div>
</div>
<div id="editEmployeeModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editEmployeeForm" method="post" id="editEmployeeForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Change Employee Information</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="EMPID_UIZP" id="EMPID_UIZP" value="">
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <?php
                                echo file2("col-sm-3","EMPPhoto_UBZN","_general/image/employee/defualt.jpg");
                            ?>
                            <div class="col-sm-9">
                                <div class="form-group"><?php 
                                    echo input1("","text","Full Name","EMPFullName_USRN","required","icon-pen");
                                ?></div>                        
                                <div class="form-group"><?php 
                                    echo input1("","text","Job Title","EMPJobTitle_USRN","required"," icon-briefcase");
                                ?></div>
                                <div class="form-group"><?php 
                                    echo input2("",array(""=>"",0=>"Male",1=>"Female"),"Geneder","EMPGender_UHRN","","icon-man-woman",0,"select2Class","","Please Select Gender...");
                                ?></div>
                                <div class="form-group"><?php 
                                    echo input1("","text","Address","EMPAddress_USRN","required"," icon-location3");
                                ?></div>
                                <div class="form-group"><?php 
                                    echo input1("","text","Phone Number","EMPPhone_UPRN","required","icon-mobile");
                                ?></div>
                                <div class="form-group"><?php 
                                    echo input1("","number","Basic Salary","EMPSalary_UIRN","required","icon-cash2");
                                ?></div>
                                <div class="form-group"><?php 
                                    echo input1("","text","Employement Date","EMPDateEmployment_UDRN","required","icon-calendar3","","","dateStyle");
                                ?></div>
                            </div>
                        </div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","","EMPNote_UARN","editors");
                        ?></div>
                    </div>    
                </div>
                <div class="modal-footer"><?php
						echo button2("editEmployeeFormButton","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
						echo button2("cancelEmployeeFormButton","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
				?></div>
                
            </div>
        </form>
    </div>
</div>
<div id="employeeSalaryHistoryModal" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
				<h4 class="modal-title multi_lang"> All History Salary </h4>
			</div>
			<div class="modal-body" >
				<div style="overflow:auto;">
                    <table id="employeeSalaryHistoryTable" class="table table-striped table-bordered table-hover table-condensed" width="100%">
                        <thead>
                            <tr>
                                <th class="multi_lang" width="20%">Date</th>
                                <th class="multi_lang" width="20%">Basic Salary</th>
                                <th class="multi_lang" width="20%">Month Salary</th>
                                <th class="multi_lang" width="60%">Note</th>
                            </tr>
                        </thead>
                        <tbody>	
                        </tbody>
                    </table>
				</div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<div id="employeeTotalSalaryHistoryModal" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
				<h4 class="modal-title multi_lang"> Totsl Salary For Each Month </h4>
			</div>
			<div class="modal-body">
				<div style="overflow:auto;">
                    <table id="employeTotaleSalaryHistoryTable" class="table table-striped table-bordered table-hover table-condensed" width="100%">
                        <thead>
                            <tr>
                                <th class="multi_lang">Date</th>
                                <th class="multi_lang">Total Salary</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
				</div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="controllers/employee.js?random=<?php echo uniqid(); ?>"></script>
    