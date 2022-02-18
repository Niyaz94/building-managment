$(document).ready(function () {
    $("#addwatchrentForm").on("submit", function (e) {
        e.preventDefault();
        check=true;
        if(Number($("#WNTOldRead_IIZN").val())>=Number($("#WNTNewRead_IIZN").val())){
            check=false;
        }
        if(check){
            $("#savewatchrentFormCollapse").attr("disabled", true);       
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("WNTWTCFORID_IIZN",$("#WTCID").val());
            formData.append("ARTAGRFORID_IIZN",$("#AGRID").val());
            formData.append("SOPID",$("#SOPID").val());

            formData.append("type","create");
            $.ajax({
                url: "models/_watchrent.php",
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
                        $("#addwatchrentForm")[0].reset();
                        $("#datatablewatchrentView").DataTable().ajax.reload(null, false);
                        $("#addwatchrentCollapse").collapse("hide");
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                        oneAlert("error","Error!!!",res.data)
                    }
                    $("#savewatchrentFormCollapse").attr("disabled",false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data);
                    $("#savewatchrentFormCollapse").attr("disabled",false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        }else{
            oneAlert("error","Error!!!","Must be New Read Greater Than Old Read");
        }
        
    });
    $("#editwatchrentForm").on("submit", function (e) {
        e.preventDefault();
        check=true;
        if(Number($("#WNTOldRead_UIRN").val())>=Number($("#WNTNewRead_UIRN").val())){
            check=false;
        }
        if(check){
            $("#savewatchrentFormModal").attr("disabled", true);
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("AGRID",$("#AGRID").val());
            formData.append("SOPID",$("#SOPID").val());
            formData.append("type","update");
            $.ajax({
                url: "models/_watchrent.php",
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
                        $("#editwatchrentForm")[0].reset();
                        $("#datatablewatchrentView").DataTable().ajax.reload(null, false);
                        $("#editwatchrentModal").modal("toggle");
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                    oneAlert("error","Error!!!",res.data);
                    }
                    $("#savewatchrentFormModal").attr("disabled",false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#savewatchrentFormModal").attr("disabled",false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        }else{
            oneAlert("error","Error!!!","Must be New Read Greater Than Old Read");
        }
    });
    addingExtenton();
    table = $("#datatablewatchrentView").DataTable({
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
            "url": "models/_watchrent.php",
            "data": function (d) {
                d.type = "load";
                d.SOPID=$("#SOPID").val();
                d.WTCID=$("#WTCID").val();
            }
        },
        drawCallback: function () {
            tooltip1("");
        },
        "columnDefs": [
            {
                "targets": 12,
                "data": null,
                "render": function (data, type, row) {
                    console.log(row[12]);
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                        {
                            "ARTPaidType":row[9],
                            "WNTDeleted":row[11],
                            "edit_last":row[12],
                        },
                        {
                            "WNTID":row[0],
                            "WNTARTFORID":row[10]
                        },
                        "table"
                    );
                }
            },{
                targets:[1,2,9,10,11],
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
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatablewatchrentView");
    generalConfig(); 
});
function deletewatchrent(WNTID,WNTARTFORID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_watchrent.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "ARTID_UIZP": WNTARTFORID,
                        "ARTDeleted_UIZ":1,
                        "WNTID_UIZP": WNTID,
                        "WNTDeleted_UIZ":1,
                    },
                    complete: function () {
                        //oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                       // oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatablewatchrentView").DataTable().ajax.reload(null, false);
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        } else {
                            oneAlert("error",res.data,res.data);
                        }
                    }
                }); 
            }
        }
    );
}
function editwatchrent(WNTID) {
    $("#WNTID_UIZP").val(Number(WNTID));
    getDataFromServer("editwatchrentForm",["'watchrent'","'agreementrent'"]);  
    $("#editwatchrentModal").modal("toggle");
}