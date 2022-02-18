var insert_payment=[],edit_payment=[],allData_edit_payment=[];
$(document).ready(function () {
    totalDebt();
    $("#addpaymentForm").on("submit", function (e) {
        e.preventDefault();
        if($("#PNTTotalMoney_IIZN").val()>0 && Number($("#PNTExtraIQD_IIZN").val())>=Number($("#PNTTotalIQD_IIZN").val())){
            $("#savepaymentFormCollapse").attr("disabled", true);       
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("PNTNote_IAZN",CKEDITOR.instances["PNTNote_IAZN"].getData());
            formData.append("type","create");
            formData.append("tableInfo",JSON.stringify(insert_payment));
            formData.append("AGRID",$("#AGRID").val());
            $.ajax({
                url: "models/_payment.php",
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
                        location.reload();
                    }else{
                        oneAlert("error","Error!!!",res.data)
                    }
                    $("#savepaymentFormCollapse").attr("disabled",false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#savepaymentFormCollapse").attr("disabled",false);
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
    $("#editpaymentForm").on("submit", function (e) {
        e.preventDefault();
        if($("#PNTTotalMoney_UIRN").val()>0 && Number($("#PNTExtraIQD_UIRN").val())>=Number($("#PNTTotalIQD_UIRN").val())){
            $("#savepaymentFormModal").attr("disabled", true);
            
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("tableInfoEditUpdate",JSON.stringify(edit_payment));
            formData.append("type","update");
            formData.append("PNTNote_UARN",CKEDITOR.instances["PNTNote_UARN"].getData());
            formData.append("AGRID",$("#AGRID").val());
            
            $.ajax({
                url: "models/_payment.php",
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
                        location.reload();
                    }else{
                    oneAlert("error","Error!!!",res.data);
                    }
                    $("#savepaymentFormModal").attr("disabled",false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#savepaymentFormModal").attr("disabled",false);
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
    table = $("#datatablepaymentView").DataTable({
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
            "url": "models/_payment.php",
            "data": function (d) {
                d.type = "load";
                d.AGRID = $("#AGRID").val();
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
                        {},{
                            "PNTID":row[0],
                            "PNTTotalMoney":row[1],
                            "edit_last":row[5],
                            
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
            $("div.datatable-header").append(`<span class="label label-flat border-warning text-slate-800" style="padding-left:10px;padding-right:10px;font-size:20px;">${
            $("#AGRTitle").val()}</span>`);
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatablepaymentView");
    generalConfig(); 
    $("#PNTTotalUSD_IIZN,#PNTUSDRate_IIZN").on("change keyup",function(event){
        paidIQD=Number($("#PNTTotalMoney_IIZN").val())-Number($("#PNTTotalUSD_IIZN").val());
        $("#PNTTotalIQD_IIZN").val(paidIQD*Number($("#PNTUSDRate_IIZN").val()));
        $("#PNTExtraIQD_IIZN").val(paidIQD*Number($("#PNTUSDRate_IIZN").val()));
    });
    $("#PNTTotalUSD_UIRN,#PNTUSDRate_UIRN").on("change keyup",function(event){
        paidIQD=Number($("#PNTTotalMoney_UIRN").val())-Number($("#PNTTotalUSD_UIRN").val());
        $("#PNTTotalIQD_UIRN").val(paidIQD*Number($("#PNTUSDRate_UIRN").val()));
        $("#PNTExtraIQD_UIRN").val(paidIQD*Number($("#PNTUSDRate_UIRN").val()));
    });
    $('.multiselect').multiselect({
        nonSelectedText: 'Please choose',
        maxHeight: 250,
        selectedClass: null,
        numberDisplayed: 2,
        enableFiltering: true,
        templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
        },
        onChange: function(element, checked) {
            agreement_id=Number($(element).val());
            id=element.parent().prop("id")
           if(id=="insert_payment_detail"){
                if(checked){
                    rows=found_inside_array(agreement_id);
                    money=addToTable("#paymentRentTable",[rows]);
                    $("#PNTTotalMoney_IIZN").val(Number(money)+Number($("#PNTTotalMoney_IIZN").val()));
                    $("#PNTTotalUSD_IIZN").val(Number($("#PNTTotalMoney_IIZN").val()));
                    $("#PNTTotalIQD_IIZN").val(0);
                    $("#PNTExtraIQD_IIZN").val(0);
                }else{
                    money=removeFromTable(agreement_id);
                    $("#PNTTotalMoney_IIZN").val(Number($("#PNTTotalMoney_IIZN").val())-Number(money));
                    $("#PNTTotalUSD_IIZN").val(Number($("#PNTTotalMoney_IIZN").val()));
                    $("#PNTTotalIQD_IIZN").val(0);
                    $("#PNTTotalIQD_IIZN").val(0);
                }
           }else if(id=="edit_payment_detail"){
                if(checked){
                    rows=found_inside_array(agreement_id,"edit");
                    money=addToTable("#paymentRentTableEdit",[rows],"edit");
                    $("#PNTTotalMoney_UIRN").val(Number(money)+Number($("#PNTTotalMoney_UIRN").val()));
                    $("#PNTTotalUSD_UIRN").val(Number($("#PNTTotalMoney_UIRN").val()));
                    $("#PNTTotalIQD_UIRN").val(0);
                    $("#PNTExtraIQD_UIRN").val(0);
                }else{
                    money=removeFromTable(agreement_id,"edit");
                    $("#PNTTotalMoney_UIRN").val(Number($("#PNTTotalMoney_UIRN").val())-Number(money));
                    $("#PNTTotalUSD_UIRN").val(Number($("#PNTTotalMoney_UIRN").val()));
                    $("#PNTTotalIQD_UIRN").val(0);
                    $("#PNTExtraIQD_UIRN").val(0);
                }
           }
        },
    });
    $(".multiselect-container input").uniform({ radioClass: 'choice'});
});

function totalDebt() {
    $.ajax({
        url: "models/_payment.php",
        type: "POST",
        dataType: "JSON",
        data:{
            "type":"totalDebt",
            "AGRID":$("#AGRID").val()
        },
        complete: function () {},
        beforeSend: function () {},
        success: function (res) {
            $("#totalDebt").val(res.data);    
            $("#totalDebtU").val(res.data);    

        }
    }); 
}
function editpayment(PNTID,PNTTotalMoney,edit_last) {
    $("#paymentRentTableEdit").empty();
    totalDebt();
    $("#PNTID_UIZP").val(Number(PNTID));
    $("#oldTotal").val(Number(PNTTotalMoney));
    getDataFromServer("editpaymentForm","'rent_payment'","'capital'");  
    setTimeout(function(){
        $("#oldPNTPaidType").val($("#PNTPaidType_UHRN").val());
        getEditPaymentDetail(PNTID,PNTTotalMoney);
    },1000);
    $("#editpaymentModal").modal("toggle");
}  
function getEditPaymentDetail(PNTID,PNTTotalMoney){
    if(Number(PNTTotalMoney)>0){
        $.ajax({
            url: "models/_payment.php",
            type: "POST",
            dataType:"json",
            data: {
                "type":"getPaymentDetail",
                "paidMoney":PNTTotalMoney,
                "AGRID":$("#AGRID").val(),
                "PNTID":PNTID
            },
            complete: function () {
                oneCloseLoader("#"+$(this).parent().id,"self");
            },
            beforeSend: function () {
                oneOpenLoader("#"+$(this).parent().id,"self","dark");
            },
            success: function (res) {
                allData_edit_payment=[
                    ...res.data,
                    ...JSON.parse($("#rent_detail").val())
                ]
                console.log(allData_edit_payment);
                new_options=``;
                for (let i = 0,iL=allData_edit_payment.length; i < iL; i++) {
                    type="Rent";
                    if(allData_edit_payment[i]["ARTRentType"]==1){
                        type="Service";
                    }else if(allData_edit_payment[i]["ARTRentType"]==2){
                        type="Electric";
                    }
                    if(Number(allData_edit_payment[i]["RPDID"])>0){
                        new_options+=`<option value="${allData_edit_payment[i]["ARTID"]}" selected="selected">${allData_edit_payment[i]["ARTDate"]+"( "+allData_edit_payment[i]["ARTDate"]+" )( "+type+" )"}</option>`;
                        
                    }else{
                        new_options+=`<option value="${allData_edit_payment[i]["ARTID"]}" >${allData_edit_payment[i]["ARTDate"]+"( "+allData_edit_payment[i]["ARTDate"]+" )( "+type+" )"}</option>`;
                    }
                }
                $('#edit_payment_detail').html(''); 
                $("#edit_payment_detail").append(new_options);
                $("#edit_payment_detail").multiselect('rebuild');
                $(".multiselect-container input").uniform({ radioClass: 'choice'});

                for (let i = 0,iL=res.data.length; i < iL; i++) {
                    addToTable("#paymentRentTableEdit",[res.data[i]],"edit");                    
                }
                
 
            },
            fail: function (err){
                oneAlert("error","Error!!!","Found Error Please Conect System Creater !!!")
            },
            always:function(){
                console.log("complete");
            }
        });
    }
}
function showInvoice(PNTID){
    window.open(`pdf/payment.php?pntid=${PNTID}`);
}
function addToTable(id,res,type="insert"){
    if(type=="insert"){
        insert_payment.push(res[0]);
    }else{
        edit_payment.push(res[0]);
    }
    resLen=res.length;
    for(i=0;i<resLen;i++){
        if(res[i]["type"]==3){
            continue;
        }
        ARTRentType="";
        if(res[i]["ARTRentType"]==0){
            ARTRentType="Area";
        }else if(res[i]["ARTRentType"]==1){
            ARTRentType="Service";
        }else if(res[i]["ARTRentType"]==2){
            ARTRentType="Electric";
        }
        $(id).append(`
            <tr id="row_${res[i]["ARTID"]}">
                <td>${res[i]["ARTID"]}</td>
                <td>${res[i]["ARTDate"]}</td>
                <td>${ARTRentType}</td>
                <td>${Number(res[i]["paidBasic"])}</td>
            </tr>
        `);
    }
    return res[0]["paidBasic"];
}
function removeFromTable(id,type="insert"){
    var money=0;
    if(type=="insert"){
        $("#paymentRentTable tr").each(function (index,value) {
            if(Number($(value).prop("id").split("_")[1])==id){
                $(value).remove();
                for (let i = 0,iL=insert_payment.length; i < iL; i++) {
                    if(Number(insert_payment[i]["ARTID"])==id){
                        money=insert_payment[i]["paidBasic"];
                        insert_payment.splice(i,1);
                        break;
                    }
                }
            }
        });
    }else{
        $("#paymentRentTableEdit tr").each(function (index,value) {
            if(Number($(value).prop("id").split("_")[1])==id){
                $(value).remove();
                for (let i = 0,iL=edit_payment.length; i < iL; i++) {
                    if(Number(edit_payment[i]["ARTID"])==id){
                        money=edit_payment[i]["paidBasic"];
                        edit_payment.splice(i,1);
                        break;
                    }
                }
            }
        });
    }
    return money;
}
function gotoAgreementRent(){
    window.open(`index.php?p=agreementrent&agrid=${$("#AGRID").val()}&title=${$("#AGRTitle").val()}`);
}
function gotoAgreement(){
    window.open(`index.php?p=agreement`);
}
function found_inside_array(id,type="insert"){
    if(type=="insert"){
        data=JSON.parse($("#rent_detail").val());
    }else{
        data=allData_edit_payment;
    }
    for (let i = 0,iL=data.length; i < iL; i++) {
        if(Number(data[i]["ARTID"])==id){
            return data[i];
        }
    }
    return [];
}
function deletepayment(PNTID,PNTTotalMoney) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_payment.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "PNTID_UIZP": PNTID,
                        "PNTDeleted_UIZ":1,
                        "paidMoney":PNTTotalMoney,
                        "AGRID":$("#AGRID").val()
                    },
                    complete: function () {},
                    beforeSend: function () {},
                    success: function (res) {
                        if (res.is_success == true) {
                            location.reload();
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
    totalDebt();
}