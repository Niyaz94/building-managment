languageInfo = languageInfo();
$(document).ready(function () {

      //$("#LANName_USZ").val($("#selectedLanguage").val()).trigger("change");


      $("#cover_image").on("change", function () {
            $("#imageUploadCoverImageForm").submit();
      });
      $("#profile_image").on("change", function () {
            $("#imageUploadImageForm").submit();
      });
      $('#LANName_ISZ').select2({
            placeholder: "Select Language Name",
      });
      $("#LANName_ISZ").val($("#language_id").val()).trigger("change"); 
});
$('#imageUploadImageForm').on('submit', (function (e) {
      e.preventDefault();
      var formData = new FormData($(this)[0]);
      formData.append("PageName",$("#PageName").val());
      formData.append("type","updateProfileImage");
      $.ajax({
            url: 'models/_profile.php',
            type: 'POST',
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
            success: function (response) {
                  if (response.is_success == true) {
                        oneMessege("",response.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        location.reload();
                  } else {
                        oneMessege("",response.data,"danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                  }
            },
            complete: function (data) {
            }
      });
}));
$('#imageUploadCoverImageForm').on('submit', (function (e) {
      e.preventDefault();
      var formData = new FormData($(this)[0]);
      formData.append("PageName",$("#PageName").val());
      formData.append("type","updateProfileCoverImage");
      $.ajax({
            url: 'models/_profile.php',
            type: 'POST',
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
            success: function (response) {
                  if (response.is_success == true) {
                        oneMessege("",response.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        location.reload();
                  } else {
                        oneMessege("",response.data,"danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                  }
            },
            complete: function (data) {
            }
      });
}));
$('#profileform').on('submit', function (e) {
      e.preventDefault();
      Check1=true;
      if ($("#STFUsername_USZ").val().length < 3) {
          oneMessege("","UserName Should Be at least 3 Characters!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
          Check1 = false;
      }
      if (Check1) {
          $("#updateprofile").attr('disabled', true);
          var formData = new FormData($(this)[0]);
          formData.append("PageName",$("#PageName").val());
          formData.append("type","updateProfile");
          $.ajax({
              url: "models/_profile.php",
              type: "POST",
              cache: false,
              contentType: false,
              processData: false,
              dataType:"json",
              data: formData,
              complete: function () {
                  oneCloseLoader("#"+$(this).parent().id,"self");
              },
              beforeSend: function () {
                  oneOpenLoader("#"+$(this).parent().id,"self","dark");
              },
              success: function (res) {
                  //console.log(res.is_success == true,res.is_success == "true");
                  if(res.is_success == true){
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    location.reload();
                  }else{
                    oneAlert("error","Error!!!",res.data);
                  }
                  $("#updateprofile").attr('disabled',false);
              },
              fail: function (err){
                  oneAlert("error","Error!!!",res.data)
                  $("#updateprofile").attr('disabled',false);
              },
              always:function(){
                  console.log("complete");
              }
          });
      } else {
          oneMessege("","Enter Required Fields!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
      } // if check
});
$('#profileformpass').on('submit', function (e) {
      e.preventDefault();
      var Check1 = true;
      if ($("#STFPassword_UWZ").val() != $("#STFPassword_UWW").val()) {
            oneMessege("","Passwords Does Not Matches!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            Check1 = false;
      }
      if ($("#STFPassword_UWZ").val().length < 6) {
          oneMessege("","Passwords Should Be at least 6 Characters!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
          Check1 = false;
      }
      if (Check1) {
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","updatePassword");
            $.ajax({
                url: "models/_profile.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                data: formData,
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    if(res.is_success == true){    
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        window.location="models/logout.php";
                       // location.reload();
                  }else{
                      oneAlert("error","Error!!!",res.data)
                    }
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                },
                always:function(){
                    console.log("complete");
                }
            });
      } else {
            oneMessege("", "You entered wrong password! or passwords does not Match", "danger", languageInfo["DIR"] == "ltr" ? "right" : "left", languageInfo["DIR"] == "ltr" ? "topright" : "topleft", false)
      }
});