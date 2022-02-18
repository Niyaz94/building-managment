$(document).ready(function () {
    $("#editagreementrentForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveagreementrentFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        formData.append("ARTNote_UARN",CKEDITOR.instances["ARTNote_UARN"].getData());
        $.ajax({
            url: "models/_agreementrent.php",
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
                    $("#editagreementrentForm")[0].reset();
                    CKEDITOR.instances["ARTNote_UARN"].setData(""); 
                    $("#datatableagreementrentView").DataTable().ajax.reload(null, false);
                    $("#editagreementrentModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#saveagreementrentFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveagreementrentFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#futureRentForm").on("submit", function (e) {
        e.preventDefault();
        var future_month = [];
        $('#future_month option:selected').each(function() {
            future_month.push(Number($(this).text()));
        });
        var future_type = [];
        $('#future_type option:selected').each(function() {
            _value=$(this).text();
            if(_value=="Rent"){
                future_type.push(0);
            }else if(_value=="Service"){
                future_type.push(1);
            }else{
                future_type.push(2);
            }
        });
        console.log(future_type.length,future_month.length);
        if(future_type.length>0 && future_month.length>0){
            $("#saveFutureRentModal").attr("disabled", true);
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","futureInsert");
            formData.append("future_month",JSON.stringify(future_month));
            formData.append("future_type",JSON.stringify(future_type));
            formData.append("AGRID",$("#AGRID").val());
            $.ajax({
                url: "models/_agreementrent.php",
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
                        if(res.data>0){
                            oneAlert("warning","Warning!!!",`${res.data} months not adding because they already added before`)
                        }
                        $("#futureRentForm")[0].reset();
                        $("#datatableagreementrentView").DataTable().ajax.reload(null, false);
                        $("#futureRentModal").modal("toggle");
                        oneMessege("","The System Updated Successfully","success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                      oneAlert("error","Error!!!",res.data);
                    }
                    $("#saveFutureRentModal").attr("disabled",false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#saveFutureRentModal").attr("disabled",false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        }else{
            oneAlert("error","Error!!!","You have to select months and type.")
        }
        
    });
    addingExtenton();
    table = $("#datatableagreementrentView").DataTable({
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
            "url": "models/_agreementrent.php",
            "data": function (d) {
                d.type = "load";
                d.agrid = $("#AGRID").val();
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
                            "ARTPaidType":(Number(row[4])==0 && Number(row[3])!=0?0:row[5]),
                            "delete_type":row[6]
                        },
                        {
                            "ARTID":row[0],
                            "ARTDate":row[1],
                            "ARTType":row[2],
                            "ARTPaidType":row[5]
                        },
                        "table"
                    );
                }
            },{
                "targets": 2,
                "render": function (data, type, row) {
                    const split=row[1].split("-");
                    const date = new Date(split[0], split[1]-1, split[2]);
                    const month = date.toLocaleString('en-us', { month: 'long' });
                    if(data==0){
                        return `<span class="label label-block label-flat border-primary text-slate-800" style="padding:6%">Rent</span>`;
                    }else if(data==1){
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Service</span>`;
                    }else if(data==2){
                        return `<span class="label label-block label-flat border-warning text-slate-800" style="padding:6%">Electric</span>`;
                    }
                }
            },{
                "targets": 1,
                "render": function (data, type, row) {
                    const split=row[1].split("-");
                    const date = new Date(split[0], split[1]-1, split[2]);
                    const month = date.toLocaleString('en-us', { month: 'long' });
                    return month+" / "+split[0];
                }
            },{
                "targets": 5,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">Not Paid</span>`;
                    }else if(data==1){
                        return `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">Partial Paid</span>`;
                    }else if(data==2){
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Paid</span>`;
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
            $("div.datatable-header").append(`<span class="label label-flat border-warning text-slate-800" style="padding-left:10px;padding-right:10px;font-size:20px;">${
                $("#AGRTitle").val()}</span>`);
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatableagreementrentView");
    generalConfig();
    $('.multiselect').multiselect({
        nonSelectedText: 'Please choose',
        maxHeight: 250,
        selectedClass: null,
        numberDisplayed: 2,
        enableFiltering: true,
        templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
        },
        onChange: function(element, checked) {}
    });
    $(".multiselect-container input").uniform({ radioClass: 'choice'});
});
function gotoPayment(){
    window.open(`index.php?p=payment&agrid=${$("#AGRID").val()}&title=${$("#AGRTitle").val()}`);
}
function gotoAgreement(){
    window.open(`index.php?p=agreement`);
}
function editagreementrent(ARTID,ARTPaidType) {
    if(ARTPaidType==0){
        $(".ShowOrNot").show("toggle");
    }else if(ARTPaidType==1){
        $(".ShowOrNot").hide("toggle");
    }
    $("#ARTID_UIZP").val(Number(ARTID));
    getDataFromServer("editagreementrentForm","'agreementrent'"); 
    //setTimeout(()=>{
    //    $("#ARTRentD_UIRN").prop("max",Number($("#ARTRent_UIRA").val()));
    //},2000); 
    $("#editagreementrentModal").modal("toggle");
}
function gotoInvoice(ARTID,ARTDate,ARTType){
    console.log(ARTDate);
    window.open(`pdf/agreementrent.php?artid=${ARTID}&agrid=${$("#AGRID").val()}&ARTDate=${ARTDate}&ARTType=${ARTType}`);
}
function deleteagreementrent(ARTID){
    deletedRow("#datatableagreementrentView",{
        "PageName":$("#PageName").val(),
        "ARTID_UIZP": ARTID,
        "ARTDeleted_UIZ":1,
        "table":"agreementrent",
        "symbol":"ART"
    });
}
function addFutureRent(){
    $("#futureRentModal").modal("toggle");
}