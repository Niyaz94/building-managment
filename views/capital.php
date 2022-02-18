<?php 
    $fileName=__FILE__;
    include_once "header.php";
    $total=totalAllType($database,date("Y-m-d"),1);
?>
<div class="row">
    <div class="col-lg-2">
			<div class="panel bg-success-300">
				<div class="panel-body">
					<h3 class="no-margin" style="text-align:center;">Total Incomes</h3>										
					<h4 class="no-margin" id="TPGU" style="font-size:18px;text-align:center;"><?php echo number_format($total["income_usd"],2)."  ";?> USD</h4>
				</div>
			</div>
	</div>
    <div class="col-lg-2">
			<div class="panel bg-danger-300">
				<div class="panel-body">
					<h3 class="no-margin" style="text-align:center;">Total Expenses</h3>										
					<h4 class="no-margin" id="TPGU" style="font-size:18px;text-align:center;"><?php echo number_format($total["expense_usd"],2)."  ";?> USD</h4>
				</div>
			</div>
	</div>
	<div class="col-lg-2">
			<div class="panel bg-info-600">
				<div class="panel-body">
					<h3 class="no-margin multi_lang" style="text-align:center;">Total In Capital</h3>
					<h4 class="no-margin multi_lang" id="TIGU" style="font-size:18px;text-align:center;"><?php echo number_format($total["income_usd"]-$total["expense_usd"],2)."  ";?> USD</h4>
				</div>
			</div>
	</div>
	
    <div class="col-lg-2">
		<div class="panel bg-success-300">
			<div class="panel-body">
				<h3 class="no-margin" style="text-align:center;">Total Incomes</h3>										
				<h4 class="no-margin" id="TPGU" style="font-size:18px;text-align:center;"><?php echo number_format($total["income_iqd"],2)."  ";?> IQD</h4>
			</div>
		</div>
	</div>
    <div class="col-lg-2">
		<div class="panel bg-danger-300">
			<div class="panel-body">
				<h3 class="no-margin" style="text-align:center;">Total Expenses</h3>										
				<h4 class="no-margin" id="TPGU" style="font-size:18px;text-align:center;"><?php echo number_format($total["expense_iqd"],2)."  ";?> IQD</h4>
			</div>
		</div>
	</div>
    <div class="col-lg-2">
		<div class="panel bg-info-600">
			<div class="panel-body">
				<h3 class="no-margin multi_lang" style="text-align:center;">Total In Capital</h3>										
				<h4 class="no-margin multi_lang" id="TPGU" style="font-size:18px;text-align:center;"><?php echo number_format($total["income_iqd"]-$total["expense_iqd"],2)."  ";?> IQD</h4>
			</div>
		</div>
	</div>
</div>	
<div id="addcapitalCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcapitalForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-6","number","Money","CPTMoney_IIZN","required"," icon-cash2",0,"","","min=1");
                    echo input1("col-sm-6","number","One usd Amount","CPTUSDRate_IIZN","required"," icon-coin-dollar",$_SESSION["USD_TO_IQD"]);
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-6","text","Payment Date","CPTDate_IDZN","required"," icon-cash3","","","dateStyle");
                    echo input2("col-sm-6",array(""=>"",0=>"USD",1=>"IQD"),"Money Type","CPTMoneyType_IHZN","required"," icon-list2",0,"select2Class","","Please Select ...");
                ?></div>
                <div class="form-group"><?php
                    echo input3("col-sm-12","","CPTNote_IAZN","editors");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("savecapitalFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelcapitalFormCollapse","#addcapitalCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editcapitalModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editcapitalForm" method="post" id="editcapitalForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Capital</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="CPTID_UIZP" id="CPTID_UIZP" value="">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","number","Money","CPTMoney_UIRN","required"," icon-cash2",0,"","","min=1");
                            echo input1("col-sm-6","number","One usd Amount","CPTUSDRate_UIRN","required"," icon-coin-dollar",$_SESSION["USD_TO_IQD"]);
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","text","Payment Date","CPTDate_UDRN","required"," icon-cash3","","","dateStyle");
                            echo input2("col-sm-6",array(""=>"",0=>"USD",1=>"IQD"),"Money Type","CPTMoneyType_UHRN","required"," icon-list2",0,"select2Class","","Please Select Money Type ...");
                        ?></div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","","CPTNote_UARN","editors");
                        ?></div>
                    </div>           
                </div>
                <div class="modal-footer"><?php
				    echo button2("savecapitalFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelcapitalFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatablecapitalView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Type</th>
                <th class="multi_lang">Money</th>
                <th class="multi_lang">Money Type</th>
                <th class="multi_lang">USD Rate</th>
                <th class="multi_lang">Paid Type</th>
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
<script type="text/javascript" src="controllers/capital.js?random=<?php echo uniqid(); ?>"></script>