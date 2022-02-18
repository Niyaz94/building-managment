$(document).ready(function () {
    $("#add_form").on("submit", function (e) {
        e.preventDefault();                            
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("EMPNote_IAZ",CKEDITOR.instances['EMPNote_IAZ'].getData());
        formData.append("type","create");
        $("#submit_add_btn").attr("disabled",true);
        $.ajax({
            url: "models/_employee.php",
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
                    $("#datatableEmployeeView").DataTable().ajax.reload(null, false);
                    $("#addemployeeCollapse").collapse("hide");
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
    $('#editEmployeeForm').on('submit', function (e) {
        e.preventDefault();
        $("#editEmployeeFormButton").attr('disabled', true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        formData.append("EMPNote_UARN",CKEDITOR.instances['EMPNote_UARN'].getData());
        $.ajax({
            url: "models/_employee.php",
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
                    $("#editEmployeeForm")[0].reset();
                    CKEDITOR.instances['EMPNote_UARN'].setData(""); 
                    $("#datatableEmployeeView").DataTable().ajax.reload(null, false);
                    $("#editEmployeeModal").modal('toggle');
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#editEmployeeFormButton").attr('disabled',false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#editEmployeeFormButton").attr('disabled',false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $('#editEmployeeSalaryForm').on('submit', function (e) {
        e.preventDefault();
        $("#editEmployeeSalaryFormButton").attr('disabled', true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","updateSalary");
        formData.append("PAYNote_BAR",CKEDITOR.instances['PAYNote_BAR'].getData());
        $.ajax({
            url: "models/_employee.php",
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
                    $("#editEmployeeSalaryForm")[0].reset();
                    CKEDITOR.instances['PAYNote_BAR'].setData(""); 
                    $("#datatableEmployeeView").DataTable().ajax.reload(null, false);
                    $("#editEmployeeSalaryModal").modal('toggle');
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#editEmployeeSalaryFormButton").attr('disabled',false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#editEmployeeSalaryFormButton").attr('disabled',false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatableEmployeeView").DataTable({
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
            "url": "models/_employee.php",
            "data": function (d) {
                d.type = "load";
            }
        },
        drawCallback: function () {
            $("[data-popup=tooltip]").tooltip();
            $("[data-popup=popover-custom]").popover({
                template: `<div class="popover  border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>`
            });
        },
        "columnDefs": [
            {
                "targets": 10,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                        {},
                        {
                            "EMPID":row[0],
                            "EMPSalary":row[8]
                        },
                        "table"
                    );
                }
            },{
                "targets": 9,
                "render": function (data, type, row) {
                    if(data==null){
                        return row[8];
                    }else{  
                        return data;
                    }
                }
            },{
                "targets": 5,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-primary text-slate-800" style="padding:6%">Male</span>`;
                    }else if(data==1){  
                        return `<span class="label label-block label-flat border-brown text-slate-800" style="padding:6%">Female</span>`;
                    }
                }
            },{
                "targets": 1,
                "render": function (data, type, row) {
                   return `
                        <span class="btn btn-file" style="box-shadow: 0 0 3px rgba(0, 0, 0, 0); background-color: rgba(245, 245, 245, 0);">
                            <a href="#" id="profileLink" class="profile-thumb">
                                <img src="_general/image/employee/${data.length>0?data:"defualt.jpg"}" class="img-circle img-xl" alt="" width="50px" height="50px">
                            </a>
                        </span>
                   `;
                }
            },{
                targets:[0],
                visible:false
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        },
        "order": [
            [0, "desc"]
        ],
        "displayLength": 25,
        initComplete: function () {
            $("#datatableEmployeeView_wrapper .datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
            //$("#datatableEmployeeView_wrapper .datatable-header").append(button3("employeeMonthHistory","button","","Show Salary History"," icon-cabinet",'style="margin-right:10px;"'));
        }
    });
    generalConfigDatatable(table,"#datatableEmployeeView");
    generalConfig();
    employeeSalaryHistoryTable = $("#employeeSalaryHistoryTable").DataTable({
        buttons: {
            buttons:[]
        },
        lengthMenu: [
            [10, 25, 50, 100],
            ["10", "25","50","100"]
        ],
        aaData: [],
        aoColumns: [
            { "mDataProp": "date" },
            { "mDataProp": "basicSalary" },
            { "mDataProp": "monthSalary" },
            { "mDataProp": "note" }
        ],
        drawCallback: function () {
            $("[data-popup=tooltip]").tooltip();
            $("[data-popup=popover-custom]").popover({
                template: `<div class="popover  border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>`
            });
        },
        "displayLength": 10
    });
    employeTotaleSalaryHistoryTable=$("#employeTotaleSalaryHistoryTable").DataTable({
        buttons: {
            buttons:[]
        },
        aaData: [],
        aoColumns: [
            { "mDataProp": "date" },
            { "mDataProp": "totalSalary" }
        ],
        drawCallback: function () {
            $("[data-popup=tooltip]").tooltip();
            $("[data-popup=popover-custom]").popover({
                template: `<div class="popover  border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>`
            });
        },
        lengthMenu: [
            [10, 25, 50, 100],
            ["10", "25","50","100"]
        ],
        drawCallback: function () {
            $("[data-popup=tooltip]").tooltip();
            $("[data-popup=popover-custom]").popover({
                template: `<div class="popover  border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>`
            });
        },
        "displayLength": 10
    });
    $("#datatableEmployeeView_wrapper .datatable-header").on("click","#EMPH_HD",function(event){
        $.ajax({
            url: "models/_employee.php",
            type: "POST",
            dataType: "JSON",
            data: {
                "type": "getAllSalary"
            },
            complete: function () {
                oneCloseLoader("#"+$(this).parent().id,"self");
            },
            beforeSend: function () {
                oneOpenLoader("#"+$(this).parent().id,"self","dark");
            },
            success: function (res) {
                if (res.is_success == true) {
                    employeTotaleSalaryHistoryTable.clear();
                    employeTotaleSalaryHistoryTable.rows.add(res.data);
                    employeTotaleSalaryHistoryTable.draw();
                    //oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                } else {
                    oneAlert("error",res.data,res.data);
                }
            }
        });
        $("#employeeTotalSalaryHistoryModal").modal("toggle");
    });
});
function deleteEmployee(EMPID) {
    deletedRow("#datatableEmployeeView",{
        "PageName":$("#PageName").val(),
        "EMPID_UIZP": EMPID,
        "EMPDeleted_UIZ":1,
        "table":"employee",
        "symbol":"EMP"
    });
}
function editEmployee(EMPID) {
    $("#EMPID_UIZP").val(Number(EMPID));
    getDataFromServer(
        "editEmployeeForm",
        "'employee'",
        "_general/image/employee/"
        );  
    $("#editEmployeeModal").modal("toggle");
} 
function salaryEmployee(EMPID,EMPSalary){
    $("#PAYEMPFORID_BIZ").val(Number(EMPID));
    $.ajax({
        url: "models/_employee.php",
        type: "POST",
        dataType: "JSON",
        data: {
            "type": "getSalary",
            "PAYEMPFORID":EMPID
        },
        complete: function () {
            oneCloseLoader("#"+$(this).parent().id,"self");
        },
        beforeSend: function () {
            oneOpenLoader("#"+$(this).parent().id,"self","dark");
        },
        success: function (res) {
            if (res.is_success == true) {
                $("#PAYBasicSalary_BIR").val(Number(EMPSalary));
                if(res.data=="Empty"){
                    $("#PAYMonthSalary_BIR").val(Number(EMPSalary));
                    $("#PAYID_BIZP").val("-1");
                }else{
                    $("#PAYID_BIZP").val(res.data["PAYID"]);
                    $("#PAYMonthSalary_BIR").val(res.data["PAYMonthSalary"]);
                    CKEDITOR.instances['PAYNote_BAR'].setData(res.data["PAYNote"]); 

                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                } 
            }else {
                oneAlert("error",res.data,res.data);
            }
        }
    });
    $("#editEmployeeSalaryModal").modal("toggle");
} 
function salaryArchive(EMPID){
    EMPID=Number(EMPID);
    $.ajax({
        url: "models/_employee.php",
        type: "POST",
        dataType: "JSON",
        data: {
            "type": "returnEmployeeHistory",
            "EMPID": EMPID
        },
        complete: function () {
            oneCloseLoader("#"+$(this).parent().id,"self");
        },
        beforeSend: function () {
            oneOpenLoader("#"+$(this).parent().id,"self","dark");
        },
        success: function (res) {
            if (res.is_success == true) {
                employeeSalaryHistoryTable.clear();
                employeeSalaryHistoryTable.rows.add(res.data);
                employeeSalaryHistoryTable.draw();
                //oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            } else {
                oneAlert("error",res.data,res.data);
            }
        }
    }); 
    $("#employeeSalaryHistoryModal").modal("toggle");
}
    