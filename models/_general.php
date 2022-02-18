<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"])){
        
        if ($_POST["type"] == "getData") {	
            //testData($_POST,0);
            extract($_POST);
            $res = $database->return_data3(array(
                "tablesName"=>is_array($table)?$table:array($table),
                "columnsName"=>json_decode($columns,true),
                "conditions"=>json_decode($condition,true),
                "others"=>"",
                "returnType"=>"key"
            ));
            //testData($res,0);
            if($res){
                echo html_entity_decode(jsonMessages2(true,$res));
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "delete") {	
            $validation=new class_validation($_POST,$_POST["symbol"]);
			$data=$validation->returnLastVersion();
            extract($data);	
            //testData($data,0);
			$res = $database->delete_data2(array(
				"tablesName"=>$_POST["table"],
				"userData"=>$data,
				"conditions"=>array()
			));
			if ($res) {
				echo jsonMessages(true,1);
				exit;
			}else{
				echo jsonMessages(false,1);
				exit;
			} 
        }
        if ($_POST['type']=="returnShopNumber") {
            extract($_POST);
			$result=$database->return_data("
				SELECT
                    SOPID,
                    SOPNumber,
                    SOPArea,
                    SOPFloor,
                    case 
                        SOPCategory
                    when 
                        0 then 'Shop'
                    when 
                        1 then 'Stand'
                    when 
                        2 then 'Office'
                    end as SOPCategory
				FROM
                    shop
				WHERE
                    SOPDeleted=0 AND
                    $condition
                    SOPNumber like '%$SOPNumber%' 
                limit 10
            ","key_all");			
			if(count($result)>0){
				echo json_encode($result);
				exit;
			}else{
				$result=array(array("SOPID"=>0,"SOPNumber"=>"Shop Not Found","SOPArea"=>0,"SOPFloor"=>0,"SOPCategory"=>"Not Found"));
				echo json_encode($result);
				exit;
			}   		 
        }
        if ($_POST['type']=="returnExpensesCategory") {
            extract($_POST);
			$result=$database->return_data("
				SELECT
                    EXGID,
                    EXGName,
                    EXGNote
				FROM
                    expenses_group
				WHERE
                    EXGDeleted=0 AND
                    EXGID<>1 AND
                    $condition
                    EXGName like '%$EXGName%' 
                limit 10
            ","key_all");	
            for($i=0;$i<count($result);++$i){
                $result[$i]["EXGNote"]=html_entity_decode($result[$i]["EXGNote"]);
            }		
			if(count($result)>0){
				echo json_encode($result);
				exit;
			}else{
				$result=array(array("EXGID"=>0,"EXGName"=>"Not Fount Category","EXGNote"=>"Not Found"));
				echo json_encode($result);
				exit;
			}   		 
        }
        if ($_POST['type']=="returnCustomerID") {
            extract($_POST);
			$result=$database->return_data("
				SELECT
                    CUSID,
                    CUSName,
                    CUSAddress,
                    CUSPhone
				FROM
                    customer
				WHERE
                    CUSDeleted=0 AND
                    CUSName like '%$SOPNumber%' 
                limit 10
            ","key_all");			
			if(count($result)>0){
				echo json_encode($result);
				exit;
			}else{
				$result=array(array("CUSID"=>0,"CUSName"=>"Customer Not Found","CUSAddress"=>"","CUSPhone"=>""));
				echo json_encode($result);
				exit;
			}   		 
        }
        if ($_POST['type']=="updateLanguage"){
            $_SESSION["languageSetting"]["CSS"]=$_POST["CSS"];
            $_SESSION["languageSetting"]["DIR"]=$_POST["DIR"];
            $_SESSION["languageSetting"]["ID"]=$_POST["ID"];
            $_SESSION["languageSetting"]["LANG"]=trim($_POST["LANG"]);
            echo 1;
        }
        if ($_POST['type']=="navbarData"){
            $data=$database->return_data("
                SELECT
                    AGRID,
                    AGRWorkype,
                    AGRShopTitle,
                    '".date("Y-m")."' as date,
                    group_concat(
                        ARTPaidType,'_',
                        (case ARTRentType
                        when 0 then 'Rent'
                        when 1 then 'Service'
                        when 2 then 'Electric'
                        end )
                        ) as type,
                    count(*) as total
                FROM
                    agreement,
                    agreementrent
                WHERE
                    AGRDeleted=0 AND
                    ARTDeleted=0 AND
                    AGRID=ARTAGRFORID AND
                    SUBSTRING(ARTDate,1,7)='".date("Y-m")."' AND
                    ARTPaidType<>2
                group by 
                    AGRID
            ","key_all");

            $len=count($data);
            $new=array();
            for ($i=0; $i < $len; $i++) { 
                if($data[$i]["total"]>0){
                    $text="";
                    $split=explode(",",$data[$i]["type"]);
                    for ($j=0; $j < count($split); $j++) {
                        $splitContent=explode("_",$split[$j]);
                        if($splitContent[0]==0) {
                            $text.="<span class='label bg-danger-300' style='margin-right:10px;margin-bottom:2px;font-size:14px;'>".$splitContent[1]." Not Paid</span>";
                        }else if($splitContent[0]==1){
                            $text.="<span class='label bg-success-300' style='margin-right:10px;font-size:14px;margin-bottom:2px;'>".$splitContent[1]." Partial Paid</span>";
                        }else if($splitContent[0]==2){
                            $text.="<span class='label bg-blue-300' style='margin-right:10px;font-size:14px;margin-bottom:2px;'>".$splitContent[1]." Paid</span>";
                        }
                    }
                    $data[$i]["text"]=$text;
                    array_push($new,$data[$i]);
                }
            }
            echo json_encode($new);
        }
    }else{
        header("Location:../");
        exit;
    }
?>