$(document).ready(function () {
    $("#addreturnadvanceForm").on("submit", function (e) {
        e.preventDefault();
        $("#savereturnadvanceFormCollapse").attr("disabled", true);       
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("CPTFORID_IIZN",$("#ADVID").val());
        formData.append("CPTMoneyType_IIZN",JSON.parse($("#need_data").val())["ADVMoneyType"]);
        formData.append("CPTNote_IAZN",CKEDITOR.instances["CPTNote_IAZN"].getData());
        formData.append("type","create");
        $.ajax({
            url: "models/_returnadvance.php",
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
                    //let need_data=JSON.parse($("#need_data").val());
                    //$("#CPTMoney_IIZN").prop("max",((Number(need_data["ADVMoney"])-Number(need_data["return_total"]))+Number($("#CPTMoney_IIZN").val())));
                    //$("#addreturnadvanceForm")[0].reset();
                    //CKEDITOR.instances["CPTNote_IAZN"].setData(""); 
                    //$("#datatablereturnadvanceView").DataTable().ajax.reload(null, false);
                    $("#addreturnadvanceCollapse").collapse("hide");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    setTimeout(function(){
                        location.reload();
                    },200);
                }else{
                    oneAlert("error","Error!!!",res.data)
                }
                $("#savereturnadvanceFormCollapse").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#savereturnadvanceFormCollapse").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editreturnadvanceForm").on("submit", function (e) {
        e.preventDefault();
        $("#savereturnadvanceFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("CPTMoneyType_IIZN",JSON.parse($("#need_data").val())["ADVMoneyType"]);
        formData.append("type","update");
        formData.append("CPTNote_UARN",CKEDITOR.instances["CPTNote_UARN"].getData());
        $.ajax({
            url: "models/_returnadvance.php",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType: "json",
            complete: function () {
                oneCloseLoader("#"+$(this).parent().id,"self");
            },
            beforeSend: function () {
                oneOpenLoader("#"+$(this).parent().id,"self","dark");
            },
            success: function (res) {
                if(res.is_success == true){
                    //let need_data=JSON.parse($("#need_data").val());
                    //$("#CPTMoney_UIRN").prop("max",((Number(need_data["ADVMoney"])-Number(need_data["return_total"]))+Number($("#CPTMoney_UIRN").val())));
                    //$("#editreturnadvanceForm")[0].reset();
                    //CKEDITOR.instances["CPTNote_UARN"].setData(""); 
                    //$("#datatablereturnadvanceView").DataTable().ajax.reload(null, false);
                    $("#editreturnadvanceModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    setTimeout(function(){
                        location.reload();
                    },200);
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#savereturnadvanceFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#savereturnadvanceFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatablereturnadvanceView").DataTable({
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
            "url": "models/_returnadvance.php",
            "data": function (d) {
                d.type = "load";
                d.id = Number($("#ADVID").val());
            }
        },
        drawCallback: function () {
            tooltip1("");
        },
        "columnDefs": [
            {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                        {},
                        {
                            "RDVID":row[0]
                        },
                        "table"
                    );
                }
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        },
        "order": [
            [0, "desc"]
        ],
        "displayLength": 25,
        initComplete: function () {
            let need_data=JSON.parse($("#need_data").val());
            $("div.datatable-header").append(`<span class="label label-flat border-warning text-slate-800" style="padding-left:10px;padding-right:10px;font-size:20px;">${
                need_data["ADVForPerson"]+" ( "+(Number(need_data["ADVMoney"])-Number(need_data["return_total"]))+" - "+(need_data["ADVMoneyType"]==0?"USD":"IQD")+" ) "
            }</span>`);
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatablereturnadvanceView");
    generalConfig(); 
});
function deletereturnadvance(CPTID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_returnadvance.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "CPTID_UIZP": CPTID,
                        "CPTDeleted_UIZN":1
                    },
                    complete: function () {
                        //oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                       // oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            //$("#datatablereturnadvanceView").DataTable().ajax.reload(null, false);
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                            setTimeout(function(){
                                location.reload();
                            },200);
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
function editreturnadvance(CPTID) {
    $("#CPTID_UIZP").val(Number(CPTID));
    getDataFromServer("editreturnadvanceForm","'capital'"); 
    setTimeout(function(){
        let need_data=JSON.parse($("#need_data").val());
        $("#CPTMoney_UIRN").prop("max",((Number(need_data["ADVMoney"])-Number(need_data["return_total"]))+Number($("#CPTMoney_UIRN").val())));
    },1000) 
    $("#editreturnadvanceModal").modal("toggle");
}  