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
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-12","text","Date","today","required"," icon-calendar","","","dateStyle");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("return_data","button","Report","icon-book","btn btn-warning btn-xlg btn-labeled btn-labeled-right");
                //echo button2("pdf_data","button","PDF File","icon-file-pdf","btn btn-success btn-xlg btn-labeled btn-labeled-right");
            ?></div>
        </form> 
    </div>
</div>
<div class="bode_style">
    <div class="infoPrint">
        <p>----------------------------------------------</p>
        <p class="" id="printtitle">حركة الصندوق ليوم</p>
        <p class="" id="printdate"></p>
        <p>----------------------------------------------</p>
    </div>
    <h3 style="text-align:center">السندات الايجارات</h3>
    <table id="today_income_a" class="tables_style tables_style2">
                <thead>
                    <tr class="border-double bg-blue">
                        <th>Shop Name</th>
                        <th>Note</th>
                        <th>Invoice ID</th>
                        <th>USD</th>
                        <th>IQD</th>
                    </tr>
                </thead>
                <tbody id="today_income_a_body">	
                </tbody>
    </table>
    <br/>
    <h3 style="text-align:center">السندات الخدمات</h3>
    <table id="today_income_s" class="tables_style">
                <thead>
                    <tr class="border-double bg-blue">
                        <th>Shop Name</th>
                        <th>Note</th>
                        <th>Invoice ID</th>
                        <th>USD</th>
                        <th>IQD</th>
                    </tr>
                </thead>
                <tbody id="today_income_s_body">	
                </tbody>
    </table>
    <br/>
    <h3 style="text-align:center">السندات الكهرباء</h3>
    <table id="today_income_e" class=" tables_style">
                <thead>
                    <tr class="border-double bg-blue">
                        <th>Shop Name</th>
                        <th>Note</th>
                        <th>Invoice ID</th>
                        <th>USD</th>
                        <th>IQD</th>
                    </tr>
                </thead>
                <tbody id="today_income_e_body">	
                </tbody>
    </table>
    <br/>
    <h3 style="text-align:center">المصارف</h3>
    <table class=" tables_style">
                <thead>
                    <tr class="border-double bg-blue">
                        <th>Note</th>
                        <th>Invoice ID</th>
                        <th>USD</th>
                        <th>IQD</th>
                    </tr>
                </thead>
                <tbody id="today_exponse_body">	
                </tbody>
    </table>
    <div >
            <br/>
            <div style="float:left;width:48%;">
                <table class="tables_style" style="width:100%">
                        <thead>
                            <tr class="border-double bg-blue">
                                <th>التفاصيل</th>
                                <th>دولار</th>
                                <th>دينار</th>
                            </tr>
                        </thead>
                        <tbody>	
                            <tr>
                                <td>نقد بالصندق</td>
                                <td id="table1_nqd_usd">0</td>
                                <td id="table1_nqd_iqd">0</td>                                
                            </tr>
                            <tr>
                                <td>حساب البنك</td>
                                <td id="table1_bank_usd">0</td>
                                <td id="table1_bank_iqd">0</td>
                            </tr>
                            <tr>
                                <td>السلف</td>
                                <td id="advance_usd">0</td>
                                <td id="advance_iqd">0</td>
                            </tr>
                            <tr>
                                <td>صك</td>
                                <td id="table1_check_usd">0</td>
                                <td id="table1_check_iqd">0</td>
                            </tr>
                            <tr style="background-color:#d4c6c6;color:white;">
                                <td>المجموع</td>
                                <td id="table1_total_usd">0</td>
                                <td id="table1_total_iqd">0</td>
                            </tr>
                        </tbody>
                </table>
            </div>
            <div style="float:right;width:48%;">
                <table class="tables_style"  >
                        <thead>
                            <tr class="border-double bg-blue">
                                <th>التفاصيل</th>
                                <th>دولار</th>
                                <th>دينار</th>
                            </tr>
                        </thead>
                        <tbody>	
                            <tr>
                                <td>مدور من اليوم السابق</td>
                                <td id="table2_yesterday_usd">0</td>
                                <td id="table2_yesterday_iqd">0</td>
                            </tr>
                            <tr>
                                <td>مجموع</td>
                                <td id="table2_today_income_usd"></td>
                                <td id="table2_today_income_iqd"></td>
                            </tr>
                            <tr>
                                <td>شراء الدولار</td>
                                <td></td>
                                <td></td>                                
                            </tr>
                            <tr>
                                <td>بيع الدولار</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="background-color:#d4c6c6;color:white;">
                                <td>المجموع</td>
                                <td id="table2_todayYesterday_usd"></td>
                                <td id="table2_todayYesterday_iqd"></td>
                            </tr>
                            <tr>
                                <td>المجموع المصاريف</td>
                                <td id="table2_expense_usd"></td>
                                <td id="table2_expense_iqd"></td>
                            </tr>
                            <tr style="background-color:#d4c6c6;color:white;">
                                <td>المجموع</td>
                                <td id="table2_total_usd"></td>
                                <td id="table2_total_iqd"></td>
                            </tr>
                        </tbody>
                </table>
            </div>
    </div>
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
                    "type":"dailyTotal",
                    "date":$("#today").val()
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    $("#today_income_a_body").empty();
                    $("#today_income_s_body").empty();
                    $("#today_income_e_body").empty();
                    $("#today_exponse_body").empty();
                    $("#printdate").text($("#today").val());
                    today_income=res["today_income"];
                    total_iqd_income_a=0,total_usd_income_a=0,total_invoice_usd=0,total_invoice_iqd=0;
                    for (let index = 0,indexL=today_income.length; index < indexL; index++) {
                        if(today_income[index]["type"]==0){
                            $("#today_income_a_body").append(`
                                <tr>
                                    <td>${today_income[index]["AGRShopTitle"]}</td>
                                    <td>${today_income[index]["PNTNote"]}</td>
                                    <td>${today_income[index]["PNTID"]}</td>
                                    <td>${Number(today_income[index]["usd"])}</td>
                                    <td>${Number(today_income[index]["iqd"])}</td>
                                </tr>
                            `);
                            total_iqd_income_a+=Number(today_income[index]["iqd"]);
                            total_usd_income_a+=Number(today_income[index]["usd"]);
                        }
                    }
                    $("#today_income_a_body").append(`
                        <tr style="background-color:#d4c6c6;color:white;">
                            <td colspan=3>Total</td>
                            <td>${total_usd_income_a}</td>
                            <td>${total_iqd_income_a}</td>
                        </tr>
                    `);
                    total_invoice_usd+=Number(total_usd_income_a);
                    total_invoice_iqd+=Number(total_iqd_income_a);

                    total_iqd_income_s=0,total_usd_income_s=0;
                    for (let index = 0,indexL=today_income.length; index < indexL; index++) {
                        if(today_income[index]["type"]==1){
                            $("#today_income_s_body").append(`
                                <tr>
                                    <td>${today_income[index]["AGRShopTitle"]}</td>
                                    <td>${today_income[index]["PNTNote"]}</td>
                                    <td>${today_income[index]["PNTID"]}</td>
                                    <td>${Number(today_income[index]["usd"])}</td>
                                    <td>${Number(today_income[index]["iqd"])}</td>
                                </tr>
                            `);
                            total_iqd_income_s+=Number(today_income[index]["iqd"]);
                            total_usd_income_s+=Number(today_income[index]["usd"]);
                        }
                    }
                    $("#today_income_s_body").append(`
                        <tr style="background-color:#d4c6c6;color:white;">
                            <td colspan=3>Total</td>
                            <td>${total_usd_income_s}</td>
                            <td>${total_iqd_income_s}</td>
                        </tr>
                    `);
                    total_invoice_usd+=Number(total_usd_income_s);
                    total_invoice_iqd+=Number(total_iqd_income_s);

                    total_iqd_income_e=0,total_usd_income_e=0;
                    for (let index = 0,indexL=today_income.length; index < indexL; index++) {
                        if(today_income[index]["type"]==2){
                            $("#today_income_e_body").append(`
                                <tr>
                                    <td>${today_income[index]["AGRShopTitle"]}</td>
                                    <td>${today_income[index]["PNTNote"]}</td>
                                    <td>${today_income[index]["PNTID"]}</td>
                                    <td>${Number(today_income[index]["usd"])}</td>
                                    <td>${Number(today_income[index]["iqd"])}</td>
                                </tr>
                            `);
                            total_iqd_income_e+=Number(today_income[index]["iqd"]);
                            total_usd_income_e+=Number(today_income[index]["usd"]);
                        }
                    }
                    $("#today_income_e_body").append(`
                        <tr style="background-color:#d4c6c6;color:white;">
                            <td colspan=3>Total</td>
                            <td>${total_usd_income_e}</td>
                            <td>${total_iqd_income_e}</td>
                        </tr>
                    `);
                    total_invoice_usd+=Number(total_usd_income_e);
                    total_invoice_iqd+=Number(total_iqd_income_e);


                    today_expense=res["today_expense"];
                    total_iqd_expense=0,total_usd_expense=0;
                    for (let index = 0,indexL=today_expense.length; index < indexL; index++) {
                        $("#today_exponse_body").append(`
                            <tr>
                                <td>${today_expense[index]["EXPNote"]}</td>
                                <td>${today_expense[index]["EXPID"]}</td>
                                <td>${today_expense[index]["EXPMoneyType"]==0?Number(today_expense[index]["EXPMoney"]):0}</td>
                                <td>${today_expense[index]["EXPMoneyType"]==1?Number(today_expense[index]["EXPMoney"]):0}</td>
                            </tr>
                        `);
                        total_iqd_expense+=Number(today_expense[index]["EXPMoneyType"]==1?Number(today_expense[index]["EXPMoney"]):0);
                        total_usd_expense+=Number(today_expense[index]["EXPMoneyType"]==0?Number(today_expense[index]["EXPMoney"]):0);
                    }
                    $("#today_exponse_body").append(`
                        <tr style="background-color:#d4c6c6;color:white;">
                            <td colspan=2>Total</td>
                            <td>${total_usd_expense}</td>
                            <td>${total_iqd_expense}</td>
                        </tr>
                    `);

                    $("#table1_nqd_usd").text(Number(res["real_capital"]["income_usd"])-Number(res["real_capital"]["expense_usd"]));
                    $("#table1_nqd_iqd").text(Number(res["real_capital"]["income_iqd"])-Number(res["real_capital"]["expense_iqd"]));

                    
                    capital=res["capital"];
                    total_iqd_capital=0,total_usd_capital=0;
                    for (let index = 0,indexL=capital.length; index < indexL; index++) {
                        if(capital[index]["PNTPaidType"]==1){
                            $("#table1_bank_usd").text(Number(capital[index]["usd"]));
                            $("#table1_bank_iqd").text(Number(capital[index]["iqd"]));
                            total_iqd_capital+=Number(capital[index]["iqd"]);
                            total_usd_capital+=Number(capital[index]["usd"]);
                        }else if(capital[index]["PNTPaidType"]==2){
                            $("#table1_check_usd").text(Number(capital[index]["usd"]));
                            $("#table1_check_iqd").text(Number(capital[index]["iqd"]));
                            total_iqd_capital+=Number(capital[index]["iqd"]);
                            total_usd_capital+=Number(capital[index]["usd"]);
                        }
                    }
                    $("#table1_total_usd").text(Number(total_usd_capital)+Number(res["real_capital"]["income_usd"])-Number(res["real_capital"]["expense_usd"])+Number(res["advance"]["usd"]));
                    $("#table1_total_iqd").text(Number(total_iqd_capital)+Number(res["real_capital"]["income_iqd"])-Number(res["real_capital"]["expense_iqd"])+Number(res["advance"]["iqd"]));


                    $("#table2_expense_usd").text(total_usd_expense);
                    $("#table2_expense_iqd").text(total_iqd_expense);

                    $("#table2_today_income_usd").text(total_invoice_usd);
                    $("#table2_today_income_iqd").text(total_invoice_iqd);

                    $("#advance_usd").text(res["advance"]["usd"]);
                    $("#advance_iqd").text(res["advance"]["iqd"]);

                    $("#table2_yesterday_usd").text(Number(res["advance"]["usd"])+Number(res["yesterday"]["income_usd"])-Number(res["yesterday"]["expense_usd"]));
                    $("#table2_yesterday_iqd").text(Number(res["advance"]["iqd"])+Number(res["yesterday"]["income_iqd"])-Number(res["yesterday"]["expense_iqd"]));
                   
                    $("#table2_todayYesterday_usd").text(Number(total_invoice_usd)+Number(res["advance"]["usd"])+Number(res["yesterday"]["income_usd"])-Number(res["yesterday"]["expense_usd"]));
                    $("#table2_todayYesterday_iqd").text(Number(total_invoice_iqd)+Number(res["advance"]["iqd"])+Number(res["yesterday"]["income_iqd"])-Number(res["yesterday"]["expense_iqd"]));



                    $("#table2_total_usd").text(Number(total_invoice_usd)+Number(res["advance"]["usd"])+Number(res["yesterday"]["income_usd"])-Number(res["yesterday"]["expense_usd"])-total_usd_expense);
                    $("#table2_total_iqd").text(Number(total_invoice_iqd)+Number(res["advance"]["iqd"])+Number(res["yesterday"]["income_iqd"])-Number(res["yesterday"]["expense_iqd"])-total_iqd_expense);
                },
                fail: function (err){
                },
                always:function(){
                }
            });
        });
        $("#pdf_data").on("click",function(){
            window.open(`pdf/dailyTotal.php?date=${$("#today").val()}`);
        });
    });
</script>