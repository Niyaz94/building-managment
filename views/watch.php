
        <?php 
            $fileName=__FILE__;
            include_once "header.php";
        ?>
        <div id="addwatchCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
            <div class="panel-body">
                <form class="form-horizontal" method="post" id="addwatchForm" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input2("col-sm-6",array(),"Shop Number","WTCSOPFORID_IHZN","required","icon-library2");
                            echo input1("col-sm-6","text","Watch Number","WTCName_ISZN","required"," icon-pencil7");
                        ?></div>
                    </div>  
                    <div class="text-right"><?php
                        echo button2("submit_add_btn","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                        echo button3("add_form_cancel","#addwatchCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
                    ?></div>
                </form> 
            </div>
        </div>
        <div id="editwatchModal" class="modal fade" style="display: none;">
            <div class="modal-dialog modal-full">
                <form class="form-horizontal"  name="editwatchForm" method="post" id="editwatchForm" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                            <h4 class="modal-title multi_lang">Edit Electric Watch</h4>
                        </div>
                        <div class="modal-body">	
                            <input type="hidden" name="WTCID_UIZP" id="WTCID_UIZP" value="">
                            <div class="form-group"><?php 
                                echo input2("col-sm-6",array(),"Shop Number","WTCSOPFORID_UGRN","required","icon-library2",0,"",'{"id":"WTCSOPFORID","text":"SOPNumber"}');
                                echo input1("col-sm-6","text","Watch Number","WTCName_USRN","required"," icon-pencil7");
                            ?></div>
                        </div>
                        
                        <div class="modal-footer"><?php
						    echo button2("savewatchFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
						    echo button2("cancelwatchFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
				        ?></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-flat">
            <table class="table" id="datatablewatchView">
                <thead>
                    <tr>
                        <th class="multi_lang">ID</th>
                        <th class="multi_lang">Shop Number</th>
                        <th class="multi_lang">Watch Number</th>
                        <th class="multi_lang" width="15%">Action</th>
                        <th class="multi_lang">ShopID</th>
                        <th class="multi_lang">AGRWATCHTYPE</th>
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
        <script type="text/javascript" src="controllers/watch.js?random=<?php echo uniqid(); ?>"></script>
    