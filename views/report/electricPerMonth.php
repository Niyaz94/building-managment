
<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div class="print_style panel panel-flat">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-12","text","Month","month","required"," icon-calendar",date("Y-m"),"","dateMonthandYear");
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
    <p class="" id="printtitle">Electric Detail</p>
    <span>Start Date : </span>
    <p style="display:inline;" id="print_start_date"></p>
    <p>----------------------------------------------</p>
</div>
<table dir="rtl" id="monthly_table" class="table table-framed table-sm">
    <thead>
        <tr class="border-double bg-blue">
            <th>ت</th>
            <th>اسم المحل</th>
            <th>تسلسل الساعة</th>
            <th>القراءة السابقة</th>
            <th>القراءة الجديدة</th>
            <th>فرق القرااتين</th>
            <th>سعر الوحدة</th>
            <th>الوحدة / المبلغ</th>
            <th>الديون السابقة</th>
            <th>المبلغ الكلي</th>
        </tr>
    </thead>
    <tbody id="reportBody">	
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $( window ).bind( "beforeprint", expandAll );
        generalConfig();
        $("#return_data").on("click",function(){
            if($("#month").val().length>0){
                $.ajax({
                    url: "models/_report.php",
                    type: "POST",
                    dataType:"json",
                    data: {
                        "type":"electricPerMonth",
                        "month":$("#month").val(),
                    },
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {                    
                        $("#reportBody").empty();
                        $("#print_start_date").text($("#month").val());

                        for (let index = 0; index < res.length; index++) {
                            if(typeof res[index]["WNTNewRead"]=="object"){
                                for (let j = 0; j < res[index]["WNTNewRead"].length; j++) {   
                                    $("#reportBody").append(`
                                        <tr>
                                            <td>${index+1}</td>
                                            <td>${res[index]["AGRShopTitle"]}</td>
                                            <td>${res[index]["WTCName"][j]}</td>
                                            <td>${res[index]["WNTOldRead"][j]}</td>
                                            <td>${res[index]["WNTNewRead"][j]}</td>
                                            <td>${Number(res[index]["WNTNewRead"][j])-Number(res[index]["WNTOldRead"][j])}</td>
                                            <td>${res[index]["SOPElectricRate"]}</td>
                                            <td>${res[index]["currentTotal"][j]}</td>
                                            <td>${res[index]["oldTotal"][j]}</td>
                                            <td>${Number(res[index]["currentTotal"][j])+Number(res[index]["oldTotal"][j])}</td>
                                        </tr>
                                    `);                             
                                }
                            }else{
                                $("#reportBody").append(`
                                    <tr>
                                        <td>${index+1}</td>
                                        <td>${res[index]["AGRShopTitle"]}</td>
                                        <td>${res[index]["WTCName"]}</td>
                                        <td>${res[index]["WNTOldRead"]}</td>
                                        <td>${res[index]["WNTNewRead"]}</td>
                                        <td>${Number(res[index]["WNTNewRead"])-Number(res[index]["WNTOldRead"])}</td>
                                        <td>${res[index]["SOPElectricRate"]}</td>
                                        <td>${res[index]["currentTotal"]}</td>
                                        <td>${res[index]["oldTotal"]}</td>
                                        <td>${Number(res[index]["currentTotal"])+Number(res[index]["oldTotal"])}</td>
                                    </tr>
                                `);
                            }
                        }
                    },
                    fail: function (err){
                    },
                    always:function(){
                    }
                });
            }else{
                oneAlert("error","Error!!!","Please Select Month");
            }
        });
        $("#pdf_data").on("click",function(){
            window.open(`pdf/electricPerMonth.php?month=${$("#month").val()}`);
        });
        function expandAll() {
            //alert(123);
        }


    });
</script>
                        