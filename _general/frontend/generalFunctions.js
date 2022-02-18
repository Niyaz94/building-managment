
function escapeHtml(text) {
    if(text == null || text=='' || text==undefined){
      return text;
    }else{
      var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, function(m) { return map[m]; });

      /*safe = unsafe.replace(/%/g, "&_25_;");
      safe = safe.replace(/\\/g, "&_5C_;");
      safe = safe.replace(/"/g, "&_quot_;");
      safe = safe.replace(/'/g, "&#_039_;");
      safe = safe.replace(/\n/g, "&#_013_;");
      return safe;*/
    }   
}
function deselectSelect2(){
    $('.select2Class').each(function () {
        $("#"+$(this).attr('id')).val("").trigger("change");
    });
}
function decodeURIComponent_back(unsafe) {
    if(unsafe == null || unsafe=='' || unsafe==undefined){
      return unsafe;
    }else{
      safe = unsafe.replace(/&_25_;/g, "%");
      safe = safe.replace(/&_5C_;/g, "\\");
      safe = safe.replace(/&_quot_;/g, '"');
      safe = safe.replace(/&#_039_;/g, "'");
      safe = safe.replace(/&#_013_;/g, "\n");
      return safe;
    }    
}
$(document).ready(function () {
    $(".removeFile").on("click",function(event){
        id=$(this).prop("id").split("-")[1];
        $("#"+id).val("");
        splitID=id.split("_");
        imgID=splitID[0]+"_"+splitID[1][0]+"FR"+splitID[1][3];
        $("#name-"+id).text("");
        $("#imgContainer-"+id).hide("toogle");
        $("#"+imgID).attr("src","#").width($("#container-"+id).width()-100).height("200px");
    });
});
function generalConfig(){
    $('.editors').each(function () {
          CKEDITOR.replace($(this).attr('id'), {
              toolbarGroups: [{
                      "name": "basicstyles",
                      "groups": ["basicstyles"]
                  },
                  {
                      "name": "paragraph",
                      "groups": ["list"]
                  },
                  {
                      "name": "styles",
                      "groups": ["styles"]
                  },
              ],
              height: '200px'
          });
    });
    if($('.dateStyle').daterangepicker != undefined){
      $('.dateStyle').daterangepicker({
          locale: {
              format: 'YYYY-MM-DD'
          },
          singleDatePicker: true
      });
    }
    $('.dateMonthandYear').each(function () {
        $("#"+$(this).attr('id')).AnyTime_picker({
            format: "%Y-%m",
        });
    });
    if($(".select2Class").select2 != undefined){
      $(".select2Class").select2({
          placeholder:"Please Select...",
          containerCssClass: 'select-lg'
      });
    }
    $(window).keydown(function(event){
        if(event.keyCode==13){
            event.preventDefault;
            return false;
        }
    });
}
function datatableAfterInit(){ 
    $('.dateAndTimeStyle').each(function () {
        $("#"+$(this).attr('id')).AnyTime_picker({
            format: "%Y-%m-%d %H:%i %s",
        });
    });
}
function readURL(input){
    id=$(input).prop("id");
    if(id!=$(input).prop("name")){
        $(input).prop("name",id);
    }
    splitID=id.split("_");
    imgID=splitID[0]+"_"+splitID[1][0]+"FR"+splitID[1][3];
    //imgID=id.substr(0,id.length-1)+"FR";/*+id.substr(id.length-1,1);*/
    imgName=$(input).val().split("\\");
    $("#name-"+id).text(imgName[imgName.length-1]);
    $("#imgContainer-"+id).show("toogle");
    if(input.files && input.files[0]){
        var reader= new FileReader();
        reader.onload=function(e){
            $("#"+imgID).attr("src",e.target.result).width($("#container-"+id).width()-100).height("270px");
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function readURL_V2(input){
    id=$(input).prop("id");
    if(id!=$(input).prop("name")){
        $(input).prop("name",id);
    }
    splitID=id.split("_");
    imgID=splitID[0]+"_"+splitID[1][0]+"FR"+splitID[1][3];
    imgName=$(input).val().split("\\");
    if(input.files && input.files[0]){
        var reader= new FileReader();
        reader.onload=function(e){
            $("#"+imgID).attr("src",e.target.result).width("350").height("380px");
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function getDataFromServer(formID,tableName,srcLink="",condition=[]){
    value=[];
    ids=[];
    $(`#${formID} input,#${formID} select,#${formID} textarea,#${formID} img`).each(function(index,data){
        id=$(data).prop("id");
        splitID=id.split("_");
        if(splitID[1]!== undefined){
            if( splitID[1][3]!==undefined && splitID[1][3]=="P"){
                condition.push({"columnName":splitID[0],"operation":"=","value":$("#"+id).val(),"link":""});
            }else if(splitID[1][1]!==undefined && splitID[1][1]=="G"){
                select2=JSON.parse($("#"+id).attr("data-info"));
                for(key in select2){
                    value.push(select2[key]);
                }
                ids.push(id);
            }else if(typeof splitID[1][2]!==undefined && splitID[1][2]=="R"){
                value.push(splitID[0]);
                ids.push(id);
            }
        }
    });
   // console.log(value,ids);
    $.ajax({
        url: "models/_general.php",
        type: "POST",
        data: {
          "type":"getData",
          "table":tableName,
          "columns":JSON.stringify(value),
          "condition":JSON.stringify(condition)
        },
        dataType:"json",
        complete: function () {
            oneCloseLoader("#"+$(this).parent().id,"self");
        },
        beforeSend: function () {
            oneOpenLoader("#"+$(this).parent().id,"self","dark");
        },
        success: function (res) {
            if(res.is_success == true){
                for(var i=0;i<ids.length;i++){
                    splitID=ids[i].split("_");
                    if(splitID[1][1]=="A"){//input ckeditor
                        CKEDITOR.instances[ids[i]].setData(res.data[splitID[0]]); 
                    }else if(splitID[1][1]=="I"){//input integer
                        $("#"+ids[i]).val(Number(res.data[splitID[0]]));
                    }else if(splitID[1][1]=="H"){//select2
                        $("#"+ids[i]).val(res.data[splitID[0]]).trigger("change");
                    }else if(splitID[1][1]=="F"){//image
                        checkSize=srcLink.split("/")[2];
                        width="600px";
                        height="200px";
                        if(checkSize=="employee"){
                            width="350px";
                            height="350px";
                        }
                        $("#"+ids[i]).prop("src",(res.data[splitID[0]].length>0?srcLink+res.data[splitID[0]]:"#")).width(width).height(height);
                    }else if(splitID[1][1]=="G"){
                        select2=JSON.parse($("#"+ids[i]).attr("data-info"));
                        $("#"+ids[i]).select2("trigger", "select", {
                            data: {id:res.data[select2["id"]] ,text:res.data[select2["text"]]}
                        });
                    }else if(splitID[1][1]!==undefined && splitID[1][1]=="K"){
                        newRes=res.data[splitID[0]].split("-");
                        $("#"+ids[i]).val(newRes[0]+"-"+newRes[1]);
                    }else{
                        $("#"+ids[i]).val(res.data[splitID[0]]);
                    }
                }
                $(`#${formID} .imgContainer`).each(function(index,data){
                    if($(this).find("img").attr("src")=="#"){
                        $(this).hide("toogle");
                    }else{
                        splitID=$(this).prop("id").split("-")[1];
                        splitImageName=$(this).find("img").attr("src").split("/");
                        $("#name-"+splitID).text(splitImageName[splitImageName.length-1]);
                    }
                });
                //oneMessege("","The Process Finished Successfully","success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            }else{
                oneAlert("error","Error!!!",res.data)
            }
        },
        fail: function (err){
            oneAlert("error","Error!!!",res.data)
        },
        always:function(){
        }
    });
}
function deletedRow(id,data,text="You will not be able to recover this record again"){
    data["type"]="delete"
    swal(
        secondAlert("warning","Are you sure?",text),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_general.php",
                    type: "POST",
                    dataType: "JSON",
                    data:data,
                    complete: function () {
                        //oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                       // oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            $(id).DataTable().ajax.reload(null, false);
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
function returnShopNumberID(id,condition=""){
    $(id).select2({
        minimumResultsForSearch: -1,
        ajax: {
            url: "models/_general.php",
            dataType: 'json',
            type: 'POST',
            quietMillis: 100,
            data: function (data, page) {
                return {
                    type: "returnShopNumber",
                    condition: condition,
                    SOPNumber:data.term,
                    page_limit: 8,
                    page: page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
    
                if (data.length > 0) {
                    var results = [];
                    $.each(data, function (index, item) {
                        results.push({
                            id: item.SOPID,
                            text: item.SOPNumber,
                            data: item
                        });
                    });
                    return {
                        results:results,
                        pagination: {
                            more: (params.page * 30) < data.length
                          }
                    };
                }
            }
        },
        minimumInputLength: 1,
        escapeMarkup: function (markup) {
             return markup; 
        },
        templateResult: function(res){
            if (res.loading) {
                return res.text;
            }
            return `
                <div class="media">
                    <div class="media-left media-middle">
                        <a href="#" class="btn bg-pink-400 btn-rounded btn-icon btn-xlg">
                            <span class="">${res.data.SOPNumber}</span>
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-heading">
                            <a href="#" class="letter-icon-title">${res.data.SOPCategory}</a>
                        </div>
                        <div class="text-muted text-size-large">
                            Floor No:- <span class="badge badge-primary">${res.data.SOPFloor}</span>
                            Area:- <span class="badge badge-warning" style="">${res.data.SOPArea}</span>
                        </div>
                    </div>
                </div>
            `;
        },
        templateSelection: function  (res) {
            return res.SOPNumber || res.text;
        },
        placeholder: "Please Select Shop"
    });
}
function returnExpensesCategory(id,condition=""){
    $(id).select2({
        minimumResultsForSearch: -1,
        ajax: {
            url: "models/_general.php",
            dataType: 'json',
            type: 'POST',
            quietMillis: 100,
            data: function (data, page) {
                return {
                    type: "returnExpensesCategory",
                    condition: condition,
                    EXGName:data.term,
                    page_limit: 8,
                    page: page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
    
                if (data.length > 0) {
                    var results = [];
                    $.each(data, function (index, item) {
                        results.push({
                            id: item.EXGID,
                            text: item.EXGName,
                            data: item
                        });
                    });
                    return {
                        results:results,
                        pagination: {
                            more: (params.page * 30) < data.length
                          }
                    };
                }
            }
        },
        minimumInputLength: 1,
        escapeMarkup: function (markup) {
             return markup; 
        },
        templateResult: function(res){
            if (res.loading) {
                return res.text;
            }

            return `
                <div class="media">
                    <div class="media-left media-middle">
                        <span class="label label-block label-flat text-slate-800" style="padding:12px;font-size:24px;">${res.data.EXGName}</span>
                    </div>
                    <div class="media-body">
                        <div class="text-muted text-size-large">
                            Note:- <span class="badge badge-primary">${res.data.EXGNote}</span>
                        </div>
                    </div>
                </div>
            `;
        },
        templateSelection: function  (res) {
            return res.EXGName || res.text;
        },
        placeholder: "Please Select Expenses Category"
    });
}
function returnCustomerID(id){
    $(id).select2({
        minimumResultsForSearch: -1,
        ajax: {
            url: "models/_general.php",
            dataType: 'json',
            type: 'POST',
            quietMillis: 100,
            data: function (data, page) {
                return {
                    type: "returnCustomerID",
                    SOPNumber:data.term,
                    page_limit: 8,
                    page: page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                if (data.length > 0) {
                    var results = [];
                    $.each(data, function (index, item) {
                        results.push({
                            id: item.CUSID,
                            text: item.CUSName,
                            data: item
                        });
                    });
                    return {
                        results:results,
                        pagination: {
                            more: (params.page * 30) < data.length
                          }
                    };
                }
            }
        },
        minimumInputLength: 1,
        escapeMarkup: function (markup) {
             return markup; 
        },
        templateResult: function(res){
            if (res.loading) {
                return res.text;
            }
            return `
                <div class="media">
                    <div class="media-left media-middle">
                        <a href="#" class="btn bg-pink-400 btn-rounded btn-icon btn-xlg">
                            <span class="">${res.data.CUSID}</span>
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-heading">
                            <a href="#" class="letter-icon-title">${res.data.CUSName}</a>
                        </div>
                        <div class="text-muted text-size-large">
                            Phone No:- <span class="badge badge-primary">${res.data.CUSPhone}</span>
                            Address:- <span class="badge badge-warning" style="">${res.data.CUSAddress}</span>
                        </div>
                    </div>
                </div>
            `;
        },
        templateSelection: function  (res) {
            return res.SOPName || res.text;
        },
        placeholder: "Please Select Customer"
    });
}
function discountValue(value,discunt){
    return Number(value)*(1-(Number(discunt)/100));
} 
function changeLanfuage(ID,LANG,DIR,CSS){
    $.ajax({
        url: "models/_general.php",
        type: "POST",
        dataType: "JSON",
        data:{
            "type":"updateLanguage",
            "LANG":LANG,
            "DIR":DIR,
            "CSS":CSS,
            "ID":ID
        },
        complete: function () {
        },
        beforeSend: function () {
        },
        success: function (res) {
            if(res==1){
                location.reload();
            }
        }
    }); 
}
function FixedPointV2(num,type="input",maxPoint=2,format=0) {
	/*
		num : user input
		type : if type is output return string with comma otherwise return number 
		maxPoint : maximum point after dot
		format : arabic or english format
	*/
	num=num.toString().split(',').join('');
	num=Number(num).toString();
	num=Number(num);
	
	if(num % 1 === 0){//int
		return type=="output"?parseInt(num).toLocaleString(format==1?'ar-EG':'en-EG', { minimumFractionDigits: 0 }):parseInt(num);
	}else if(num % 1 !== 0){//float
		return type=="output"?parseFloat(num).toLocaleString(format==1?'ar-EG':'en-EG', { minimumFractionDigits: maxPoint }):parseFloat(num);
	}else{
		return num;
	}

}
function FixedPoint(num) {
    num = parseFloat(num).toString();
    num = num.toString().split(".");

    if (num[1] == undefined) {
        return num[0];
    } else {
        if (num[1].length >= 2) {
            return parseFloat(num[0] + "." + num[1]).toFixed(2);
        } else {
            return parseFloat(num[0] + "." + num[1]).toFixed(num[1].length);
        }
    }
}