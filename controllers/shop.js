var languageInfo=languageInfo(), table;
$(document).ready(function () {
    $("#add_form").on("submit", function (e) {
        e.preventDefault();                            
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("SOPNote_IAZN",CKEDITOR.instances['SOPNote_IAZN'].getData());
        
        formData.append("type","create");
        $("#submit_add_btn").attr("disabled",true);
        $.ajax({
                url: "models/_shop.php",
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
                        $("#add_form")[0].reset();
                        deselectSelect2();
                        CKEDITOR.instances['SOPNote_IAZN'].setData(""); 
                        $("#datatableShopView").DataTable().ajax.reload(null, false);
                        $("#addshopCollapse").collapse("hide");
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
    $('#editShopForm').on('submit', function (e) {
        e.preventDefault();
        $("#editShopFormButton").attr('disabled', true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        formData.append("SOPNote_UARN",CKEDITOR.instances['SOPNote_UARN'].getData());
        $.ajax({
            url: "models/_shop.php",
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
                    $("#editShopForm")[0].reset();
                    CKEDITOR.instances['SOPNote_UARN'].setData(""); 
                    $("#datatableShopView").DataTable().ajax.reload(null, false);
                    $("#editShopModal").modal('toggle');
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#editShopFormButton").attr('disabled',false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#editShopFormButton").attr('disabled',false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $('#datatableShopView').DataTable({
        buttons: {
            buttons: dtButtons()
        },
        lengthMenu: [
            [10, 25, 50, 100],
            ['10', '25', '50', '100']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "models/_shop.php",
            "data": function (d) {
                d.type = "load";
            }
        },
        drawCallback: function () {
            $('[data-popup=tooltip]').tooltip();
            $('[data-popup=popover-custom]').popover({
                template: '<div class="popover  border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
            });

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
                            "SOPType":row[5]
                        },{
                            "SOPID":row[0]
                        },
                        "table"
                    );
                }
            },{
                "targets": 4,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">Shop</span>`;
                    }else if(data==1){  
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Stand</span>`;
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
            },{
                "targets": 3,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Base Floor</span>`;
                    }else if(data==1){  
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Ground Floor</span>`;
                    }else if(data==2){  
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">First Floor</span>`;
                    }else if(data==3){
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Second Floor</span>`;
                    }else if(data==4){  
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Third Floor</span>`;
                    }else if(data==5){
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Fourth Floor</span>`;
                    }else if(data==6){  
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Fifth Floor</span>`;
                    }else if(data==7){
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Sixth Floor</span>`;
                    }else if(data==8){  
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Seventh Floor</span>`;
                    }else if(data==9){
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Eighth Floor</span>`;
                    }else if(data==10){  
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Ninth Floor</span>`;
                    }
                }
            }

        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        },
        "order": [
            [0, 'desc']
        ],
        "displayLength": 25,
        initComplete: function () {
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatableShopView");
    generalConfig(); 
    $(".numberClass").TouchSpin({
        verticalbuttons: true,
        verticalupclass: 'icon-arrow-up22',
        verticaldownclass: 'icon-arrow-down22'
    });
});
function gotoAgreement(){
    window.open(`index.php?p=agreement`);
}
function gotoCustomer(){
    window.open(`index.php?p=customer`);
}
function gotoWatch(){
    window.open(`index.php?p=watch`);
}
function deleteShop(SOPID) {
    deletedRow("#datatableShopView",{
        "PageName":$("#PageName").val(),
        "SOPID_UIZP": SOPID,
        "SOPDeleted_UIZN":1,
        "table":"shop",
        "symbol":"SOP"
    });
}
function editShop(SOPID) {
    $("#SOPID_UIZP").val(Number(SOPID));
    getDataFromServer("editShopForm","'shop'");  
    $('#editShopModal').modal('toggle');
}                    