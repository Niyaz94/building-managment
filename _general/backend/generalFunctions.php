<?php
    function jsonMessages($type,$number){
        $data="";
        if($type==true){
            if($number==1){
                $data="The Process Finished Sucessfully.";
            }else if($number==2){
                $data="The Data added Successfully.";
            }else if($number==3){
                $data="The Delete Operation Finished Successfully";
            }else if($number==4){
                $data="Successfully Update Your System Information";
            }else if($number==5){
                $data="Empty";
            }
        }else{
            if($number==1){
                $data="Error!!! Something Wrong Please Try Again.";
            }else if($number==2){
                $data="Erorr!!! The Name Repeated.";
            }else if($number==3){
                $data="Error!!! You can't Delete Because in the System have translated word.";
            }else if($number==4){
                $data="Error!!! Passwords does not match.";
            }else if($number==5){
                $data="Error!!! You Are Not Allowed To perform this Action.";
            }else if($number==6){
                $data="Error!!! Super Admin Can Not be Deleted or Deactive, it Should be at least one Super Admin.";
            }else if($number==7){
                $data="Error!!! We have the same Username please enter another Username.";
            }else if($number==8){
                $data="Error!!! We have No Data for Update All data the same.";
            }else if($number==9){
                $data="Error!!! Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }else if($number==10){
                $data="Error!!! Sorry, your file is too large.";
            }else if($number==11){
                $data="Error!!! File is not an image.";
            }else if($number==12){
                $data="Error!!! All Data The Same, Not Need Update.";
            }else if($number==13){
                $data="Error!!! The Date Wrong, Must be Enter greter Date.";
            }else if($number==14){
                $data="Error!!! Not Enough Money In Capital To Pay this Expense.";
            }else if($number==15){
                $data="Error!!! You Can't Not Delete This Row Because Not Enough Money In Capital.";
            }else if($number==16){
                $data="Error!!! You Can't Not Edit This Row Because Not Enough Money In Capital.";
            }else if($number==17){
                $data="Error!!! You Can't Not Insert This Row Because Not Enough Money In Capital.";
            }
        }
        return json_encode(array(
            "is_success"=>$type,
            "data"=>$data
        ));
    }
    function jsonMessages2($type,$data){
        return json_encode(array(
            "is_success"=>$type,
            "data"=>$data
        ));
    }
    function testData($data,$type=0){
        if($type=="0"){
            print_r($data);
        }else if($type=="1"){
            echo $data;
        }
        exit;
    }
    function returnPermissionJSON(){
        return json_decode(file_get_contents("models/json/permission.json"),true);
    }
    function checkLoginActive(){
        $data=json_decode(html_entity_decode(is_string($_SESSION["userPermission"])?$_SESSION["userPermission"]:array()),true);
        $newData=array();
        if(is_array($data)){
            foreach($data as $key=>$value){
                if($data[$key]["active"]==1){
                    $newData[]=$key;
                }
            }
        }
        return $newData;
    }
    function uploadingImageFile($file,$path,$name){
        $target_file = $path . basename($fileSaveName);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		if(strlen($file[$name]["tmp_name"])==0 || getimagesize($file[$name]["tmp_name"]) == false) {// Check if image file is a actual image or fake image
            return "false11";
        }
		if ($file[$name]["size"] > 1500000) {
            return "false10";	
        }
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            return "false9";
        }
        $result=move_uploaded_file($_FILES[$name]["tmp_name"], $target_file);
        if($result){
            chmod($target_file,0777);
            return "true1";
        }else{
            return "false1";
        }
    }
    function uploadingImageFileV2($file,$path){
        $fileDatabaseName=array();
        foreach($file as $fileName=>$fileInfo){
            if(isset(explode("_",$fileName)[1][3]) && explode("_",$fileName)[1][3]=="R" && $file[$fileName]["size"]==0){//file required and user not uploaded
                return jsonMessages2(false,"Inserting Image Required Please Upload Image.");
            }
            if(isset(explode("_",$fileName)[1][3]) && explode("_",$fileName)[1][3]=="E" ){//file remove image
                $fileDatabaseName[$fileName]="";
                continue;
            }
            if($file[$fileName]["size"]==0){
                continue;
            }
            if ($file[$fileName]["size"] > 1500000) {
                return jsonMessages2(false,"The Image To large Please Enter Another Image.");	
            }
            if($file[$fileName]["type"] != "image/jpg" && $file[$fileName]["type"] != "image/png" && $file[$fileName]["type"] != "image/jpeg" && $file[$fileName]["type"] != "image/gif" ){
                return jsonMessages2(false,"The File Not Image, Please Upload Image File.");	
            }
            $fileSaveName = rand(1000,100000) . "-" . $_FILES[$fileName]['name'];
            $target_file = $path . basename($fileSaveName);
            $result=move_uploaded_file($_FILES[$fileName]["tmp_name"], $target_file);
            if($result){
                chmod($target_file,0777);
                $fileDatabaseName[$fileName]=$fileSaveName;
            }
        }
        return $fileDatabaseName;
    }
    function totalAllType($database,$date="",$currentMonth=0){
        $add_date="";
        if(strlen($date)>0){
            $add_date="CPTDate<='$date' AND ";
        }
        if($currentMonth==1){
            $add_date="CPTDate between '".date('Y-m-01', strtotime($date))."' AND '".date('Y-m-t', strtotime($date))."' and ";
        }
        $arr=array("income_usd"=>0,"income_iqd"=>0,"expense_usd"=>0,"expense_iqd"=>0);
        $total=$database->return_data("
            SELECT
                CASE 
                    WHEN ( CPTTransactionType = 0 OR CPTTransactionType= 2 OR CPTTransactionType= 4 OR CPTTransactionType=5 OR CPTTransactionType=6) AND CPTMoneyType = 0 THEN 'income_usd' 
                    WHEN ( CPTTransactionType = 0 OR CPTTransactionType= 2 OR CPTTransactionType= 4 OR CPTTransactionType=5 OR CPTTransactionType=6) AND CPTMoneyType = 1 THEN 'income_iqd' 
                    WHEN ( CPTTransactionType = 1 OR CPTTransactionType=3) AND CPTMoneyType = 0 THEN 'expense_usd'
                    WHEN ( CPTTransactionType = 1 OR CPTTransactionType=3) AND CPTMoneyType = 1 THEN 'expense_iqd'
                END AS type,
                SUM(CPTMoney) as total_money
            FROM
                capital
            WHERE
                $add_date
                CPTDeleted = 0 AND
                CPTPaidType=0
            GROUP BY
                CPTOperationType,CPTMoneyType
        ","key_all");
        for ($i=0;$i<count($total);++$i){
            foreach($arr as $key=>$value){
                if($total[$i]["type"]==$key){
                    $arr[$key]=$total[$i]["total_money"];
                    break;
                }
            }
        }
        return $arr;
    }
    function changePrice($price,$usd=1,$pointShow=1){
        if($price==0){//agar money IQD bu ean price 0 bu aua rast price ka return bka
            return number_format($price,0,'.', ($pointShow==1?',':''));
        }else{
            if($usd==1){
                $number=$price+0;
            }else{
                $number=((float)$price/(float)$usd)+0;//convert IQD to USD 
            }
            //this 0 to convert 12.500 ==> 12.5
            $parts= explode('.', (string)$number);//bashe zhmara u point kae lek jea dakataua
            if(count($parts)==1){//agar point e nabu aua eaksar u zhmara kae return bka
                return number_format($parts[0],0,'.',($pointShow==1?',':''));
            }else{//agar point habu
                if(strlen($parts[1])>3){//agar .9484444 ==> .948 bkagrenaua

                    //floatval
                    return number_format(($parts[0].".".$parts[1]),3,'.',($pointShow==1?',':''));
                }else if(strlen($parts[1])<=3){//agar la 3 point kamtr bu ba length point kan be garenaua
                    return number_format(($parts[0].".".$parts[1]),strlen($parts[1]),'.',($pointShow==1?',':''));
                }
            }
        }
        
    }
?>