$(document).ready(function () {
    $("#addadvanceForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveadvanceFormCollapse").attr("disabled", true);       
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("ADVNote_IAZN",CKEDITOR.instances["ADVNote_IAZN"].getData());
        formData.append("type","create");
        $.ajax({
            url: "models/_advance.php",
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
                    $("#addadvanceForm")[0].reset();
                    CKEDITOR.instances["ADVNote_IAZN"].setData(""); 
                    $("#datatableadvanceView").DataTable().ajax.reload(null, false);
                    $("#addadvanceCollapse").collapse("hide");
                    deselectSelect2();
                    $("#ADVEXGFORID_IHZN").empty().trigger('change')
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                    oneAlert("error","Error!!!",res.data)
                }
                $("#saveadvanceFormCollapse").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveadvanceFormCollapse").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editadvanceForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveadvanceFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        formData.append("ADVNote_UARN",CKEDITOR.instances["ADVNote_UARN"].getData());
        $.ajax({
            url: "models/_advance.php",
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
                    $("#editadvanceForm")[0].reset();
                    CKEDITOR.instances["ADVNote_UARN"].setData(""); 
                    $("#datatableadvanceView").DataTable().ajax.reload(null, false);
                    $("#editadvanceModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#saveadvanceFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveadvanceFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatableadvanceView").DataTable({
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
            "url": "models/_advance.php",
            "data": function (d) {
                d.type = "load";
            }
        },
        drawCallback: function () {
            tooltip1("");
        },
        "columnDefs": [
            {
                "targets": 6,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                        {
                            "total_return":row[5]
                        },
                        {
                            "ADVID":row[0]
                        },
                        "table"
                    );
                }
            },{
                "targets": 4,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">USD</span>`;
                    }else if(data==1){  
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">IQD</span>`;
                    }
                }
            },{
                "targets": 5,
                "render": function (data, type, row) {
                    return `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">${data}</span>`;
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
    generalConfigDatatable(table,"#datatableadvanceView");
    generalConfig();
});
function deleteadvance(ADVID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_advance.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "ADVID_UIZP": ADVID,
                        "ADVDeleted_UIZN":1
                    },
                    complete: function () {
                        //oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                       // oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatableadvanceView").DataTable().ajax.reload(null, false);
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
function editadvance(ADVID) {
    $("#ADVID_UIZP").val(Number(ADVID));
    getDataFromServer("editadvanceForm",["'advance'"]);  
    $("#editadvanceModal").modal("toggle");
} 
function gotoReturn(ADVID){
    window.location.href=`index.php?p=returnadvance&id=${ADVID}`;
}
    