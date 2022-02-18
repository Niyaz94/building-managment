<?php
    function input1($inputLength,$inputType,$labelText,$id,$required,$icon,$value="",$readonly="",$class="",$extra=""){
        return '
			<div class="'.$inputLength.'">
				<div class="input-group">
					<span class="input-group-addon">'.$labelText.'</i></span>
					<input type="'.$inputType.'" id="'.$id.'" name="'.$id.'" value="'.$value.'" class="form-control input-lg multi_lang '.$class.'" '.$extra.' placeholder="'.$labelText.'" '.$required.'  '.$readonly.'>
                    <span class="input-group-addon"><i class="'.$icon.'"></i></span>
                </div>
			</div>
        ';
    }
    /*function number1($inputLength,$labelText,$id,$required,$icon,$value=0,$readonly="",$class="",$step="",$min=10,$max=1000000000000){
        return '
            <div class="'.$inputLength.'">
                <div class="input-group">
                    <span class="input-group-addon">'.$labelText.'</i></span>
                    <input type="text" id="'.$id.'" name="'.$id.'" value="'.$value.'" '.$step.' class="form-control numberClass multi_lang '.$class.'" placeholder="'.$labelText.'" '.$required.'  '.$readonly.'>
                    <span class="input-group-addon"><i class="'.$icon.'"></i></span>
                </div>
            </div>
        ';
    }*/
    function input2($inputLength,$values,$labelText,$id,$required,$icon,$keyorValue=0,$class="",$extra="",$placeholder="",$addingButton=0){
        $option="";
		foreach($values as $key=>$value){
            $option.='<option class="multi_lang" value="'.($keyorValue==1?$value:$key).'">'.($keyorValue==1?$key:$value).'</option>';
        }

        if($addingButton==1){
            return '
                <div class="'.$inputLength.'">
                    <div class="form-group">
                        <div class="col-sm-11">
                            <div class="input-group ">
                                <span class="input-group-addon">'.$labelText.'</i></span>
                                <select class="'.$class.'" data-placeholder="'.$placeholder.'" data-width="100%" data-info=\''.$extra.'\' name="'.$id.'" id="'.$id.'" placeholder="'.$labelText.'" required="required">
                                    '.$option.'						
                                </select>
                                <span class="input-group-addon"><i class="'.$icon.'"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-1" style="padding-left:0px">
                            <button type="button" class="btn btn-danger btn-xlg button'.explode("_",$id)[0].'"><i class="icon-plus3"></i></button>
                        </div>
                    </div>
                    
                </div>
            ';
        }else{
            return '
                <div class="'.$inputLength.'">
                    <div class="input-group ">
                        <span class="input-group-addon">'.$labelText.'</i></span>
                        <select class="'.$class.'" data-placeholder="'.$placeholder.'" data-width="100%" data-info=\''.$extra.'\' name="'.$id.'" id="'.$id.'" placeholder="'.$labelText.'" required="required">
                            '.$option.'						
                        </select>
                        <span class="input-group-addon"><i class="'.$icon.'"></i></span>
                    </div>
                </div>
            ';
        }

        
    }
    function input3($inputLength,$labelText,$id,$class){
        return '
            <div class="'.$inputLength.'"  >
                <label class="control-label multi_lang">'.$labelText.'</label><br/>
                <textarea class="'.$class.'" name="'.$id.'" id="'.$id.'" contenteditable="true"></textarea>
            </div>
        ';
    }
    function button1($id,$type,$text,$icon,$class="btn btn-primary btn-labeled btn-labeled-right",$extra=""){
        return '
            <div class="form-group">
                <button type="'.$type.'" class="'.$class.'" id="'.$id.'" '.$extra.'>
                    <span class="multi_lang">'.$text.'</span> 
                    <b><i class="'.$icon.'"></i></b>
                </button>
            </div>
        ';
    }
    function button2($id,$type,$text,$icon,$class="btn btn-primary btn-labeled btn-labeled-right",$extra='',$disabled=""){
        return '
            <button type="'.$type.'" class="'.$class.'" id="'.$id.'" '.$extra.' '.$disabled.'>
                <span class="multi_lang">'.$text.'</span> 
                <b><i class="'.$icon.'"></i></b>
            </button>
        ';
    }
    function button3($id,$link,$text,$icon,$class,$extra=''){
        return '
            <a href="'.$link.'" id="'.$id.'" class="'.$class.'" '.$extra.' >
                <span class="multi_lang">'.$text.'</span> 
                <b><i class="'.$icon.'"></i></b>
            </a>
        ';
    }
    function file1($inputLength,$id,$buttonText,$required=''){
        $splitID=explode("_",$id);
        $imgID=$splitID[0]."_".$splitID[1][0]."FR".$splitID[1][3];
        return '
            <div class="'.$inputLength.'" id="container-'.$id.'">
                <div id="imgContainer-'.$id.'" class="imgContainer">
                    <img src="#" title="" alt="" id="'.$imgID.'" >
                </div>
                <div class="input-group">
                    <div tabindex="500" class="form-control">
                        <div title="">
                            <i class="glyphicon glyphicon-file kv-caption-icon"></i>
                            <span id="name-'.$id.'"></span>
                        </div>
                    </div>
                    <div class="input-group-btn">
                        <button type="button" tabindex="500" title="Remove Image" id="remove-'.$id.'" class="btn btn-default removeFile">
                            <i class="icon-cross3"></i> 
                            <span class="hidden-xs">Remove</span>
                        </button>
                        <div tabindex="500" class="btn btn-primary btn-file">
                            <i class="icon-file-plus"></i>
                            <span class="hidden-xs">'.$buttonText.'</span>
                            <input class="" id="'.$id.'" name="'.$id.'" type="file" accept="image/*" onchange="readURL(this)" '.$required.'>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
    function file2($inputLength,$id,$src="#",$required=''){
        $splitID=explode("_",$id);
        $imgID=$splitID[0]."_".$splitID[1][0]."FR".$splitID[1][3];
        return '
            <div class="'.$inputLength.'">
                <span class="btn btn-file" style="box-shadow: 0 0 3px rgba(0, 0, 0, 0); background-color: rgba(245, 245, 245, 0);">
                    <a href="#" id="profileLink" class="profile-thumb">
                        <img src="'.$src.'" class="img-circle img-xl" alt="" id="'.$imgID.'" width="350px" height="380px">
                        <input class="" id="'.$id.'" name="'.$id.'" type="file" accept="image/*" onchange="readURL_V2(this)" '.$required.'>
                    </a>
                </span>
            </div>
        ';
    }
?>