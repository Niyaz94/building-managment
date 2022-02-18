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
            $agreementrent = $database->return_data2(array(
                "tablesName"=>array("agreementrent"),
                "columnsName"=>array("ARTID","ARTDate","(ARTRentAftDis) as paidBasic","ARTRentType"," 0 as RPDID"),
                "conditions"=>array(
                    array("columnName"=>"ARTAGRFORID","operation"=>"=","value"=>$_GET["agrid"],"link"=>"and"),
                    array("columnName"=>"ARTPaidType","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"ARTRent","operation"=>">","value"=>0,"link"=>"and"),
                    array("columnName"=>"ARTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            echo "
                <input type='hidden' name='rent_detail' id='rent_detail' value='".json_encode($agreementrent)."'> 
            ";
            $agreementrent_array=array();
            for ($i=0,$iL=count($agreementrent); $i < $iL; $i++) { 
                $type="Rent";
                if($agreementrent[$i]["ARTRentType"]==1){
                    $type="Service";
                }else if($agreementrent[$i]["ARTRentType"]==2){
                    $type="Electric";
                }
                $agreementrent_array[$agreementrent[$i]["ARTID"]]=$agreementrent[$i]["ARTDate"]."( ".$agreementrent[$i]["ARTDate"]." )( ".$type." )";
            }
            //testData($agreementrent);
        }
    }
?>
<div id="addpaymentCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addpaymentForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <input type="hidden" name="PNTAGRFORID_IIZN" id="PNTAGRFORID_IIZN" value="<?php echo $_GET["agrid"];?>">
                <div class="form-group"><?php 
                    echo input1("col-sm-4","number","Total Debt","totalDebt","","icon-cash",0,"readonly");
                    echo input1("col-sm-4","number","Payment","PNTTotalMoney_IIZN","","icon-cash",0,"readonly","",'min=0');
                    echo input2("col-sm-4",array(0=>"Cash",1=>"Bank",2=>"Check"),"Money Category","PNTPaidType_IHZN","required","icon-graph",0,"select2Class","","Please Select Money Category...");
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-4","number","USD Money","PNTTotalUSD_IIZN","required"," icon-cash2",0,"","","min=0");
                    echo input1("col-sm-4","number","IQD Money","PNTTotalIQD_IIZN","required"," icon-cash2",0,"readonly","","min=0");
                    echo input1("col-sm-4","number","New IQD Amount","PNTExtraIQD_IIZN","required"," icon-cash2",0,"","","min=0");
                ?></div>
                <div class="form-group">
                    <?php 
                        echo input1("col-sm-4","text","Payment Date","PNTDate_IDZN","required"," icon-cash3","","","dateStyle");
                        echo input1("col-sm-4","number","One usd Amount","PNTUSDRate_IIZN","required"," icon-coin-dollar",($_SESSION["USD_TO_IQD"]+0));
                   ?>
                    <div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">Payment</span>
							<div class="multi-select-full" >
								<select class="multiselect" multiple="multiple" tabindex="2" id="insert_payment_detail"><?php
                                    foreach($agreementrent_array as $key=>$value) { 
                                        echo '<option value="'.$key.'">'.$value.'</option>';
                                    }
								?></select>
							</div>
						</div>
					</div>
                </div>
                <div class="form-group"><?php 
                    echo input1("col-sm-12","number","Invoice Number","PNTInvoiceId_IIZN","required","icon-stamp",0,"","","min=0");
                ?></div>
                <div class="form-group" style="height: 300px !important;overflow: scroll;">
                    <table class="table table-striped table-bordered table-condensed">
                        <thead class="thead-dark"><tr>
                            <th scope="col">ID</th>
                            <th scope="col">Date</th>
                            <th scope="col">Rent Type</th>
                            <th scope="col">Rent</th>
                        </tr></thead>
                        <tbody id="paymentRentTable">
                        </tbody>
                    </table>
                </div>
                <div class="form-group"><?php
                    echo input3("col-sm-12","","PNTNote_IAZN","editors");
                ?></div>
            </div> 

            <div class="text-right"><?php
                echo button2("savepaymentFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelpaymentFormCollapse","#addpaymentCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editpaymentModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editpaymentForm" method="post" id="editpaymentForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Payment</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="PNTID_UIZP" id="PNTID_UIZP" value="">
                    <input type="hidden" name="oldTotal" id="oldTotal" value="">
                    <input type="hidden" name="oldPNTPaidType" id="oldPNTPaidType" value="">
                    <input type="hidden" name="PNTAGRFORID_UIRN" id="PNTAGRFORID_UIRN" value="<?php echo $_GET["agrid"];?>">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-4","number","Total Debt","totalDebtU","","icon-cash",0,"readonly");
                            echo input1("col-sm-4","number","Payment","PNTTotalMoney_UIRN","","icon-cash",0,"","",'min=0');
                            echo input2("col-sm-4",array(0=>"Cash",1=>"Bank",2=>"Check"),"Money Category","PNTPaidType_UHRN","required","icon-graph",0,"select2Class","","Please Select Money Category...");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("col-sm-4","number","USD Money","PNTTotalUSD_UIRN","required"," icon-cash2",0,"","","min=0");
                            echo input1("col-sm-4","number","IQD Money","PNTTotalIQD_UIRN","required"," icon-cash2",0,"readonly","","min=0");
                            echo input1("col-sm-4","number","New IQD Amount","PNTExtraIQD_UIRN","required"," icon-cash2",0,"","","min=0");
                        ?></div>
                        <div class="form-group">
                            <?php
                                echo input1("col-sm-4","text","Payment Date","PNTDate_UDRN","required"," icon-cash3","","","dateStyle");
                                echo input1("col-sm-4","number","One usd Amount","PNTUSDRate_UIRN","required"," icon-coin-dollar",($_SESSION["USD_TO_IQD"]+0));
                            ?>
                            <div class="col-md-4">
					        	<div class="input-group">
					        		<span class="input-group-addon">Payment</span>
					        		<div class="multi-select-full" >
					        			<select class="multiselect" multiple="multiple" tabindex="2" id="edit_payment_detail"><?php
                                            foreach($agreementrent_array as $key=>$value) { 
                                                echo '<option value="'.$key.'">'.$value.'</option>';
                                            }
					        			?></select>
					        		</div>
					        	</div>
					        </div>
                        </div>
                        <div class="form-group"><?php 
                            echo input1("col-sm-12","number","Invoice Number","PNTInvoiceId_UIRN","required","icon-stamp",0,"","","min=0");
                        ?></div>
                        <div class="form-group" style="height: 300px !important;overflow: scroll;">
                            <table class="table table-striped table-bordered table-condensed">
                                <thead class="thead-dark"><tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Rent Type</th>
                                    <th scope="col">Rent</th>
                                </tr></thead>
                                <tbody id="paymentRentTableEdit">
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","","PNTNote_UARN","editors");
                        ?></div>
                    </div> 

                    
                </div>
                <div class="modal-footer"><?php
				    echo button2("savepaymentFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary checkButton","","");
				    echo button2("cancelpaymentFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatablepaymentView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Total Payment</th>
                <th class="multi_lang">Pay By USD</th>
                <th class="multi_lang">Pay By IQD</th>
                <th class="multi_lang">USD Rate</th>
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
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/payment.js?random=<?php echo uniqid(); ?>"></script>