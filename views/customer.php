<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div id="addcustomerCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="add_form" enctype="multipart/form-data">
            <div class="form-group"><?php 
                echo input1("col-sm-6","text","Full Name","CUSName_ISZN","required","icon-users2");
                echo input1("col-sm-6","text","Address"  ,"CUSAddress_ISZN",""," icon-location3");
			?></div>
            <div class="form-group"><?php 
                echo input1("col-sm-6","text","Phone","CUSPhone_IPZN","required","icon-phone-plus");
                echo input1("col-sm-6","E-Mail","Email","CUSEmail_IEZN","","icon-envelop5");
			?></div>
            <div class="form-group"><?php
                echo file1("col-lg-6","CUSPIdentifyCard_IBZN","Identify Card");
                echo file1("col-lg-6","CUSAddressCard_IBZN","Address Card");
            ?></div>
            <div class="form-group"><?php
                echo file1("col-lg-6","CUSPassport_IBZN","Passport");
                echo file1("col-lg-6","CUSRemainCard_IBZN","Remain Card");
            ?></div>
            <div class="form-group"><?php
				echo input3("col-sm-12","","CUSNote_IAZN","editors");
			?></div>
            <div class="text-right">
                <?php
                    echo button2("submit_add_btn","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                    echo button3("add_form_cancel","#addcustomerCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
                ?>
            </div>
    </form>
</div>
</div>
<div id="editCustomerModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal" name="editCustomerForm" method="post" id="editCustomerForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Change Customer Information</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="CUSID_UIZP" id="CUSID_UIZP" value="">
                    <div class="form-group"><?php 
                        echo input1("col-sm-6","text","Full Name","CUSName_USRN","required","icon-users2");
                        echo input1("col-sm-6","text","Address"  ,"CUSAddress_USRN",""," icon-location3");
                    ?></div>
                    <div class="form-group"><?php 
                        echo input1("col-sm-6","text","Phone","CUSPhone_UPRN","required","icon-phone-plus");
                        echo input1("col-sm-6","E-Mail","Email","CUSEmail_UERN","","icon-envelop5");
                    ?></div>
                    <div class="form-group"><?php
                        echo file1("col-lg-6","CUSPIdentifyCard_UBZN","Identify Card");
                        echo file1("col-lg-6","CUSAddressCard_UBZN","Address Card");
                    ?></div>
                    <div class="form-group"><?php
                        echo file1("col-lg-6","CUSPassport_UBZN","Passport");
                        echo file1("col-lg-6","CUSRemainCard_UBZN","Remain Card");
                    ?></div>
                    <div class="form-group"><?php
                        echo input3("col-sm-12","","CUSNote_UARN","editors");
                    ?></div>
                </div>
                <div class="modal-footer">
                    <?php
						    echo button2("editCustomerFormButton","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
						    echo button2("cancelCustomerFormButton","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
				        ?>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatableCustomerView">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th class="multi_lang">Full Name</th>
                <th class="multi_lang">Address</th>
                <th class="multi_lang">Mobile Pone</th>
                <th class="multi_lang">Email Address</th>
                <th class="text-center multi_lang" width="15%">Actions</th>
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
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/customer.js?random=<?php echo uniqid(); ?>"></script>