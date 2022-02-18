var table, block = $(".content-wrapper").parent(),languageInfo=languageInfo();
$(document).ready(function () {
    $('#add_form').submit(function (event) {
        event.preventDefault();
        block = $(this).parent();
        $("#submit_add_btn").attr('disabled', true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        $.ajax({
            url: "models/_translation.php",
            type: "POST",
            dataType: "JSON",
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
                if(res.is_success == true){
                    $("#add_form")[0].reset();
                    $("#datatabletranslationView").DataTable().ajax.reload(null, false);
                    $("#addtranslationCollapse").collapse('hide');
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
    });
    addingExtenton();
    var query_id = getQueryVariable("id");
    var table = $('#datatabletranslationView').DataTable({
        buttons: {
            buttons: dtButtons()
        },
        lengthMenu: [
            [10, 25, 50, 100, -1],
            ['10', '25', '50', '100', 'All']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "models/_translation.php",
            "data": function (d) {
                d.type = "load";
                if (query_id != 'not') {
                    d.id = query_id;
                }
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
                        {},
                        {
                            "KEYID":row[0],
                            "LAKFORIDLAN":row[6],
                        },
                        "table"
                    );
                }
            },{
                "render": function (data, type, row) {
                    return `<a href='index.php?p=translation&id=` + row[6] + `'  data-popup='tooltip' data-original-title='` + Translation('Display this language keywords') + `'>` + data + `</a>`;
                },
                "targets": 3
            },{
                "render": function (data, type, row) {
                    if (data == '' || data == null) {
                        class1 = 'has-warning';
                        class2 = 'icon-notification2';
                    } else if (data < 0) {
                        class1 = 'has-error';
                        class2 = 'icon-cancel-circle2';
                    } else {
                        class1 = 'has-success';
                        class2 = 'icon-checkmark-circle';
                    }
                    return `
                        <div class="col-lg-12 ${class1}">
                            <div class="input-group">
                            <textarea 
                                type="text" 
                                id="key_word_${row[0]}" 
                                name="key_word_${row[0]}" 
                                class="form-control" 
                                placeholder="${Translation('keyword')}" 
                                value="${data}"  
                                style="height:80px;"
                            >
                                ${data}
                            </textarea>
                            </div>
                            <div class="form-control-feedback"><i class="${class2}"></i></div>
                        </div>
                    `;
                },
                "targets": 1
            },{
                "render": function (data, type, row) {
                    if (data == '' || data == null) {
                        data = '';
                        class1 = 'has-warning';
                        class2 = 'icon-notification2';
                    } else if (data < 0) {
                        class1 = 'has-error';
                        class2 = 'icon-cancel-circle2';
                    } else {
                        class1 = 'has-success';
                        class2 = 'icon-checkmark-circle';
                    }
                    return `
                            <div class="col-lg-12 ${class1}">
                              <div class="input-group">
                                <textarea 
                                    type="text" 
                                    id="translated_${row[0]+"_"+row[6]}" 
                                    name="translated_${row[0]+"_"+row[6]}" 
                                    class="form-control" 
                                    placeholder="${Translation('Translate to ')+' '+row[3]}" 
                                    value="${data}" 
                                    style="height:80px;"
                                >
                                    ${data}
                                </textarea>
                              </div>
                              <div class="form-control-feedback"><i class="${class2}"></i></div>
                            </div>
                      `;
                },
                "targets": 2
            },{
                "render": function (data, type, row) {
                    if (data == 'rtl') {
                        status = `<span class="label label-success btn-block ">` + Translation('Right to Left') + `</span>`;
                    } else {
                        status = '<span class="label bg-violet  btn-block ">' + Translation('Left to Right') + '</span>';
                    }
                    return status;
                },
                "targets": 5
            },
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData[9] == 0) {
                $(nRow).children().each(function (index, td) {
                    $(this).addClass('warning-active');
                });
            }
        },
        "order": [
            [0, 'desc']
        ],
        "displayLength": 25,
        initComplete: function () {
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#STFProfileType").val(),{},{},"header"));
            $("div.datatable-header").append(`
                &nbsp;  
                <div class="btn-group">
                    <button 
                        id="inv_filter_btn" 
                        type="button" 
                        class="btn bg-teal-400 btn-labeled dropdown-toggle" 
                        data-toggle="dropdown"
                    >
                        <b><i class="icon-filter4"></i></b>
                        <span id='inv_filter'>` + Translation('Language Filter') + `</span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" id="languages_filter">
                    </ul>
                </div>
            `);
            //adding language to buttons
            $.ajax({
                url: 'models/_languages.php',
                type: 'POST',
                data: {
                    type: 'get'
                },
                dataType: 'json',
                success: function (response) {
                    var attach = '';
                    for (var i = response.length - 1; i >= 0; i--) {
                        attach = attach + '<li><a href="index.php?p=translation&id=' + response[i][0] + '"><i class="icon-earth  text-success"></i> ' + response[i][1] + '</a></li>';
                    }
                    $("#languages_filter").html(attach);
                },
                complete: function () {
                }
            }); 
        }
    });
    generalConfigDatatable(table,"#datatabletranslationView");
    generalConfig(); 
});
function edittranslation(KEYID, LAKFORIDLAN) {
    $.ajax({
        url: "models/_translation.php",
        type: "POST",
        dataType: "JSON",
        data: {
            KEYID: KEYID,
            LAKFORIDLAN: LAKFORIDLAN,
            LAKTranslated: $("#translated_" + KEYID+"_"+LAKFORIDLAN).val().trim(),
            KEYName: $("#key_word_" + KEYID).val().trim(),
            PageName:$("#PageName").val(),
            type: 'save_single'
        }
    }).done(function (res) {
        if (res.is_success == true) {
            $("#datatabletranslationView").DataTable().ajax.reload(null, false);
            oneMessege("Success",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
        } else {
            oneMessege("Error!!!",res.data,"danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
        }
    }).fail(function (err) {
            oneMessege("Error!!!",err,"danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
    }).always(function () {
        console.log("complete");
    });
}
function deletetranslation(KEYID) {
    block = $("#list_of_items_tbl");
    swal(        
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_translation.php",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        "type": "delete",
                        "PageName":$("#PageName").val(),
                        "KEYID_UIZP": KEYID,
                        "KEYDeleted_UIZ":1
                    },
                    complete: function () {
                        oneCloseLoader("#list_of_items_tbl","self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#list_of_items_tbl","self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            oneAlert("success","Success",res.data)
                            $("#datatabletranslationView").DataTable().ajax.reload(null, false);
                        } else {
                            oneAlert("error","Error!!!",res.data);
                        }
                    }
                });
            }
        }); // /confirm
}
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    return 'not';
}