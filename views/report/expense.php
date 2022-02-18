<?php 
    $fileName=__FILE__;
    include_once "header.php";
    $res = $database->return_data2(array(
        "tablesName"=>array("expenses_group"),
        "columnsName"=>array("EXGID","EXGName"),
        "conditions"=>array(
            array("columnName"=>"EXGDeleted","operation"=>"=","value"=>0,"link"=>"")
        ),
        "others"=>"",
        "returnType"=>"key_all"
    ));
    $expenseGroup=array();
    $expenseGroup[0]="All";
    for($i=0;$i<count($res);++$i){
        $expenseGroup[$res[$i]["EXGID"]]=$res[$i]["EXGName"];
    }
?>
<div class="print_style panel panel-flat">
    <div class="panel-heading">
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="reload"></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body ">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-6","text","Start Date","startDate","required"," icon-calendar","","","dateStyle");
                    echo input1("col-sm-6","text","End Date","endDate","required"," icon-calendar","","","dateStyle");
                ?></div>
                <div class="form-group"><?php 
                    echo input2("col-sm-6",$expenseGroup,"Expense Type","exgid",""," icon-home9",0,"select2Class");
                    echo input2("col-sm-6",array(2=>"Both",0=>"USD",1=>"IQD"),"Money Type","moneyType","","icon-cash",0,"select2Class");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("return_data","button","Report","icon-book","btn btn-warning btn-xlg btn-labeled btn-labeled-right");
            ?></div>
        </form> 
    </div>
</div>
<div class="infoPrint">
    <p>----------------------------------------------</p>
    <p class="" id="printtitle">Expenses</p>
    <span>Start Date : </span>
    <p style="display:inline;padding-right:20px;" id="print_start_date"></p>
    <span style="padding-left:20px;">End Date : </span>
    <p style="display:inline;" id="print_end_date"></p>
    <p>----------------------------------------------</p>
</div>
<div class="mainContainer">
    <table id="monthly_table" class="table table-framed table-sm">
        <thead>
            <tr class="border-double bg-blue">
                <th>#</th>
                <th>Expense Type</th>
                <th>Money</th>
                <th>Money Type</th>
                <th>USD rate</th>
                <th>Date</th>
                <th>For</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody id="reportBody">	
        </tbody>
        <thead>
            <tr class="border-double bg-blue">
                <th width="25%" colspan="2">Total USD</th>
                <th width="25%" colspan="2" id="total_usd"></th>
                <th width="25%" colspan="2">Total IQD</th>
                <th width="25%" colspan="2" id="total_iqd"></th>
            </tr>
        </thead>
    </table>
</div>
<script>
    $(document).ready(function () {
        generalConfig();
        $("#return_data").on("click",function(){
            $.ajax({
                url: "models/_report.php",
                type: "POST",
                dataType:"json",
                data: {
                    "type":"expense",
                    "endDate":$("#endDate").val(),
                    "startDate":$("#startDate").val(),
                    "moneyType":$("#moneyType").val(),
                    "exgid":$("#exgid").val(),
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    $("#reportBody").empty();
                    $("#print_start_date").text($("#startDate").val());
                    $("#print_end_date").text($("#endDate").val());
                    for (let index = 0; index < res.length-1; index++) {
                        $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td>${res[index]["EXGName"]}</td>
                                <td>${Number(res[index]["EXPMoney"])}</td>
                                <td>${(res[index]["EXPMoneyType"])}</td>
                                <td>${Number(res[index]["EXPUSDRate"])}</td>
                                <td>${(res[index]["EXPDate"])}</td>
                                <td>${(res[index]["EXPForPerson"])}</td>
                                <td>${(res[index]["EXPNote"])}</td>
                            </tr>
                        `);
                    }
                    $("#total_usd").text(Number(res[res.length-1]["total_usd"]).toLocaleString());
                    $("#total_iqd").text(Number(res[res.length-1]["total_iqd"]).toLocaleString());
                },
                fail: function (err){
                },
                always:function(){
                }
            });
        });
    });
</script>