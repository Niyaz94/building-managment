$(document).ready(function () {
    $("#addexpensesgroupForm").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("EXGNote_IAZN",CKEDITOR.instances['EXGNote_IAZN'].getData());
        formData.append("type","create");
        currentUrl=$("#PageName").val().split("/");
        currentPage=currentUrl[currentUrl.length-1].split(".")[0];
        if(currentPage=="expensesgroup"){
            $("#saveexpensesgroupFormCollapse").attr("disabled", true);       
        }else if(currentPage=="expenses"){
            $("#saveexpensesgroupFormModal").attr("disabled",true);
        }
        $.ajax({
            url: "models/_expensesgroup.php",
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
                    if(currentPage=="expensesgroup"){
                        $("#datatableexpensesgroupView").DataTable().ajax.reload(null, false);
                        $("#addexpensesgroupCollapse").collapse("hide");
                        $("#saveexpensesgroupFormCollapse").attr("disabled",false);
                    }else if(currentPage=="expenses"){
                        $("#insertexpensesgroupModal").modal("toggle");
                        $("#saveexpensesgroupFormModal").attr("disabled",false);
                    }
                    $("#addexpensesgroupForm")[0].reset();
                    CKEDITOR.instances['EXGNote_IAZN'].setData(""); 
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                    if(currentPage=="expensesgroup"){
                        $("#saveexpensesgroupFormCollapse").attr("disabled",false);
                    }else if(currentPage=="expenses"){
                        $("#saveexpensesgroupFormModal").attr("disabled",false);
                    }
                    oneAlert("error","Error!!!",res.data)
                }
                
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data);
                if(currentPage=="expensesgroup"){
                    $("#saveexpensesgroupFormCollapse").attr("disabled",false);
                }else if(currentPage=="expenses"){

                }
            },
            always:function(){
                console.log("complete");
            }
        });
    });
});