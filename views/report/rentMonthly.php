<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<style>    
 @media print{    
        .print_style{
            display: none !important;
        }
    } 
</style>
<div class="print_style panel panel-flat">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-12","text","Month","month","required"," icon-calendar","","","dateMonthandYear");
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
    <p class="" id="printtitle">Customer Rent Detail</p>
    <span>Start Date : </span>
    <p style="display:inline;padding-right:20px;" id="print_start_date"></p>
    <p>----------------------------------------------</p>
</div>
<table id="monthly_table" class="table table-framed table-sm">
    <thead>
        <tr class="border-double bg-blue">
            <th>#</th>
            <th>Customer Name</th>
            <th>Building Number</th>
            <th>Working Type</th>
            <th>Building Title</th>
            <th>Area Rent</th>
            <th>Service Rent</th>
            <th>Electric Rent</th>
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
                    "type":"rentMonthly",
                    "month":$("#month").val(),
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {    
                    $("#print_start_date").text($("#month").val());                
                    $("#reportBody").empty();
                    for (let index = 0; index < res.length; index++) {
                        rentType=[
                            "Not Paid",
                            "Not Paid",
                            "Not Paid"
                        ]
                        splitType=res[index]["type"].split(",");
                        for (let index = 0; index < splitType.length; index++) {
                            splitRent=splitType[index].split("_");
                            if(splitRent[0]=="0"){
                                if(splitRent[1]=="0"){
                                    rentType[0]="Not Paid";
                                }else if(splitRent[1]=="1"){
                                    rentType[0]="Partial Paid";
                                }else if(splitRent[1]=="2"){
                                    rentType[0]="Paid";
                                }
                            }
                            if(splitRent[0]=="1"){
                                if(splitRent[1]=="0"){
                                    rentType[1]="Not Paid";
                                }else if(splitRent[1]=="1"){
                                    rentType[1]="Partial Paid";
                                }else if(splitRent[1]=="2"){
                                    rentType[1]="Paid";
                                }
                            }
                            if(splitRent[0]="2"){
                                if(splitRent[1]=="0"){
                                    rentType[2]="Not Paid";
                                }else if(splitRent[1]=="1"){
                                    rentType[2]="Partial Paid";
                                }else if(splitRent[1]=="2"){
                                    rentType[2]="Paid";
                                }
                            }
                        }
                        $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td>${res[index]["CUSName"]}</td>
                                <td>${res[index]["SOPNumber"]}</td>
                                <td>${res[index]["AGRWorkype"]}</td>
                                <td>${res[index]["AGRShopTitle"]}</td>

                                <td>${rentType[0]}</td>
                                <td>${rentType[1]}</td>
                                <td>${rentType[2]}</td>
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
            window.open(`pdf/rentMonthly.php?month=${$("#month").val()}`);
        });
    });
</script>
                        