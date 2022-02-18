
$(document).ready(function () {
    $("#add_form").on("submit", function (e) {
        e.preventDefault(); 
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("CUSNote_IAZN",CKEDITOR.instances['CUSNote_IAZN'].getData());
        formData.append("type","create");
        $("#submit_add_btn").attr("disabled",true);
        $.ajax({
            url: "models/_customer.php",
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
                    $("#add_form")[0].reset();
                    CKEDITOR.instances['CUSNote_IAZN'].setData(""); 
                    $("#datatableCustomerView").DataTable().ajax.reload(null, false);
                    $("#addcustomerCollapse").collapse("hide");
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
    $('#editCustomerForm').on('submit', function (e) {
        e.preventDefault();
        $("#editCustomerFormButton").attr('disabled', true);
        $(`#editCustomerForm .imgContainer`).each(function(index,data){
            splitID=$(this).prop("id").split("-")[1];
            if($("#name-"+splitID).text().length==0){
                $("#"+splitID).attr("name",splitID.substr(0,splitID.length-1)+"E");
            }
        });
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        formData.append("CUSNote_UARN",CKEDITOR.instances['CUSNote_UARN'].getData());

        $.ajax({
            url: "models/_customer.php",
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
                    $("#editCustomerForm")[0].reset();
                    CKEDITOR.instances['CUSNote_UARN'].setData(""); 
                    $("#datatableCustomerView").DataTable().ajax.reload(null, false);
                    $("#editCustomerModal").modal('toggle');
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#editCustomerFormButton").attr('disabled',false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#editCustomerFormButton").attr('disabled',false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatableCustomerView").DataTable({
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
                    "url": "models/_customer.php",
                    "data": function (d) {
                        d.type = "load";
                    }
                },
                drawCallback: function () {
                    tooltip1("");
                },
                "columnDefs": [
                    {
                        "targets": 5,
                        "data": null,
                        "render": function (data, type, row) {
                            return returnTablButtons(
                                JSON.parse($("#pageInfo").val()),
                                JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                                {
                                    "totalagreement":row[5]
                                },
                                {
                                    "CUSID":row[0]
                                },
                                "table"
                            );
                        }
                    }/*,{
                        "targets": 4,
                        "render": function (data, type, row) {
                            if(data==0){
                                return `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">Shop</span>`;
                            }else if(data==1){  
                                return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Pergola</span>`;
                            }else if(data==2){
                                return `<span class="label label-block label-flat border-brown text-slate-800" style="padding:6%">Office</span>`;
                            }
                        }
                    },{
                        "targets": 5,
                        "render": function (data, type, row) {
                            if(data==0){
                                return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">Empty</span>`;
                            }else if(data==1){  
                                return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Full</span>`;
                            }else if(data==2){
                                return `<span class="label label-block label-flat border-warning text-slate-800" style="padding:6%">Full Before</span>`;
                            }
                        }
                    }*/

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
    generalConfigDatatable(table,"#datatableCustomerView");
    generalConfig();
});
function gotoAgreement(){
    window.open(`index.php?p=agreement`);
}
function gotoShop(){
    window.open(`index.php?p=shop`);
}
function deleteCustomer(CUSID) {
    deletedRow("#datatableCustomerView",{
        "PageName":$("#PageName").val(),
        "CUSID_UIZP": CUSID,
        "CUSDeleted_UIZ":1,
        "table":"customer",
        "symbol":"CUS"
    });
}
function editCustomer(CUSID) {
    $("#CUSID_UIZP").val(Number(CUSID));
    getDataFromServer("editCustomerForm","'customer'","_general/image/customer/");  
    $("#editCustomerModal").modal("toggle");
}