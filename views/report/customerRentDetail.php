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
    $agreementID=array();
    for($i=0;$i<count($res);++$i){
        $agreementID[$res[$i]["AGRID"]]=$res[$i]["AGRShopTitle"];
    }
?>

<div class=" print_style panel panel-flat">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input2("col-sm-4",$agreementID,"Shop Title","agrid",""," icon-home9",0,"select2Class");
                    echo input1("col-sm-4","text","Start Date","startDate","required"," icon-calendar","","","dateStyle");
                    echo input1("col-sm-4","text","End Date","endDate","required"," icon-calendar","","","dateStyle");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("return_data","button","Report","icon-book","btn btn-warning btn-xlg btn-labeled btn-labeled-right");
                echo button2("pdf_data","button","PDF File","icon-file-pdf","btn btn-success btn-xlg btn-labeled btn-labeled-right");
            ?></div>
        </form> 
    </div>
</div>
<div class="infoPrint">
    <p>----------------------------------------------</p>
    <p class="" id="printtitle">Customer Rent Detail</p>
    <span>Start Date : </span>
    <p style="display:inline;padding-right:20px;" id="print_start_date"></p>
    <span style="padding-left:20px;">End Date : </span>
    <p style="display:inline;" id="print_end_date"></p>
    <p>----------------------------------------------</p>
</div>
<div class="mainContainer">
    <table id="" class="table table-framed table-sm">
        <thead>
        </thead>
        <tbody id="reportBody">	
        </tbody>
    </table>
</div>  
<script>
    $(document).ready(function () {
        generalConfig();
        $("#return_data").on("click",function(){
            if($("#agrid").val()>0){
                $.ajax({
                    url: "models/_report.php",
                    type: "POST",
                    dataType:"json",
                    data: {
                        "type":"customerRentDetail",
                        "agrid":$("#agrid").val(),
                        "startDate":$("#startDate").val(),
                        "endDate":$("#endDate").val(),
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
                        let balance=Number(res[res.length-1][2]);
                        $("#reportBody").append(`
                            <tr>
                                <td colspan=6 style="background-color:#d86e6e;color:white;">Start Balance</td>
                                <td style="padding:0px;margin:0px;border:0px;color:white;"><span class="label label-block label-flat border-info text-slate-800" style="padding:6%;font-size:20px">${Number(balance)}</span></td>
                            </tr>
                        `);
                        for (let index = 0,indexL=res.length-1; index < indexL; index++) {
                            if(res[index]["debt"] !== undefined){
                                balance-=Number(res[index]["debt"]);
                                $("#reportBody").append(`
                                    <tr>
                                        <td>${index+1}</td>
                                        <td>${res[index]["type"]}</td>
                                        <td>${res[index]["date"]}</td>
                                        <td>${res[index]["note"]}</td>
                                        <td style="padding:0px;margin:0px;border:0px;"><span class="label label-block label-flat border-danger text-slate-800" style="padding:6%;font-size:20px">${Number(res[index]["debt"])}</span></td>
                                        <td></td>
                                        <td style="padding:6px 4px;margin:0px;border:0px;"><span class="label label-block label-flat border-primary text-slate-800" style="padding:6%;font-size:20px">${Number(balance)}</span></td>
                                    </tr>
                                `);
                            }
                            if(res[index]["payment"] !== undefined){
                                balance+=Number(res[index]["payment"]);
                                $("#reportBody").append(`
                                    <tr>
                                        <td>${index+1}</td>
                                        <td>${res[index]["type"]}</td>
                                        <td>${res[index]["date"]}</td>
                                        <td>${res[index]["note"]}</td>
                                        <td></td>
                                        <td style="padding:0px;margin:0px;border:0px;">
                                            <span class="label label-block label-flat border-success text-slate-800" style="padding:6%;font-size:20px">
                                                ${Number(res[index]["payment"])}
                                            </span>
                                        </td>
                                        <td style="padding:6px 4px;margin:0px;border:0px;">
                                            <span class="label label-block label-flat border-primary text-slate-800" style="padding:6%;font-size:20px">
                                                ${Number(balance)}
                                            </span>
                                        </td>
                                    </tr>
                                `);
                            }   
                        }
                        $("#reportBody").append(`
                            <tr>
                                <td colspan=6 style="background-color:#d86e6e;color:white;">End Balance</td>
                                <td style="padding:0px;margin:0px;border:0px;color:white;"><span class="label label-block label-flat border-info text-slate-800" style="padding:6%;font-size:20px">${Number(balance)}</span></td>
                            </tr>
                        `);
                    },
                    fail: function (err){
                    },
                    always:function(){
                    }
                });
            }else{
                oneAlert("error","Error!!!","Please Select Agreement");
            }
        });
        $("#pdf_data").on("click",function(){
            window.open(`pdf/customerRentDetail.php?agrid=${$("#agrid").val()}&startDate=${$("#startDate").val()}&endDate=${$("#endDate").val()}`);
        });
    });
</script>