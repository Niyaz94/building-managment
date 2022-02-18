
<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div id="addagreementCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addagreementForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input2("col-sm-4",array(),"Customer Name","AGRCUSFORID_IHZN","required","icon-library2",0,"","","Please Select Shop Number...");
                    echo input2("col-sm-4",array(),"Shop Number","AGRSOPFORID_IHZN","required","icon-reading",0,"","","Please Select Customer...");
                    echo input1("col-sm-4","number","Shop Order","AGROrderRow_IIZN","","icon-users2",0,"","","min=0 max=10000");
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-4","text","Shop Title","AGRShopTitle_ISZN","required","icon-bubble7");
                    echo input1("col-sm-4","text","Contact Name","AGRContactName_ISZN","required","icon-bubble7");
                    echo input1("col-sm-4","text","Work Type","AGRWorkype_ISZN","required","icon-briefcase");
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-6","number","Area Rental","AGRShopRental_IIZN","required","icon-users2",0,"","","min=1");
                    echo input1("col-sm-6","number","Service Rental","AGRServiceRental_IIZN","required"," icon-location3",0,"","","min=0");
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-6","text","Agreement Start Date","AGRDateStart_IDZN","required"," icon-calendar","","","dateStyle");
                    echo input1("col-sm-6","text","Payment Start Date","AGRPaymentStart_IKZN","required"," icon-calendar","","","dateMonthandYear");
                ?></div>
                <div class="form-group"><?php 
                    echo input2("col-sm-6",array(""=>"",0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8),"Agreement Year Duration","EMPYearI","","icon-pencil7",0,"select2Class","","Please Select Year of Agreement...");
                    echo input2("col-sm-6",array(""=>"",0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11),"Agreement Month Duration","EMPMonthI","","icon-pencil7",0,"select2Class","","Please Select Month Of Agreement...");
                ?></div>
                <div class="form-group"><?php 
                    echo input2("col-sm-6",array(0=>"Per Watch",1=>"Per Month",),"Electric Payment Type","AGRWatchPaymentType_IHZN","","icon-pencil7",0,"select2Class","","Please Select Year of Agreement...");
                    echo input1("col-sm-6","number","Electric Rental","AGRElectricRental_IIZN","","icon-pencil7",0,"readonly","","min=1");
                ?></div>
                <div class="form-group"><?php
                    echo file1("col-lg-6","AGRCompanyCertificate_IBZN","Company Certificate");
                    echo input3("col-sm-6","","AGRNote_IAZN","editors");
                ?></div>
            </div>
            <div class="text-right"><?php
                echo button2("submit_add_btn","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("add_form_cancel","#addagreementCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editagreementModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editagreementForm" method="post" id="editagreementForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Update Agreement</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="AGRID_UIZP" id="AGRID_UIZP" value="">
                    <input type="hidden" name="AGRDuration_USRN" id="AGRDuration_USRN" value="">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input2("col-sm-4",array(),"Customer Name","AGRCUSFORID_UGRN","required","icon-reading",0,"", '{"id":"AGRCUSFORID","text":"CUSName"}');
                            echo input2("col-sm-4",array(),"Shop Number","AGRSOPFORID_UGRN","required","",0,"icon-library2",'{"id":"AGRSOPFORID","text":"SOPNumber"}');
                            echo input1("col-sm-4","number","Shop Order","AGROrderRow_UIRN","","icon-users2",0,"","","min=0 max=10000");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("col-sm-4","text","Shop Title","AGRShopTitle_USRN","required","icon-bubble7");
                            echo input1("col-sm-4","text","Contact Name","AGRContactName_USRN","","icon-bubble7");
                            echo input1("col-sm-4","text","Work Type","AGRWorkype_USRN","required","icon-briefcase");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","number","Area Rental","AGRShopRental_UIRN","required","icon-users2",0,"","","min=1");
                            echo input1("col-sm-6","number","Service Rental","AGRServiceRental_UIRN","required"," icon-location3",0,"","","min=0");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","text","Agreement Start Date","AGRDateStart_UDRN","required"," icon-cash3","","","dateStyle");
                            echo input1("col-sm-6","text","Payment Start Date","AGRPaymentStart_UKRN","required"," icon-calendar","","","dateMonthandYear");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input2("col-sm-6",array(""=>"",0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8),"Agreement Year Duration","EMPYearU","","icon-pencil7",0,"select2Class","","Please Select Year of Agreement...");
                            echo input2("col-sm-6",array(""=>"",0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11),"Agreement Month Duration","EMPMonthU","","icon-pencil7",0,"select2Class","","Please Select Month of Agreement...");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input2("col-sm-6",array(0=>"Per Watch",1=>"Per Month",),"Electric Payment Type","AGRWatchPaymentType_UHRN","","icon-pencil7",0,"select2Class","","Please Select Year of Agreement...");
                            echo input1("col-sm-6","number","Electric Rental","AGRElectricRental_UIRN","","icon-pencil7",0,"readonly","","min=1");
                        ?></div>
                        <div class="form-group"><?php
                            echo file1("col-lg-6","AGRCompanyCertificate_UBZN","Company Certificate");
                            echo input3("col-sm-6","","AGRNote_UARN","editors");
                        ?></div>
                    </div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("editagreementFormButton","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelagreementFormButton","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="future_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal"  name="future_form" method="post" id="future_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Future Agreement</h4>
                </div>
                <div class="modal-body">	
                    <div class="col-sm-12">
                        <input type="hidden" name="future_agrid" id="future_agrid" value="">
                        <div class="form-group"><?php 
                            echo input2("col-sm-12",array(0=>"Rent / Service",1=>"Electric",),"Invoice Type","future_type","","icon-pencil7",0,"select2Class","","Please Select Year of Agreement...");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("col-sm-12","text","Agreement Date","future_date","required"," icon-calendar",date("Y-".(date("m")+1)),"","dateMonthandYear");
                        ?></div>
                    </div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("future_send","button","Show","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("future_cancel","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="renewagreementModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="renewagreementForm" method="post" id="renewagreementForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Renew Agreement</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="AGRID_RIZP" id="AGRID_RIZP" value="">
                    <input type="hidden" name="AGRDuration_USR" id="AGRDuration_USR" value="">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","number","Area Rental","AGRShopRental_UIR","required","icon-users2");
                            echo input1("col-sm-6","number","Service Rental","AGRServiceRental_UIR","required"," icon-location3");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input2("col-sm-6",array(""=>"",0=>0,1=>1,2=>2,3=>3,4=>4,5=>5),"Agreement Year Duration","EMPYearN","","icon-pencil7",0,"select2Class","","Please Select Year of Agreement...");
                            echo input2("col-sm-6",array(""=>"",0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11),"Agreement Month Duration","EMPMonthN","","icon-pencil7",0,"select2Class","","Please Select Month of Agreement...");
                        ?></div>
                    </div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("renewagreementFormButton","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("renewCagreementFormButton","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
            <table class="table" id="datatableagreementView">
                <thead>
                    <tr>
                        <th class="multi_lang">ID</th>
                        <th class="multi_lang">Customer Name</th>
                        <th class="multi_lang">Shop Number</th>
                        <th class="multi_lang">Work Type</th>
                        <th class="multi_lang">Shop Title</th>
                        <th class="multi_lang">Start of Rental Date</th>
                        <th class="multi_lang">End of Rental Date</th>
                        <th class="multi_lang">Area Rent</th>
                        <th class="multi_lang">Service Rent</th>
                        <th class="multi_lang" width="15%">Action</th>
                        <th class="multi_lang"></th>
                        <th class="multi_lang"></th>
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
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
<script type="text/javascript" src="controllers/agreement.js?random=<?php echo uniqid(); ?>"></script>