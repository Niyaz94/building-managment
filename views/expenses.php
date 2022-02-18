
<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div id="addexpensesCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addexpensesForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input2("col-sm-4",array(),"Expense Name","EXPEXGFORID_IHZN","required","icon-lan2",0,"","","",1);
                    echo input1("col-sm-4","text","Expense For","EXPForPerson_ISRN","required"," icon-person","","","");                        
                    echo input1("col-sm-4","text","Expense Date","EXPDate_IDZN","required"," icon-calendar","","","dateStyle");
                    
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-4","number","Money","EXPMoney_IIZN","required"," icon-cash2");
                    echo input2("col-sm-4",array(""=>"",0=>"USD",1=>"IQD"),"Money Type","EXPMoneyType_IHZN","required"," icon-list2",0,"select2Class","","Please Select Money Type...");
                    echo input1("col-sm-4","number","One usd Amount","EXPUSDRate_IIZN","required"," icon-coin-dollar",$_SESSION["USD_TO_IQD"]);
                ?></div>
                <div class="form-group"><?php
                    echo input3("col-sm-12","","EXPNote_IAZN","editors");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("saveexpensesFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelexpensesFormCollapse","#addexpensesCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editexpensesModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editexpensesForm" method="post" id="editexpensesForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Expense</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="EXPID_UIZP" id="EXPID_UIZP" value="">
                    <div class="form-group"><?php 
                        echo input2("col-sm-4",array(),"Expense Name","EXPEXGFORID_UGRN","required","icon-lan2",0,"", '{"id":"EXPEXGFORID","text":"EXGName"}',"",1);
                        echo input1("col-sm-4","text","Expense For","EXPForPerson_USRN","required"," icon-person","","","");                        
                        echo input1("col-sm-4","text","Expense Date","EXPDate_UDRN","required"," icon-calendar","","","dateStyle");
                    ?></div>
                    <div class="form-group"><?php 
                        echo input1("col-sm-4","number","Money","EXPMoney_UIRN","required"," icon-cash2");
                        echo input2("col-sm-4",array(""=>"",0=>"USD",1=>"IQD"),"Money Type","EXPMoneyType_UHRN","required"," icon-list2",0,"select2Class","","Please Select Money Type...");
                        echo input1("col-sm-4","number","One usd Amount","EXPUSDRate_UIRN","required"," icon-coin-dollar",$_SESSION["USD_TO_IQD"]);
                    ?></div>
                    <div class="form-group"><?php
                        echo input3("col-sm-12","","EXPNote_UARN","editors");
                    ?></div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("saveexpensesFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelexpensesFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="insertexpensesgroupModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="addexpensesgroupForm" method="post" id="addexpensesgroupForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Insert Capital Type</h4>
                </div>
                <div class="modal-body">	
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-12","text","Type Name","EXGName_IPZN",""," icon-bubble7");
                        ?></div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","","EXGNote_IAZN","editors");
                        ?></div>
                    </div>  
                    
                </div>
                <div class="modal-footer"><?php
				    echo button2("saveexpensesgroupFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelexpensesgroupFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat" style="margin:5px;">
    <table class="table" id="datatableexpensesView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">For</th>
                <th class="multi_lang">Expenses Type</th>
                <th class="multi_lang">Money</th>
                <th class="multi_lang">Date</th>
                <th class="multi_lang">Money Type</th>
                <th class="multi_lang">Usd Rate</th>
                <th class="multi_lang">Actions</th>
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
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/expenses.js?random=<?php echo uniqid(); ?>"></script>
<script type="text/javascript" src="controllers/expensesgeneral.js?random=<?php echo uniqid(); ?>"></script>    
    