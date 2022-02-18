$(document).ready(function () {
    $.ajax({
        url: "models/_agreement.php",
        type: "POST",
        data: {
            "type":"addPayment",
            "PageName":$("#PageName").val()
        },
        complete: function () {
            oneCloseLoader("#"+$(this).parent().id,"self");
        },
        beforeSend: function () {
            oneOpenLoader("#"+$(this).parent().id,"self","dark");
        },
        success: function (res) {
        },
        fail: function (err){
            oneAlert("error","Error!!!",res.data)
        },
        always:function(){
            console.log("complete");
        }
    });
    $("#addagreementForm").on("submit", function (e) {
        e.preventDefault();
        check1=dateValidation($("#AGRDateStart_IDZN").val(),$("#AGRPaymentStart_IKZN").val(),Number($("#EMPYearI").val()),Number($("#EMPMonthI").val()));
        if(check1["is_success"]==false){
            oneAlert("error","Error!!!",check1.data);
        }else{
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("AGRDuration",$("#EMPYearI").val()+","+$("#EMPMonthI").val());
            formData.append("AGRNote_IAZN",CKEDITOR.instances['AGRNote_IAZN'].getData());
            formData.append("type","create");
            $("#submit_add_btn").attr("disabled",true);
            $.ajax({
                url: "models/_agreement.php",
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
                        //$("#addagreementForm")[0].reset();
                        //$("#datatableagreementView").DataTable().ajax.reload(null, false);
                        $("#addagreementCollapse").collapse("hide");
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false);
                        setTimeout(function(){
                            location.reload();
                        },300);
                    }else{
                    oneAlert("error","Error!!!",res.data)
                    }
                    $("#submit_add_btn").attr("disabled",false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#submit_add_btn").attr("disabled",false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        }
    });
    $('#editagreementForm').on('submit', function (e) {
        e.preventDefault();
        check1=dateValidation($("#AGRDateStart_UDRN").val(),$("#AGRPaymentStart_UKRN").val(),Number($("#EMPYearU").val()),Number($("#EMPMonthU").val()));
        if(check1["is_success"]==false){
            oneAlert("error","Error!!!",check1.data);
        }else{
            $("#editagreementFormButton").attr('disabled', true);
            $(`#editagreementForm .imgContainer`).each(function(index,data){
                splitID=$(this).prop("id").split("-")[1];
                if($("#name-"+splitID).text().length==0){
                    $("#"+splitID).attr("name",splitID.substr(0,splitID.length-1)+"E");
                }
            });
            $("#AGRDuration_USRN").val($("#EMPYearU").val()+","+$("#EMPMonthU").val());
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","update");
            formData.append("AGRNote_UARN",CKEDITOR.instances['AGRNote_UARN'].getData());
            $("#editagreementFormButton").attr('disabled',true);
            $.ajax({
                url: "models/_agreement.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    if(res.is_success == true){
                        //$("#editagreementForm")[0].reset();
                        //CKEDITOR.instances['AGRNote_UARN'].setData(""); 
                        //$("#datatableagreementView").DataTable().ajax.reload(null, false);
                        $("#editagreementModal").modal('toggle');
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        setTimeout(function(){
                            location.reload();
                        },300);
                    }else{
                    oneAlert("error","Error!!!",res.data);
                    }
                    $("#editagreementFormButton").attr('disabled',false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#editCustomerFormButton").attr('disabled',false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        }
    });
    $('#renewagreementForm').on('submit', function (e) {
        e.preventDefault();
        if(Number($("#EMPYearN").val())==0 && Number($("#EMPMonthN").val())==0){
            oneAlert("error","Error!!!","You Must At least Select Month Or Year To Renew");
        }else{
            $("#renewagreementFormButton").attr('disabled', true);
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","renew");

            $.ajax({
                url: "models/_agreement.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    if(res.is_success == true){
                        location.reload();
                    }else{
                        oneAlert("error","Error!!!",res.data);
                    }
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#renewagreementFormButton").attr('disabled',false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        }
    });
    $('#AGRWatchPaymentType_IHZN').on('change', function (e){
        value=$(this).val();
        if(value==0){
            $("#AGRElectricRental_IIZN").prop("readonly",true);
        }else{
            $("#AGRElectricRental_IIZN").prop("readonly",false);
        }
        $("#AGRElectricRental_IIZN").val(0);
    });
    $('#AGRWatchPaymentType_UHRN').on('change', function (e){
        value=$(this).val();
        if(value==0){
            $("#AGRElectricRental_UIRN").prop("readonly",true);
        }else{
            $("#AGRElectricRental_UIRN").prop("readonly",false);
        }
        $("#AGRElectricRental_UIRN").val(0);
    });
    $("#future_send").on("click",()=>{
        window.open(`pdf/agreementFuture.php?agrid=${$("#future_agrid").val()}&type=${$("#future_type").val()}&date=${$("#future_date").val()}`);
    });
    addingExtenton();
    table = $("#datatableagreementView").DataTable({
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
            "url": "models/_agreement.php",
            "data": function (d) {
                d.type = "load";
            }
        },
        drawCallback: function () {
            tooltip1("");
        },
        "columnDefs": [
            {
                "targets": 9,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                        {
                            "AGRType":row[9],
                            "totalPaid":row[10]
                        },
                        {
                            "AGRID":row[0],
                            "title":row[4]
                        },
                        "table"
                    );
                }
            },{
                targets:[0,7,8,10,11],
                visible:false
            },{
                targets:"_all",
                className:"text-center"
            }

        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if(Number(aData[9])==1){
                $(nRow).prop('style',"background-color:#9af9bc;");
            }else if(Number(aData[9])==2){
                $(nRow).prop('style',"background-color:#f99a9a;");
            }
        },
        "order": [
            [11, "desc"]
        ],
        "displayLength": 25,
        initComplete: function () {
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatableagreementView");
    generalConfig();
});
returnShopNumberID("#AGRSOPFORID_IHZN"," SOPType <>1 AND ");
returnShopNumberID("#AGRSOPFORID_UGRN"," SOPType <>1 AND ");
returnCustomerID("#AGRCUSFORID_IHZN");
returnCustomerID("#AGRCUSFORID_UGRN");

function deleteAgreement(AGRID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_agreement.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "AGRID_UIZP": AGRID,
                        "AGRDeleted_UIZN":1
                    },
                    complete: function () {
                        //oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                       // oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatableagreementView").DataTable().ajax.reload(null, false);
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        } else {
                            setTimeout(function(){
                                oneAlert("error","Error!!!",res.data);
                            },500);
                        }
                    }
                }); 
            }
        }
    );
}
function editAgreement(AGRID) {
    $("#AGRID_UIZP").val(Number(AGRID));
    getDataFromServer(
        "editagreementForm",
        ["'agreement'","'shop'","'customer'"],
        "_general/image/agreement/",
        []
    );   
    setTimeout(function(){
        splitDuration=$("#AGRDuration_USRN").val().split(",");
        $("#EMPYearU").val(splitDuration[0]).trigger("change");
        $("#EMPMonthU").val(splitDuration[1]).trigger("change");
    },3000);
    $("#editagreementModal").modal("toggle");
} 
function rentAgreement(AGRID,title){
    window.location.href=`index.php?p=agreementrent&agrid=${AGRID}&title=${title}`;
}
function paymentAgreement(AGRID,title){
    window.location.href=`index.php?p=payment&agrid=${AGRID}&title=${title}`;
}
function dateValidation(startDate,startPayment,year,month){
    if(startPayment.split("-").length!=2){
        return {
            is_success:false,
            data:"Please Insert Paymnet Date"
        }
    }
    splitstartDate=startDate.split("-");
    EndDate=new Date(splitstartDate[0],splitstartDate[1]-1,splitstartDate[2]);
    EndDate.setMonth(EndDate.getMonth()+month);
    EndDate.setFullYear(EndDate.getFullYear()+year);

    EndDate=EndDate.getFullYear()+"-"+(EndDate.getMonth()+1)+"-"+EndDate.getDate();
    startPayment=startPayment+"-"+splitstartDate[2];

    if(startDate>startPayment || startDate>=EndDate || startPayment>=EndDate){
        return {
            is_success:false,
            data:"Must Duration Of Agreement at least one month and Payment date between start Date and end Date"
        }
    }
    return {
        is_success:true,
        data:""
    };
}
function renewAgreement(AGRID){
    $("#AGRID_RIZP").val(Number(AGRID));
    getDataFromServer(
        "renewagreementForm",
        ["'agreement'"]
    );   
    setTimeout(function(){
        splitDuration=$("#AGRDuration_USR").val().split(",");
        $("#EMPYearN").val(splitDuration[0]).trigger("change");
        $("#EMPMonthN").val(splitDuration[1]).trigger("change");
    },1000);
    $("#renewagreementModal").modal("toggle");
}
function showInvoice(AGRID){
    window.open(`pdf/agreement.php?agrid=${AGRID}`);
}
function showFutureInvoice(AGRID){
    $("#future_agrid").val(AGRID);
    $("#future_modal").modal("toggle");
}
function gotoCustomer(){
    window.open(`index.php?p=customer`);
}
function gotoShop(){
    window.open(`index.php?p=shop`);
}
function endAgreement(AGRID){
    swal(
        secondAlert("warning","Are you sure to finish agreement?","After finishing agreement new rent not created and shop empty again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_agreement.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"finish",
                        "PageName":$("#PageName").val(),
                        "AGRID_UIZP": AGRID,
                        "AGRType_UIZN":2
                    },
                    complete: function () {
                        //oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                       // oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatableagreementView").DataTable().ajax.reload(null, false);
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        } else {
                            setTimeout(function(){
                                oneAlert("error","Error!!!",res.data);
                            },500);
                        }
                    }
                }); 
            }
        }
    );
}