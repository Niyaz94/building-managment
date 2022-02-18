
<?php 
    $fileName=__FILE__;
    include_once "header.php";
    if(!isset($_GET["watchid"]) || !is_numeric($_GET["watchid"]) || !isset($_GET["shopid"]) || !is_numeric($_GET["shopid"])){
        echo '<script>window.location="starter.php";</script>';
    }else{
        $countWatch = $database->return_data2(array(
            "tablesName"=>array("watch"),
            "columnsName"=>array("*"),
            "conditions"=>array(
                array("columnName"=>"WTCID","operation"=>"=","value"=>$_GET["watchid"],"link"=>"and "),
                array("columnName"=>"WTCDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"row_count"
        ));
        $countShop = $database->return_data2(array(
            "tablesName"=>array("shop"),
            "columnsName"=>array("*"),
            "conditions"=>array(
                array("columnName"=>"SOPID","operation"=>"=","value"=>$_GET["shopid"],"link"=>"and "),
                array("columnName"=>"SOPDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"row_count"
        ));
        if($countWatch!=1 || $countShop!=1){
            echo '<script>window.location="starter.php";</script>';
        }else{
            $AGRID = $database->return_data2(array(
                "tablesName"=>array("agreement"),
                "columnsName"=>array("AGRID"),
                "conditions"=>array(
                    array("columnName"=>"AGRSOPFORID","operation"=>"=","value"=>$_GET["shopid"],"link"=>"and "),
                    array("columnName"=>"AGRType","operation"=>"<>","value"=>2,"link"=>"and"),
                    array("columnName"=>"AGRDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["AGRID"];
            echo '
                <input type="hidden" name="SOPID" id="SOPID" value="'.$_GET["shopid"].'">
                <input type="hidden" name="WTCID" id="WTCID" value="'.$_GET["watchid"].'">
                <input type="hidden" name="AGRID" id="AGRID" value="'.$AGRID.'">
            ';

        }
    }
?>
<div id="addwatchrentCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addwatchrentForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <input type="hidden" name="ARTRentType_IIZN" id="ARTRentType_IIZN" value="2">
                <div class="form-group"><?php 
                    echo input1("col-sm-4","number","Old Read","WNTOldRead_IIZN","required"," icon-stairs-up","","","","min=0");
                    echo input1("col-sm-4","number","New Read","WNTNewRead_IIZN","required"," icon-stairs-up","","","","min=0");
                    echo input1("col-sm-4","text","Employement Date","ARTDate_IKZN","required","icon-calendar3","","","dateMonthandYear");
                ?></div>
                <div class="form-group"><?php
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("savewatchrentFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelwatchrentFormCollapse","#addwatchrentCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editwatchrentModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editwatchrentForm" method="post" id="editwatchrentForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Watch Rent</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="WNTID_UIZP" id="WNTID_UIZP" value="">
                    <input type="hidden" name="WNTWTCFORID_UIRN" id="WNTWTCFORID_UIRN" value="">
                    <input type="hidden" name="WNTARTFORID_UIRN" id="WNTARTFORID_UIRN" value="">

                    <div class="form-group"><?php 
                        echo input1("col-sm-4","number","Old Read","WNTOldRead_UIRN","required"," icon-stairs-up");
                        echo input1("col-sm-4","number","New Read","WNTNewRead_UIRN","required"," icon-stairs-up");
                        echo input1("col-sm-4","text","Employement Date","ARTDate_UKRN","required","icon-calendar3","","","dateMonthandYear");
                    ?></div>
                    
                </div>
                <div class="modal-footer"><?php
				    echo button2("savewatchrentFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelwatchrentFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatablewatchrentView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Shop ID</th>
                <th class="multi_lang">Watch ID</th>
                <th class="multi_lang">Shop Number</th>
                <th class="multi_lang">Watch Name</th>
                <th class="multi_lang">Money For Unit</th>
                <th class="multi_lang">Old Read</th>
                <th class="multi_lang">New Read</th>
                <th class="multi_lang">Date</th>
                <th class="multi_lang"></th>
                <th class="multi_lang"></th>
                <th class="multi_lang"></th>
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
<script type="text/javascript" src="controllers/watchrent.js?random=<?php echo uniqid(); ?>"></script>
    