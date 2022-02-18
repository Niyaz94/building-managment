<?php 
    $fileName=__FILE__;
    include_once "header.php";
    if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
        echo '<script>window.location="starter.php";</script>';
    }else{
        $countAdvance = $database->return_data2(array(
            "tablesName"=>array("advance"),
            "columnsName"=>array("*"),
            "conditions"=>array(
                array("columnName"=>"ADVID","operation"=>"=","value"=>$_GET["id"],"link"=>"and "),
                array("columnName"=>"ADVDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"row_count"
        ));
        if($countAdvance!=1){
            echo '<script>window.location="starter.php";</script>';
        }else{
            $needData = $database->return_data("
                select
                    ADVMoney,
                    ADVMoneyType,
                    ADVForPerson,
                    ifnull((
                        SELECT
                            sum(CPTMoney)
                        FROM
                            capital
                        WHERE
                            CPTTransactionType=4 AND
                            CPTDeleted=0 AND
                            CPTFORID=".$_GET["id"]."
                    ),0) as return_total
                from
                    advance
                where
                    ADVID=".$_GET["id"]." and
                    ADVDeleted=0
            ","key");
            echo "
                <input type='hidden' name='ADVID' id='ADVID' value='".$_GET['id']."'>
                <input type='hidden' name='need_data' id='need_data' value='".json_encode($needData)."'>
            ";
        }
    }
?>
<div id="addreturnadvanceCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addreturnadvanceForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-6","number","Money","CPTMoney_IIZN","required"," icon-cash2",0,"","","min=1 max=".($needData["ADVMoney"]-$needData["return_total"]));
                    echo input1("col-sm-6","number","One usd Amount","CPTUSDRate_IIZN","required"," icon-coin-dollar",$_SESSION["USD_TO_IQD"]);
                ?></div>
                <div class="form-group"><?php
                    echo input3("col-sm-12","","CPTNote_IAZN","editors");
                ?></div>
                
            </div>   
            <div class="text-right"><?php
                echo button2("savereturnadvanceFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelreturnadvanceFormCollapse","#addreturnadvanceCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editreturnadvanceModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editreturnadvanceForm" method="post" id="editreturnadvanceForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Return Advance</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="CPTID_UIZP" id="CPTID_UIZP" value="">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","number","Money","CPTMoney_UIRN","required"," icon-cash2",0,"","","min=1 max=".($needData["ADVMoney"]-$needData["return_total"])."");
                            echo input1("col-sm-6","number","One usd Amount","CPTUSDRate_UIRN","required"," icon-coin-dollar",$_SESSION["USD_TO_IQD"]);
                        ?></div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","","CPTNote_UARN","editors");
                        ?></div>
                    </div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("savereturnadvanceFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelreturnadvanceFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatablereturnadvanceView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Return Money</th>
                <th class="multi_lang">USD Rate</th>
                <th class="multi_lang">Note</th>
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
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/returnadvance.js?random=<?php echo uniqid(); ?>"></script>
    