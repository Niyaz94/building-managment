<?php 
    $fileName=__FILE__;
    include_once "header.php";
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
                    echo input1("col-sm-12","text","Select Month","month","required","icon-calendar",date("Y-m"),"","dateMonthandYear");
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
    <p class="" id="printtitle">كشف حساب المتأجرين</p>
    <span>Month : </span>
    <p style="display:inline;" id="print_date"></p>
    <p>----------------------------------------------</p>
</div>
<table id="monthly_table" class="table table-framed table-sm">
    <thead>
        <tr class="border-double bg-blue">
            <th>#</th>
            <th>Shop Name</th>
            <th>Shop Area</th>
            <th>Shop Floor</th>
            <th>Shop Rent</th>
            <th>Monthly Rent</th>
            <th>Basic Service</th>
            <th>Service</th>
            <th>Electric</th>
            <th>Total</th>
            <th class="coulmnMonthDebt">Month Debt</th>
            <th>Total Debt</th>
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
                    "type":"monthlyTotal",
                    "month":$("#month").val()
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    $("#print_date").text($("#month").val());
                    $("#reportBody").empty();
                    for (let index = 0; index < res.length; index++) {
                        $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td>${res[index]["AGRShopTitle"]}</td>
                                <td>${res[index]["SOPArea"]}</td>
                                <td>${res[index]["SOPFloor"]}</td>
                                <td>${Number(res[index]["AGRShopRental"])}</td>
                                <td>${Number(res[index]["rent"])}</td>
                                <td>${Number(res[index]["serviceBasic"])}</td>
                                <td>${Number(res[index]["service"])}</td>
                                <td>${Number(res[index]["electric"])}</td>
                                <td>${Number(res[index]["rent"])+Number(res[index]["service"])+Number(res[index]["electric"])}</td>
                                <td>${res[index]["date_rent"]==null?"":res[index]["date_rent"].split(";").join("  /  ")}</td>
                                <td>${res[index]["totalDebt"]}</td>
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
            window.open(`pdf/monthlyTotal.php?month=${$("#month").val()}`);
        });
    });
</script>