
$(document).ready(function () {
    $("#editexpensesgroupForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveexpensesgroupFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("EXGNote_UARN",CKEDITOR.instances['EXGNote_UARN'].getData());
        formData.append("type","update");
        $.ajax({
            url: "models/_expensesgroup.php",
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
                    $("#editexpensesgroupForm")[0].reset();
                    CKEDITOR.instances['EXGNote_UARN'].setData(""); 
                    $("#datatableexpensesgroupView").DataTable().ajax.reload(null, false);
                    $("#editexpensesgroupModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#saveexpensesgroupFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveexpensesgroupFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatableexpensesgroupView").DataTable({
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
            "url": "models/_expensesgroup.php",
            "data": function (d) {
                d.type = "load";
            }
        },
        drawCallback: function () {
            tooltip1("");
        },
        "columnDefs": [
            {
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                        {
                            /*["EXGID","gr",2]*/
                            /*"EXGID":row[0]*/
                        },{
                            "EXGID":row[0]
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
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatableexpensesgroupView");
    generalConfig(); 
});
function deleteexpensesgroup(EXGID) {
    deletedRow("#datatableexpensesgroupView",{
        "PageName":$("#PageName").val(),
        "EXGID_UIZP": EXGID,
        "EXGDeleted_UIZN":1,
        "table":"expenses_group",
        "symbol":"EXG"
    });
}
function editexpensesgroup(EXGID) {
    $("#EXGID_UIZP").val(Number(EXGID));
    getDataFromServer("editexpensesgroupForm","'expenses_group'");  
    $("#editexpensesgroupModal").modal("toggle");
}  