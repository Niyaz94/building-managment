$(document).ready(function () {
    $("#addcapitalForm").on("submit", function (e) {
        e.preventDefault();
        if($("#CPTMoney_IIZN").val()>0){
            $("#savecapitalFormCollapse").attr("disabled", true);       
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("CPTNote_IAZN",CKEDITOR.instances["CPTNote_IAZN"].getData());
            formData.append("type","create");
            $.ajax({
                url: "models/_capital.php",
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
                        $("#addcapitalForm")[0].reset();
                        CKEDITOR.instances['CPTNote_IAZN'].setData(""); 
                        $("#datatablecapitalView").DataTable().ajax.reload(null, false);
                        $("#addcapitalCollapse").collapse("hide");
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                        oneAlert("error","Error!!!",res.data)
                    }
                    $("#savecapitalFormCollapse").attr("disabled",false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#savecapitalFormCollapse").attr("disabled",false);
                },
                always:function(){
                    console.log("complete");
                }
            });
            totalDebt();
        }else{
            oneAlert("error","Error!!!","The Money Must Be Greater Than 0");
        }
    });
    $("#editcapitalForm").on("submit", function (e) {
        e.preventDefault();
        if($("#CPTMoney_UIRN").val()>0){
            $("#savecapitalFormModal").attr("disabled", true);
            
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","update");
            formData.append("CPTNote_UARN",CKEDITOR.instances["CPTNote_UARN"].getData());            
            $.ajax({
                url: "models/_capital.php",
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
                        $("#editcapitalForm")[0].reset();
                        CKEDITOR.instances["CPTNote_UARN"].setData(""); 
                        $("#datatablecapitalView").DataTable().ajax.reload(null, false);
                        $("#editcapitalModal").modal("toggle");
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                    oneAlert("error","Error!!!",res.data);
                    }
                    $("#savecapitalFormModal").attr("disabled",false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#savecapitalFormModal").attr("disabled",false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        }else{
            oneAlert("error","Error!!!","The Money Must Be Greater Than 0");
        }
    });
    addingExtenton();
    table = $("#datatablecapitalView").DataTable({
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
            "url": "models/_capital.php",
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
                            "extra_payment":row[6]
                        },
                        {
                            "CPTID":row[0],
                            "CPTMoney":row[2],
                            "CPTMoneyType":row[3]
                        },
                        "table"
                    );
                }
            },{
                "targets": 3,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-primary text-slate-800" style="padding:6%">USD</span>`;
                    }else if(data==1){
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">IQD</span>`;
                    }
                }
            },{
                "targets": 5,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-warning  text-slate-800" style="padding:6%">Cash</span>`;
                    }else if(data==1){
                        return `<span class="label label-block label-flat border-purple text-slate-800" style="padding:6%">Bank</span>`;
                    }else if(data==2){
                        return `<span class="label label-block label-flat border-default text-slate-800" style="padding:6%">Check</span>`;
                    }
                }
            },{
                "targets": 1,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-brown text-slate-800" style="padding:6%">Push Money</span>`;
                    }else{
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Pull Money</span>`;
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
    generalConfigDatatable(table,"#datatablecapitalView");
    generalConfig(); 
});
function edit_payment(CPTID) {
    $("#CPTID_UIZP").val(Number(CPTID));
    getDataFromServer("editcapitalForm",["'capital'"]);  
    $("#editcapitalModal").modal("toggle");
}
function delete_payment(CPTID,CPTMoney,CPTMoneyType) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_capital.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "CPTID_UIZP": CPTID,
                        "CPTMoneyType_UIZN": CPTMoneyType,
                        "CPTMoney_UIZN": CPTMoney,
                        "CPTDeleted_UIZN":1
                    },
                    complete: function () {},
                    beforeSend: function () {},
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatablecapitalView").DataTable().ajax.reload(null, false);
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