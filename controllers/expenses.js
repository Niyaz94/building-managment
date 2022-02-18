$(document).ready(function () {
    $("#addexpensesForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveexpensesFormCollapse").attr("disabled", true);       
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("EXPNote_IAZN",CKEDITOR.instances["EXPNote_IAZN"].getData());
        formData.append("type","create");
        $.ajax({
            url: "models/_expenses.php",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType:"json",
            complete: function () {
                oneCloseLoader("#"+$(this).parent().id,"self");
            },
            beforeSend: function () {
                oneOpenLoader("#"+$(this).parent().id,"self","dark");
            },
            success: function (res) {
                if(res.is_success == true){
                    $("#addexpensesForm")[0].reset();
                    CKEDITOR.instances["EXPNote_IAZN"].setData(""); 
                    $("#datatableexpensesView").DataTable().ajax.reload(null, false);
                    $("#addexpensesCollapse").collapse("hide");
                    deselectSelect2();
                    $("#EXPEXGFORID_IHZN").empty().trigger('change')
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                    oneAlert("error","Error!!!",res.data)
                }
                $("#saveexpensesFormCollapse").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveexpensesFormCollapse").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editexpensesForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveexpensesFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        formData.append("EXPNote_UARN",CKEDITOR.instances["EXPNote_UARN"].getData());
        $.ajax({
            url: "models/_expenses.php",
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
                    $("#editexpensesForm")[0].reset();
                    CKEDITOR.instances["EXPNote_UARN"].setData(""); 
                    $("#datatableexpensesView").DataTable().ajax.reload(null, false);
                    $("#editexpensesModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#saveexpensesFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveexpensesFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatableexpensesView").DataTable({
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
            "url": "models/_expenses.php",
            "data": function (d) {
                d.type = "load";
            }
        },
        drawCallback: function () {
            tooltip1("");
        },
        "columnDefs": [
            {
                "targets": 7,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                        {},
                        {
                            "EXPID":row[0]
                        },
                        "table"
                    );
                }
            },{
                "targets": 5,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">USD</span>`;
                    }else if(data==1){  
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">IQD</span>`;
                    }
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
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatableexpensesView");
    generalConfig();
    $(".buttonEXPEXGFORID").on("click",function(event){
        $("#insertexpensesgroupModal").modal("toggle");
    });
});
returnExpensesCategory("#EXPEXGFORID_IHZN"); 
returnExpensesCategory("#EXPEXGFORID_UGRN"); 
function deleteexpenses(EXPID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_expenses.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "EXPID_UIZP": EXPID,
                        "EXPDeleted_UIZ":1
                    },
                    complete: function () {
                        //oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                       // oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatableexpensesView").DataTable().ajax.reload(null, false);
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
function editexpenses(EXPID) {
    $("#EXPID_UIZP").val(Number(EXPID));
    getDataFromServer("editexpensesForm",["'expenses'","'expenses_group'"]);  
    $("#editexpensesModal").modal("toggle");
}  
function showInvoice(EXPID){
    window.open(`pdf/expenses.php?expid=${EXPID}`);
}
    