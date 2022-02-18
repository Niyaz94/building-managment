
<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div id="addexpensesgroupCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addexpensesgroupForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-12","text","Type Name","EXGName_IPZN",""," icon-bubble7");
                ?></div>
                <div class="form-group"><?php
					echo input3("col-sm-12","","EXGNote_IAZN","editors");
				?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("saveexpensesgroupFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelexpensesgroupFormCollapse","#addexpensesgroupCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editexpensesgroupModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editexpensesgroupForm" method="post" id="editexpensesgroupForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Capital Type</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="EXGID_UIZP" id="EXGID_UIZP" value="">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-12","text","Type Name","EXGName_UPRN",""," icon-bubble7");
                        ?></div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","","EXGNote_UARN","editors");
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
<div class="panel panel-flat">
    <table class="table" id="datatableexpensesgroupView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Name</th>
                <th class="multi_lang" width="60%">Note</th>
                <th class="multi_lang">Actions</th>

            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <td>ID</td>
                <td>Name</td>
                <td>Note</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/expensesgroup.js?random=<?php echo uniqid(); ?>"></script>
<script type="text/javascript" src="controllers/expensesgeneral.js?random=<?php echo uniqid(); ?>"></script>    