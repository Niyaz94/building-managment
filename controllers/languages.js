
var table ,   block = $(".content-wrapper").parent(),languageInfo=languageInfo();
    
function editLanguage(LANID,itemName){
    $("#LANID_UIZP").val(Number(LANID));
    getDataFromServer("edit_form",["'languages'"]);  
    $("#editcapitalModal").modal("toggle");

    $('#edit_form_cont').modal('toggle');
}
function deleteLanguage(itemid) {
    block = $("#list_of_items_tbl");
    swal({
        title: Translation("Are you sure?"),
        text: Translation("You will not be able to recover this record again"),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#EF5350",
        confirmButtonText:  Translation("Yes"),
        cancelButtonText:   Translation("Cancel"),
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm){
            if (isConfirm) {
              $.ajax({
                url: "models/_languages.php",
                type:"POST",
                dataType : "JSON",
                data: {
                    "type":"delete",
                    "LANID_UIZP":itemid,
                    "LANDeleted":1,
                    "PageName":$("#PageName").val()
                } ,
                complete:function(){
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend:function(){
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function(res) {
                  if(res.is_success == true){
                    oneAlert("success","Success",res.data);
                    $("#dataTableLanguageView").DataTable().ajax.reload(null, false);
                  }else{
                    oneAlert("error","Error!!!",res.data);
                  }
                }
            });
            }
    }); // /confirm
}
$(document).ready(function() {
    $('#add_form').submit(function(event) {
        event.preventDefault();
        block = $(this).parent();
        $("#LANName_ISZ").val($("#LANSymbol_ISZ option:selected").attr('data-lang-native-name'));
        $("#LANLocal_ISZ").val($("#LANSymbol_ISZ option:selected").attr('data-lang-name'));
        var Check1 = true;
        var Check2 = true;
        if($("#LANSymbol_ISZ").val() =='') {
           addRemoveClass1(true,"#LANSymbol_ISZ");
           Check1 = false;
           oneMessege("","Please enter language name","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
        }else{
            addRemoveClass1(false,"#LANSymbol_ISZ");
        }
        if($("#LANDir_ISZ").val() =='') {
           addRemoveClass1(true,"#LANDir_ISZ");
           Check2 = false;
           oneMessege("","Please enter language name","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
        }else{
            addRemoveClass1(false,"#LANDir_ISZ");
        }
        if(Check1 && Check2){
          var formData = new FormData($(this)[0]);
          formData.append("PageName",$("#PageName").val());
          $("#submit_add_btn").attr('disabled',true);
            $.ajax({
                url: "models/_languages.php",
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
                        $("#dataTableLanguageView").DataTable().ajax.reload(null, false);
                        $("#addlanguagesCollapse").collapse('hide');
                        oneAlert("success","Success",res.data)
                    }else{
                      oneAlert("error","Error!!!",res.data)
                    }
                    $("#submit_add_btn").attr('disabled',false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#submit_add_btn").attr('disabled',false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        }
    });
    $('#edit_form').submit(function(event) {
        event.preventDefault();
        block = $(this).parent();
        $("#LANName_USRN").val($("#LANSymbol_UHRN option:selected").attr('data-lang-native-name'));
        $("#LANLocal_USRN").val($("#LANSymbol_UHRN option:selected").attr('data-lang-name'));
        var Check1 = true;
        var Check2 = true;
        if($("#LANSymbol_USR").val() =='') {
            addRemoveClass1(true,"#LANSymbol_USR");
            Check1 = false;
            oneMessege("","Please enter language name","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
         }else{
             addRemoveClass1(false,"#LANSymbol_USR");
         }
         if($("#LANDir_USR").val() =='') {
            addRemoveClass1(true,"#LANDir_USR");
            Check2 = false;
            oneMessege("","Please enter language name","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
         }else{
             addRemoveClass1(false,"#LANDir_USR");
         }
        if(Check1 && Check2 ){
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            $("#submit_edit_btn").attr('disabled',true);
            $.ajax({
                url: "models/_languages.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType: "JSON",
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    if(res.is_success == true){
                        $("#edit_form")[0].reset();
                        $("#dataTableLanguageView").DataTable().ajax.reload(null, false);
                        $('#edit_form_cont').modal('toggle');
                        oneMessege("Success",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                      oneAlert("error","Error!!!",res.data);
                    }
                    $("#submit_edit_btn").attr('disabled',false);
                },
                fail: function (err){
                    //oneAlert("error","Error!!!",res.data)
                    //$("#submit_add_btn").attr('disabled',false);
                },
                always:function(){
                   // console.log("complete");
                }
            });
          }
    });
    addingExtenton();
    var table = $('#dataTableLanguageView').DataTable({
        buttons: {            
            buttons: dtButtons()
        },
        lengthMenu: [
            [  10, 25, 50, 100 ],
            [  '10', '25', '50', '100' ]
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
        "url" : "models/_languages.php",
        "data": function(d){
          d.type = "load";
        }
        },
        drawCallback: function() {
            tooltip1("");
        },
        "columnDefs": [
          {
                "targets": 6,
                "data": null,
                "render": function (data,type,row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),
                        {},
                        {
                            "LANID":row[0],
                            "LANName":row[1]
                        },
                        "table"
                    );
                }
          },
          {
             "render": function(data, type, row){
                return  `<a href='index.php?p=translation&id=`+row[0]+`'  data-popup='tooltip' data-original-title='`+Translation('Display all its words')+`'>`+data+`</a>`;
             },
             "targets": 1
          },
          {
             "render": function(data, type, row){
                   if(data =='rtl' ){
                     status=  `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">${Translation('From Right to Left')}</span>`;
                   }else{ 
                     status = `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">${Translation('From Left To Right')}</span>`;
                   }
                    return  status;
             },
             "targets": 4
          },
          {
             "render": function(data, type, row){
                   if(data ==1 ){
                        status=  `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">${Translation('Default language')}</span>`;

                   }else{ 
                        status=  `<span class="label label-block label-flat border-primary text-slate-800" style="padding:6%">${Translation('Normal')}</span>`;
                   }
                      return  status;
             },
             "targets": 5
          },  
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if(   aData[4] == 0 ){
                $(nRow).children().each(function (index, td) {
                    $(this).addClass('warning-active');
                });
            }
        },
        "order": [[0,'desc']],
        "displayLength": 25,
        initComplete: function () {
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
        }
    });
    generalConfig(table,"#datatableShopView");
});