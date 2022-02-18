<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div id="addadvanceCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addadvanceForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-6","text","Advance For","ADVForPerson_ISRN","required"," icon-person","","","");                        
                    echo input1("col-sm-6","text","Advance Date","ADVDate_IDZN","required"," icon-calendar","","","dateStyle");
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-4","number","Money","ADVMoney_IIZN","required"," icon-cash2",0,"","","min=1");
                    echo input2("col-sm-4",array(0=>"USD",1=>"IQD"),"Money Type","ADVMoneyType_IHZN","required"," icon-list2",0,"select2Class","","Please Select Money Type...");
                    echo input1("col-sm-4","number","One usd Amount","ADVUSDRate_IIZN","required"," icon-coin-dollar",$_SESSION["USD_TO_IQD"]);
                ?></div>
                <div class="form-group"><?php
                    echo input3("col-sm-12","","ADVNote_IAZN","editors");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("saveadvanceFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("canceladvanceFormCollapse","#addadvanceCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editadvanceModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editadvanceForm" method="post" id="editadvanceForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Advance</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="ADVID_UIZP" id="ADVID_UIZP" value="">
                    <div class="form-group"><?php 
                        echo input1("col-sm-6","text","Advance For", "ADVForPerson_USRN","required"," icon-person","","","");                        
                        echo input1("col-sm-6","text","Advance Date","ADVDate_UDRN","required"," icon-calendar","","","dateStyle");
                    ?></div>
                    <div class="form-group"><?php 
                        echo input1("col-sm-4","number","Money","ADVMoney_UIRN","required"," icon-cash2",0,"","","min=1");
                        echo input2("col-sm-4",array(""=>"",0=>"USD",1=>"IQD"),"Money Type","ADVMoneyType_UHRN","required"," icon-list2",0,"select2Class","","Please Select Money Type...");
                        echo input1("col-sm-4","number","One usd Amount","ADVUSDRate_UIRN","required"," icon-coin-dollar",$_SESSION["USD_TO_IQD"]);
                    ?></div>
                    <div class="form-group"><?php
                        echo input3("col-sm-12","","ADVNote_UARN","editors");
                    ?></div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("saveadvanceFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("canceladvanceFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat" style="margin:5px;">
    <table class="table" id="datatableadvanceView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">For</th>
                <th class="multi_lang">Money</th>
                <th class="multi_lang">Date</th>
                <th class="multi_lang">Money Type</th>
                <th class="multi_lang">Return Money</th>
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
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/advance.js?random=<?php echo uniqid(); ?>"></script>
    