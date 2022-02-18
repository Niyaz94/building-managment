$(document).ready(function () {
    $("#addwatchForm").on("submit", function (e) {
        e.preventDefault();                            
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","create");
        $("#submit_add_btn").attr("disabled",true);
        $.ajax({
            url: "models/_watch.php",
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
                    $("#addwatchForm")[0].reset();
                    $("#WTCSOPFORID_IHZN").empty().trigger('change')
                    $("#datatablewatchView").DataTable().ajax.reload(null, false);
                    $("#addwatchCollapse").collapse("hide");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
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
    });
    $("#editwatchForm").on("submit", function (e) {
        e.preventDefault();
        $("#savewatchFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        $.ajax({
            url: "models/_watch.php",
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
                    $("#editwatchForm")[0].reset();
                    $("#datatablewatchView").DataTable().ajax.reload(null, false);
                    $("#editwatchModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#savewatchFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#savewatchFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatablewatchView").DataTable({
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
                    "url": "models/_watch.php",
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
                                    "SOPType":row[3],
                                    "AGRWatchPaymentType":row[5]
                                },
                                {
                                    "WTCID":row[0],
                                    "WTCSOPFORID":row[4]
                                },
                                "table"
                            );
                        }
                    },{
                        targets:[4,5],
                        visible:false
                    },{
                        "targets": 2,
                        "render": function (data, type, row) {
                            if(data==0){
                                return `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">watch</span>`;
                            }else{
                                return data;
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
    generalConfigDatatable(table,"#datatablewatchView");
    generalConfig(); 
    returnShopNumberID("#WTCSOPFORID_IHZN","");
    returnShopNumberID("#WTCSOPFORID_UGRN","");
});
function deleteWatch(WTCID) {
    deletedRow("#datatablewatchView",{
        "PageName":$("#PageName").val(),
        "WTCID_UIZP": WTCID,
        "WTCDeleted_UIZ":1,
        "table":"watch",
        "symbol":"WTC"
    });
}
function editWatch(WTCID) {
    $("#WTCID_UIZP").val(Number(WTCID));
    getDataFromServer("editwatchForm",["'watch'","'shop'"]);  
    $("#editwatchModal").modal("toggle");
} 
function paymentWatch(WTCID,SOPID){
    window.location.href=`index.php?p=watchrent&watchid=${WTCID}&shopid=${SOPID}`;
}