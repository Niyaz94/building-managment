<?php
	class _createNewFilesToSystem{
        public static function createNewFile($filePermissionData){
            for($i=0;$i<count($filePermissionData);$i++){
                extract($filePermissionData[$i]);
                if($create=="yes" && $page_type=="normal"){
if(!file_exists("views/".$page.".php")){
    $fp = fopen("views/".$page.".php", 'a+');
    fwrite($fp,'
        <?php 
            $fileName=__FILE__;
            include_once "header.php";
        ?>
        <div id="add'.$page.'Collapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
            <div class="panel-body">
                <form class="form-horizontal" method="post" id="add'.$page.'Form" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","text","Phone","STFPhoneNumber_IPZ","","icon-phone-plus");
                            echo input2("col-sm-6",array(0=>"permision",1=>"admin"),"Profile Type","STFProfileType_ICZ","","icon-unlocked2");
                        ?></div>
                    </div>  
                    <div class="text-right"><?php
                        echo button2("save'.$page.'FormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                        echo button3("cancel'.$page.'FormCollapse","#add'.$page.'Collapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",\'data-toggle="collapse"\')
                    ?></div>
                </form> 
            </div>
        </div>
        <h4>Hello World</h4>
        <div id="edit'.$page.'Modal" class="modal fade" style="display: none;">
            <div class="modal-dialog modal-full">
                <form class="form-horizontal"  name="edit'.$page.'Form" method="post" id="edit'.$page.'Form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                            <h4 class="modal-title multi_lang">____________</h4>
                        </div>
                        <div class="modal-body">	
                            <input type="hidden" name="'.$symbol.'ID_UIZP" id="'.$symbol.'ID_UIZP" value="">
                            
                        </div>
                        <div class="modal-footer"><?php
						    echo button2("save'.$page.'FormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
						    echo button2("cancel'.$page.'FormModal","button","Close","icon-cross","btn btn-warning",\'data-dismiss="modal"\')
				        ?></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-flat">
            <table class="table" id="datatable'.$page.'View">
                <thead>
                    <tr>
                        <th class="multi_lang"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <script type="text/javascript" src="controllers/'.$page.'.js?random=<?php echo uniqid(); ?>"></script>
    ');
    fclose($fp);
    chmod("views/".$page.".php", 0777);
}
if(!file_exists("models/_".$page.".php")){
    $fp = fopen("models/_".$page.".php", 'a+');
    fwrite($fp,'
        <?php
            include_once "../_general/backend/_header.php";
            if (isset($_POST["type"]) || isset($_GET["type"])){
                if( isset($_GET["type"]) && ($_GET["type"] == "load")){
                    $table = "";
                    $primaryKey = "";
                    $where="";
                    $columns =  array(
                        array( "db" => "", "dt" => 0 ),  
                    );
                    echo json_encode(
                        SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
                    );
                    exit;
                }
                if ($_POST["type"] == "create") {	
                    //testData($_POST,0);
                    $validation=new class_validation($_POST,"'.$symbol.'");
                    $data=$validation->returnLastVersion();
                    extract($data);
                    $res = $database->return_data2(array(
                        "tablesName"=>array("'.$page.'"),
                        "columnsName"=>array("*"),
                        "conditions"=>array(
                            array("columnName"=>"columnName","operation"=>"=","value"=>0,"link"=>"and"),
                        ),
                        "others"=>"",
                        "returnType"=>"row_count"
                    ));
                    if($res>0){
                        echo jsonMessages(false,7);
                        exit;
                    }
                    $res = $database->insert_data2("'.$page.'",$data);
                    if ($res) {	
                        echo jsonMessages(true,2);
                        exit;
                    }else{
                        echo jsonMessages(false,1);
                        exit;
                    }
                }
                if ($_POST["type"] == "update") {
                    //testData($_POST,0);
                    $validation=new class_validation($_POST,"'.$symbol.'");
                    $data=$validation->returnLastVersion();
                    extract($data);
                    $res = $database->return_data2(array(
                        "tablesName"=>array("'.$page.'"),
                        "columnsName"=>array("*"),
                        "conditions"=>array(
                            array("columnName"=>"'.$symbol.'Deleted","operation"=>"=","value"=>0,"link"=>"and"),
                            array("columnName"=>"'.$symbol.'ID","operation"=>"!=","value"=>$'.$symbol.'ID,"link"=>"")
                        ),
                        "others"=>"",
                        "returnType"=>"row_count"
                    ));
                    if($res>0){
                        echo jsonMessages(false,7);
                        exit;
                    }
                    $res = $database->update_data2(array(
                        "tablesName"=>"'.$page.'",
                        "userData"=>$data,
                        "conditions"=>array()
                    ));
                    if ($res) {
                        echo jsonMessages(true,1);
                        exit;
                    }else{
                        echo jsonMessages(false,1);
                        exit;
                    }
                }
            }else{
                header("Location:../");
                exit;
            }
        ?>
    ');
    fclose($fp);
    chmod("models/_".$page.".php", 0777);
}
if(!file_exists("_general/notes/".$page)){
    $fp = fopen("_general/notes/".$page, 'a+');
    fwrite($fp,'
    ');
    fclose($fp);
    chmod("_general/notes/".$page, 0777);
}
if(!file_exists("controllers/".$page.".js")){
    $fp = fopen("controllers/".$page.".js", 'a+');
    fwrite($fp, '
        $(document).ready(function () {
            $("#add'.$page.'Form").on("submit", function (e) {
                e.preventDefault();
                $("#save'.$page.'FormCollapse").attr("disabled", true);       
                var formData = new FormData($(this)[0]);
                formData.append("PageName",$("#PageName").val());
                formData.append("",CKEDITOR.instances[""].getData());
                formData.append("type","create");
                $.ajax({
                    url: "models/_'.$page.'.php",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        res=JSON.parse(res);
                        if(res.is_success == true){
                            $("#add'.$page.'Form")[0].reset();
                            CKEDITOR.instances[""].setData(""); 
                            $("#datatable'.$page.'View").DataTable().ajax.reload(null, false);
                            $("#add'.$page.'Collapse").collapse("hide");
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }else{
                            oneAlert("error","Error!!!",res.data)
                        }
                        $("#save'.$page.'FormCollapse").attr("disabled",false);
                    },
                    fail: function (err){
                        oneAlert("error","Error!!!",res.data)
                        $("#save'.$page.'FormCollapse").attr("disabled",false);
                    },
                    always:function(){
                        console.log("complete");
                    }
                });
            });
            $("#edit'.$page.'Form").on("submit", function (e) {
                e.preventDefault();
                $("#save'.$page.'FormModal").attr("disabled", true);
                var formData = new FormData($(this)[0]);
                formData.append("PageName",$("#PageName").val());
                formData.append("type","update");
                formData.append("",CKEDITOR.instances[""].getData());
                $.ajax({
                    url: "models/_'.$page.'.php",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    dataType: "json",
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if(res.is_success == true){
                            $("#edit'.$page.'Form")[0].reset();
                            CKEDITOR.instances[""].setData(""); 
                            $("#datatable'.$page.'View").DataTable().ajax.reload(null, false);
                            $("#edit'.$page.'Modal").modal("toggle");
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }else{
                          oneAlert("error","Error!!!",res.data);
                        }
                        $("#save'.$page.'FormModal").attr("disabled",false);
                    },
                    fail: function (err){
                        oneAlert("error","Error!!!",res.data)
                        $("#save'.$page.'FormModal").attr("disabled",false);
                    },
                    always:function(){
                        console.log("complete");
                    }
                });
            });
            addingExtenton();
            table = $("#datatable'.$page.'View").DataTable({
                buttons: {
                    buttons: dtButtons()
                },
                lengthMenu: [
                    [10, 25, 50, 100],
                    ["10", "25","50","100"]
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "models/_'.$page.'.php",
                    "data": function (d) {
                        d.type = "load";
                    }
                },
                drawCallback: function () {
                    tooltip1("");
                },
                "columnDefs": [
                    {
                        "targets": 6,
                        "data": null,
                        "render": function (data, type, row) {
                            return returnTablButtons(
                                JSON.parse($("#pageInfo").val()),
                                JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                                {},
                                {
                                    "'.$symbol.'ID":row[0]
                                },
                                "table"
                            );
                        }
                    },{
                        "targets": 4,
                        "render": function (data, type, row) {
                            if(data==0){
                                return `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">Shop</span>`;
                            }else{
                                return data;
                            }
                        }
                    }
                ],
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                },
                "order": [
                    [0, "desc"]
                ],
                "displayLength": 25,
                initComplete: function () {
                    $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
                }
            });
            generalConfigDatatable(table,"#datatable'.$page.'View");
            generalConfig(); 
        });
        function delete'.$page.'('.$symbol.'ID) {
            deletedRow("#datatable'.$page.'View",{
                "PageName":$("#PageName").val(),
                "'.$symbol.'ID_UIZP": '.$symbol.'ID,
                "'.$symbol.'Deleted_UIZ":1,
                "table":"'.$page.'",
                "symbol":"'.$symbol.'"
            });
        }
        function edit'.$page.'('.$symbol.'ID) {
            $("#'.$symbol.'ID_UIZP").val(Number('.$symbol.'ID));
            getDataFromServer("edit'.$page.'Form","\''.$page.'\'");  
            $("#edit'.$page.'Modal").modal("toggle");
        }  
    ');
    fclose($fp);
    chmod("controllers/".$page.".js", 0777);
}
if(!file_exists("_general/style/".$page.".css")){
    $fp = fopen("_general/style/".$page.".css", 'a+');
    fwrite($fp, '
        h4{
            color:red;
            background-color:black;
            font-size:50px;
            text-align:center;
            margin:20px;
            padding:20px;
        }
        #datatable'.$page.'View th{
            text-align:center;
            font-size: 12px;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }
    ');
    fclose($fp);
    chmod("_general/style/".$page.".css", 0777);
}
                }

            }
        }
        public static function createNewReport($filePermissionData){
            for($i=0;$i<count($filePermissionData);$i++){
                extract($filePermissionData[$i]);
                if($create=="yes" && $page_type=="report"){
                    if(!file_exists("views/report/".$page.".php")){
                        $fp = fopen("views/report/".$page.".php", 'a+');
                        fwrite($fp,'
                            <?php 
                                $fileName=__FILE__;
                                include_once "header.php";
                            ?>
                            <style>     
                            </style>
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <div class="heading-elements">
                                        <ul class="icons-list">
                                            <li><a data-action="collapse"></a></li>
                                            <li><a data-action="reload"></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
                                        <div class="col-sm-12">
                                            <div class="form-group"><?php 
                                                echo input2("col-sm-6",array(),"","",""," icon-home9",0,"select2Class");
                                                echo input2("col-sm-6",array(),"","","","icon-history",0,"select2Class");
                                            ?></div>
                                            <div class="form-group"><?php 
                                                echo input1("col-sm-6","text","Start Date","startDate","required"," icon-calendar","","","dateStyle");
                                                echo input1("col-sm-6","text","Start End","endDate","required"," icon-calendar","","","dateStyle");
                                            ?></div>
                                            <div class="form-group"><?php
                                                echo input1("col-sm-6","number","","startNumber","required","icon-tree6","0","","","min=0");
                                                echo input1("col-sm-6","number","","endNumber","required",  "icon-tree6","0","","","min=0");
                                            ?></div>
                                        </div>  
                                        <div class="text-right"><?php
                                            echo button2("return_data","button","Report","icon-book","btn btn-warning btn-xlg btn-labeled btn-labeled-right");
                                            echo button2("pdf_data","button","PDF File","icon-file-pdf","btn btn-success btn-xlg btn-labeled btn-labeled-right");
                                        ?></div>
                                    </form> 
                                </div>
                            </div>
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <div class="heading-elements">
                                        <ul class="icons-list">
                                            <li><a data-action="collapse"></a></li>
                                            <li><a data-action="reload"></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-framed table-sm">
                                            <thead>
                                                <tr class="border-double bg-blue">
                                                </tr>
                                            </thead>
                                            <tbody id="reportBody">	
                                            </tbody>
                                            <tfoot>
                                                <tr class="border-double bg-blue">
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function () {
                                    generalConfig();
                                    $("#return_data").on("click",function(){
                                        console.log(111);
                                        $.ajax({
                                            url: "models/_report.php",
                                            type: "POST",
                                            dataType:"json",
                                            data: {
                                                "type":"adminActive",
                                                "typeUser":$("#typeUser").val(),
                                                "stateUser":$("#stateUser").val(),
                                                "startDate":$("#startDate").val(),
                                                "endDate":$("#endDate").val(),
                                                "startNumber":$("#startNumber").val(),
                                                "endNumber":$("#endNumber").val(),
                                            },
                                            complete: function () {
                                                oneCloseLoader("#"+$(this).parent().id,"self");
                                            },
                                            beforeSend: function () {
                                                oneOpenLoader("#"+$(this).parent().id,"self","dark");
                                            },
                                            success: function (res) {
                                                $("#reportBody").empty();
                                                for (let index = 0; index < res.length; index++) {
                                                    $("#reportBody").append(`
                                                        <tr>
                                                            <td>${index+1}</td>
                                                            <td>${res[index]["STFUsername"]}</td>
                                                            <td>${res[index]["STFFullname"]}</td>
                                                            <td>${res[index]["STFPhoneNumber"]}</td>
                                                            <td>${res[index]["STFEmail"]}</td>
                                                            <td>${res[index]["STFRegisterDate"]}</td>
                                                        </tr>
                                                    `);
                                                }
                                            },
                                            fail: function (err){
                                            },
                                            always:function(){
                                            }
                                        });
                                    });
                                    $("#pdf_data").on("click",function(){
                                        window.open(`pdf/adminActive.php?typeUser=${$("#typeUser").val()}&stateUser=${$("#stateUser").val()}`);
                                    });
                                });
                            </script>
                        ');
                        fclose($fp);
                        chmod("views/report/".$page.".php", 0777);
                    } 
                    if(!file_exists("pdf/".$page.".php")){
                        $fp = fopen("pdf/".$page.".php", 'a+');
                        fwrite($fp,'
                            <?php
                                require_once "header.php";
                                $style=file_get_contents("../_general/style/pdf/report/report_all_round.css");
                                $mpdf =new \Mpdf\Mpdf([
                                    "tempDir" => __DIR__ . "/tmp",
                                    "mode" => "utf-8", 
                                    "format" => "A4", 
                                    "default_font_size" => "0", 
                                    "default_font" => "Arial",
                                    "margin_left" => "4",
                                    "margin_right" => "4",
                                    "margin_top" => "50", 
                                    "margin_bottom" => "10", 
                                    "margin_header" => "0", 
                                    "margin_footer" => "10", 
                                    "orientation" => "P"
                                ]); 
                                $mpdf->setHTMLHeader("
                                    <div>
                                        <div style="text-align:center;padding-top: 10px;">
                                            <img src="logo.jpg" width="300px" heigth="180px" alt="">
                                        </div>
                                        <h2 style="text-align:center;padding-bottom:0px;margin-bottom:0px;">
                                            Blumont Kerosene Distribution Program 2018 – 2019
                                        </h2>           
                                    </div>
                                    <hr>
                                ");	
                                $mpdf->setFooter("|{PAGENO} of {nb}|");
                                $mpdf->WriteHTML($style,1);	
                                extract($_GET);
                                $table.="
                                ";
                                $mpdf->WriteHTML("
                                ".$table."
                                ",2);	
                                $mpdf->Output();
                            ?>
        

                        ');
                        fclose($fp);
                        chmod("pdf/".$page.".php", 0777);
                    } 
                    if(!file_exists("_general/style/pdf/report/".$page.".css")){
                        $fp = fopen("_general/style/pdf/report/".$page.".css", 'a+');
                        fwrite($fp,'
                            #meanTable {
                                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                                border-collapse: collapse;
                                width: 100%;
                                border: 1px solid black;
                                page-break-inside: auto;
                            }
                            #meanTable td, #meanTable th {
                                border: 1px solid rgba(151, 146, 146, 0.329);
                            }
                            #meanTable td ,#meanTable th {
                                padding-left:10px;
                                padding-right:10px;
                                padding-top: 15px;
                                padding-bottom: 15px;
                                text-align:center;
                            }
                            #meanTable th {
                                background-color:#06a9f4;
                                color:white;
                            }
                        ');
                        fclose($fp);
                        chmod("_general/style/pdf/report/".$page.".css", 0777);
                    } 
                }
            }
            
        }

	}
?>