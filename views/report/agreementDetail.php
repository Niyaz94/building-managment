<?php 
    $fileName=__FILE__;
    include_once "header.php";
    $res = $database->return_data2(array(
        "tablesName"=>array("agreement"),
        "columnsName"=>array("AGRID","AGRShopTitle"),
        "conditions"=>array(
            array("columnName"=>"AGRDeleted","operation"=>"=","value"=>0,"link"=>"")
        ),
        "others"=>"",
        "returnType"=>"key_all"
    ));
    $agreementID=array(0=>"All");
    for($i=0;$i<count($res);++$i){
        $agreementID[$res[$i]["AGRID"]]=$res[$i]["AGRShopTitle"];
    }
?>
<div class="print_style panel panel-flat">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input2("col-sm-6",$agreementID,"Shop Title","agrid",""," icon-home9",0,"select2Class");
                    echo input2("col-sm-6",array(3=>"All",0=>"Rent",1=>"Service",2=>"Electric"),"Rent Type","rentType","","icon-cash",0,"select2Class");
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-6","text","Start Date","startDate","required"," icon-calendar","","","dateStyle");
                    echo input1("col-sm-6","text","End Date","endDate","required"," icon-calendar","","","dateStyle");
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
    <p>----------------------------------------------</p>
    <p class="" id="printtitle">Rent Detail</p>
    <span>Start Date : </span>
    <p style="display:inline;padding-right:20px;" id="print_start_date"></p>
    <span style="padding-left:20px;">End Date : </span>
    <p style="display:inline;" id="print_end_date"></p>
    <p>----------------------------------------------</p>
</div>
<table id="monthly_table" class="table table-framed table-sm">
    <thead>
        <tr class="border-double bg-blue">
            <th>#</th>
            <th>Shop Title</th>
            <th>Rent Type</th>
            <th>Date</th>
            <th>Money</th>
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
                    "type":"agreementDetail",
                    "agrid":$("#agrid").val(),
                    "rentType":$("#rentType").val(),
                    "endDate":$("#endDate").val(),
                    "startDate":$("#startDate").val(),
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) { 
                    $("#print_start_date").text($("#startDate").val());
                    $("#print_end_date").text($("#endDate").val());                   
                    $("#reportBody").empty();
                    total=0;
                    for (let index = 0,indexL=res.length; index < indexL; index++) {

                        const split=res[index]["ARTDate"].split("-");
                        const date = new Date(split[0], split[1]-1, split[2]);
                        const month = date.toLocaleString('en-us', { month: 'long' });
                        $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td>${res[index]["AGRShopTitle"]}</td>
                                <td>${res[index]["ARTRentType"]}</td>
                                <td>${month+" / "+split[0]}</td>
                                <td>${Number(res[index]["total"])}</td>
                            </tr>
                        `);
                        total+=Number(res[index]["total"]);
                    }
                    $("#reportBody").append(`
                        <tr>
                            <td colspan=4 style="background-color:#e48d8d;color:white;">Total</td>
                            <td colspan=2 style="background-color:#e48d8d;color:white;">${total}</td>
                        </tr>
                    `);
                },
                fail: function (err){
                },
                always:function(){
                }
            });
        });
        $("#pdf_data").on("click",function(){
            window.open(`pdf/agreementDetail.php?agrid=${$("#agrid").val()}`);
        });
    });
</script>
                        