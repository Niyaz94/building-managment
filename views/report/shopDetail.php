<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div class="print_style panel panel-flat">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input2("col-sm-6",array(0=>"Shop",1=>"Stand",2=>"Office",3=>"All"),"Build Type","buildType","","icon-home9",0,"select2Class");
                    echo input2("col-sm-6",array(0=>"Empty",1=>"Full",2=>"All"),"Building Situation","buildStiuation","","icon-home9",0,"select2Class");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("return_data","button","Report","icon-book","btn btn-warning btn-xlg btn-labeled btn-labeled-right");
                //echo button2("pdf_data","button","PDF File","icon-file-pdf","btn btn-success btn-xlg btn-labeled btn-labeled-right");
            ?></div>
        </form> 
    </div>
</div>
<div class="infoPrint">
    <p class="" id="printtitle">Building Detail</p>
    <p>----------------------------------------------</p>
</div>
<table id="monthly_table" class="table table-framed table-sm">
    <thead>
        <tr class="border-double bg-blue">
            <th>#</th>
            <th>Building Number</th>
            <th>Building Floor</th>
            <th>Building Area</th>
            <th>Building Type</th>
            <th>Building Situation</th>
            <th>Electric</th>
        </tr>
    </thead>
    <tbody id="reportBody">	
    </tbody>
</table>
<script>
    $(document).ready(function () {
        generalConfig();
        $("#return_data").on("click",function(){
            $.ajax({
                url: "models/_report.php",
                type: "POST",
                dataType:"json",
                data: {
                    "type":"shopDetail",
                    "buildType":$("#buildType").val(),
                    "buildStiuation":$("#buildStiuation").val()
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
                        SOPCategory="";
                        if(res[index]["SOPCategory"]==0){
                            SOPCategory="Shop";
                        }else if(res[index]["SOPCategory"]==1){
                            SOPCategory="Stand";
                        }else if(res[index]["SOPCategory"]==2){
                            SOPCategory="Office";
                        }
                        SOPType="";
                        if(res[index]["SOPType"]==0 || res[index]["SOPType"]==2){
                            SOPType="Empty";
                        }else if(res[index]["SOPType"]==1){
                            SOPType="Full";
                        }
                        $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td>${res[index]["SOPNumber"]}</td>
                                <td>${res[index]["SOPFloor"]}</td>
                                <td>${res[index]["SOPArea"]}</td>
                                <td>${SOPCategory}</td>
                                <td>${SOPType}</td>
                                <td>${res[index]["SOPElectricRate"]}</td>
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
            window.open(`pdf/shopDetail.php?buildType=${$("#buildType").val()}&buildStiuation=${$("#buildStiuation").val()}`);
        });
    });
</script>
                        