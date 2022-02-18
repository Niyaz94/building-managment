<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
    <div class="print_style panel panel-flat" style="">
        <div class="panel-body ">
            <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
                <div class="col-sm-12">
                    <div class="form-group"><?php 
                        echo input1("col-sm-6","text","Start Date","startDate","required"," icon-calendar","","","dateStyle");
                        echo input1("col-sm-6","text","End Date","endDate","required"," icon-calendar","","","dateStyle");
                    ?></div>
                    <div class="form-group"><?php 
                        echo input2("col-sm-12",array(2=>"Both",0=>"USD",1=>"IQD"),"Money Type","moneyType","","icon-cash",0,"select2Class");
                    ?></div>
                </div>  
                <div class="text-right"><?php
                    echo button2("return_data","button","Report","icon-book","btn btn-warning btn-xlg btn-labeled btn-labeled-right");
                ?></div>
            </form> 
        </div>
    </div>
    <div class="infoPrint" style="padding:0px;margin:0px;">
        <p>----------------------------------------------</p>
        <p class="" id="printtitle">Extra Income</p>
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
                <th>Money</th>
                <th>Money Type</th>
                <th>USD rate</th>
                <th>Date</th>
                <th>Note</th>
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
                    "type":"extraIncome",
                    "endDate":$("#endDate").val(),
                    "startDate":$("#startDate").val(),
                    "moneyType":$("#moneyType").val(),
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
                    for (let index = 0; index < res.length; index++) {
                        $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td>${Number(res[index]["CPTMoney"])}</td>
                                <td>${(res[index]["CPTMoneyType"])}</td>
                                <td>${Number(res[index]["CPTUSDRate"])}</td>
                                <td>${(res[index]["CPTDate"])}</td>
                                <td>${(res[index]["CPTNote"])}</td>
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
    });
</script>