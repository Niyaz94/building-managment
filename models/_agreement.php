<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        function addingTodate($date,$year=0,$month=0,$day=0){
            return date("Y-m-d",strtotime("+".$year." year +".$month." month +".$day." day",strtotime($date)));
        }
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                SELECT
                    CUSName,
                    SOPNumber,
                    agreement.*,
                    ifnull((
                        SELECT
                            count(*)
                        FROM
                            agreementrent
                        WHERE
                            ARTPaidType<>0 AND
                            ARTRentAftDis<>0 AND
                            ARTDeleted=0 AND
                            ARTAGRFORID=AGRID
                    ),0) as totalPaid
                FROM
                    agreement,customer,shop
                WHERE
                    CUSID=AGRCUSFORID AND
                    SOPID=AGRSOPFORID AND
                    AGRDeleted=0
            ) as table1";
            $primaryKey = "AGRID";
            $where="";
            $columns =  array(
                array( "db" => "AGRID", "dt" => 0 ),  
                array( "db" => "CUSName", "dt" => 1),  
                array( "db" => "SOPNumber", "dt" => 2),  
                array( "db" => "AGRWorkype", "dt" => 3),  
                array( "db" => "AGRShopTitle", "dt" => 4),  
                array( "db" => "AGRDateStart", "dt" => 5),  
                array( "db" => "AGRDuration", "dt" => 6,"formatter"=>function($d,$row){
                    $splitDuration=explode(",",$d);
                    return date("Y-m-d",strtotime("+".$splitDuration[0]." year +".$splitDuration[1]." month",strtotime($row[5])));
                }),  
                array( "db" => "AGRShopRental", "dt" => 7), 
                array( "db" => "AGRServiceRental", "dt" => 8),
                array( "db" => "AGRType", "dt" => 9) ,
                array( "db" => "totalPaid", "dt" => 10) ,
                array( "db" => "AGROrderRow", "dt" => 11) 

            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            $fileUpload=uploadingImageFileV2($_FILES,"../_general/image/agreement/");
            if(!is_array($fileUpload)){
                echo $fileUpload;
                exit;
            }else{
                $_POST=array_merge($fileUpload,$_POST);
            }
            $validation=new class_validation($_POST,"AGR");
            $data=$validation->returnLastVersion();
            extract($data);
           // testData($data,0);
            $data["AGRPaymentStart"]=$data["AGRPaymentStart"]."-".explode("-",$data["AGRDateStart"])[2];
            $res = $database->insert_data2("agreement",$data);
            $res = $database->update_data2(array(
                "tablesName"=>"shop",
                "userData"=>array("SOPType"=>1,"PageName"=>$PageName,"primaryKey"=>array("key"=>"SOPID","value"=>$AGRSOPFORID)),
                "conditions"=>array()
            ));
            if ($res) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            $oldAgrement=$database->return_data2(array(
                "tablesName"=>array("agreement"),
                "columnsName"=>array("AGRSOPFORID","AGRWatchPaymentType"),
                "conditions"=>array(
                    array("columnName"=>"AGRID","operation"=>"=","value"=>$_POST["AGRID_UIZP"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            if($oldAgrement["AGRSOPFORID"]!=$_POST["AGRSOPFORID_UGRN"]){
                if($oldAgrement["AGRSOPFORID"]==0){
                    $totalElectric=$database->return_data2(array(
                        "tablesName"=>array("agreementrent"),
                        "columnsName"=>array("count(*) as total"),
                        "conditions"=>array(
                            array("columnName"=>"ARTAGRFORID","operation"=>"=","value"=>$_POST["AGRID_UIZP"],"link"=>"and"),
                            array("columnName"=>"ARTDeleted","operation"=>"=","value"=>0,"link"=>""),
                        ),
                        "others"=>"",
                        "returnType"=>"key"
                    ))["total"];
                    if($totalElectric>0){
                        echo jsonMessages2(false,"You can't edit the shop name because you add electric rent for it !!!");
                        exit;
                    }
                }
                $database->update_data2(array(
                    "tablesName"=>"shop",
                    "userData"=>array(
                        "SOPType"=>2,
                        "PageName"=>$_POST["PageName"],
                        "primaryKey"=>array("key"=>"SOPID","value"=>$oldAgrement["AGRSOPFORID"])
                    ),
                    "conditions"=>array()
                ));
                $database->update_data2(array(
                    "tablesName"=>"shop",
                    "userData"=>array(
                        "SOPType"=>1,
                        "PageName"=>$_POST["PageName"],
                        "primaryKey"=>array("key"=>"SOPID","value"=>$_POST["AGRSOPFORID_UGRN"])
                    ),
                    "conditions"=>array()
                ));
            }
            $fileUpload=uploadingImageFileV2($_FILES,"../_general/image/agreement/");
            if(!is_array($fileUpload)){
                echo $fileUpload;
                exit;
            }else{
                $_POST=array_merge($fileUpload,$_POST);
            }
            $validation=new class_validation($_POST,"AGR");
            $data=$validation->returnLastVersion();
            extract($data);
            $data["AGRPaymentStart"]=$data["AGRPaymentStart"]."-".explode("-",$data["AGRDateStart"])[2];
            $res = $database->update_data2(array(
                "tablesName"=>"agreement",
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
        if ($_POST["type"] == "addPayment"){
            extract($_POST);
            //return all agreement
            $agreement = $database->return_data2(array(
                "tablesName"=>array("agreement"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"AGRDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"AGRType","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            for($i=0,$iL=count($agreement);$i<$iL;$i++){
                extract($agreement[$i]);

                $splitEndDate=explode(",",$AGRDuration);
                $endDate=addingTodate($AGRDateStart,$splitEndDate[0],$splitEndDate[1]);
                //return all rent for this agreement
                $rent = $database->return_data2(array(
                    "tablesName"=>array("agreementrent"),
                    "columnsName"=>array("*"),
                    "conditions"=>array(
                        array("columnName"=>"ARTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                        array("columnName"=>"ARTRentType","operation"=>"<>","value"=>2,"link"=>"and"),
                        array("columnName"=>"ARTFutureType","operation"=>"=","value"=>0,"link"=>"and"),
                        array("columnName"=>"ARTAGRFORID","operation"=>"=","value"=>$AGRID,"link"=>""),
                    ),
                    "others"=>"order by ARTID desc limit 1",
                    "returnType"=>"key_all"
                ));
                $paymentDate="";//au variable baruare au mangae tedaea ka dabe kre bdaen
                //au if bas data e au mangae dabentaua
                if(empty($rent)){//this is the first rent
                    if(explode("-",$AGRDateStart)[0]<2010 || explode("-",$AGRPaymentStart)[0]<2010){
                        continue;
                    }
                    $splitAGRDateStart=explode("-",$AGRDateStart);
                    $splitAGRPaymentStart=explode("-",$AGRPaymentStart);
                    $paymentDate=$splitAGRPaymentStart[0]."-".$splitAGRPaymentStart[1]."-".$splitAGRDateStart[2];
                }else{//if we rented before
                    if(explode("-",$rent[0]["ARTDate"])[0]<2010){
                        continue;
                    }
                    $paymentDate=addingTodate($rent[0]["ARTDate"],0,1);
                }
                /*
                    au loop loe uae bakar det bzane kate kreakae hatea ean na 
                    bo nmuna aesta 2018-10-01 nakre system bche kre 2018-12-01 bdat
                */
                //$currentDate<=date("Y-m-d") ==> kre peshake
                //addingTodate($currentDate,0,1)<=date("Y-m-d") ==> kre pashke
                for($currentDate=$paymentDate;$currentDate<=date("Y-m-d");$currentDate=addingTodate($currentDate,0,1)){
                    $future_rent = $database->return_data2(array(
                        "tablesName"=>array("agreementrent"),
                        "columnsName"=>array("*"),
                        "conditions"=>array(
                            array("columnName"=>"ARTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                            array("columnName"=>"ARTDate","operation"=>"=","value"=>$currentDate,"link"=>"and"),
                            array("columnName"=>"ARTFutureType","operation"=>"=","value"=>1,"link"=>"and"),
                            array("columnName"=>"ARTAGRFORID","operation"=>"=","value"=>$AGRID,"link"=>""),
                        ),
                        "others"=>"",
                        "returnType"=>"key_all"
                    ));
                    $future_rentType=array_column($future_rent,"ARTRentType");

                    if($currentDate>$endDate){
                        break;
                    }else if($endDate==$currentDate){
                        $res = $database->update_data2(array(
                            "tablesName"=>"agreement",
                            "userData"=>array("AGRType"=>1,"PageName"=>$PageName,"primaryKey"=>array("key"=>"AGRID","value"=>$AGRID)),
                            "conditions"=>array()
                        ));
                    }
                    if(in_array(0,$future_rentType)){
                        for ($k=0; $k < count($future_rent); $k++) { 
                            if($future_rent[$k]["ARTRentType"]==0){
                                $res = $database->update_data2(array(
                                    "tablesName"=>"agreementrent",
                                    "userData"=>array(
                                        "ARTFutureType"=>0,
                                        "PageName"=>$PageName,
                                        "primaryKey"=>array("key"=>"ARTID","value"=>$future_rent[$k]["ARTID"])
                                    ),
                                    "conditions"=>array()
                                ));
                                break;
                            }
                        }
                    }else{
                        $res = $database->insert_data2("agreementrent",array(
                            "PageName"=>$PageName,
                            "ARTAGRFORID"=>$AGRID,
                            "ARTDate"=>$currentDate,
                            "ARTRent"=>$AGRShopRental,
                            "ARTRentAftDis"=>$AGRShopRental,
                            "ARTRentType"=>0
                        ));
                    }
                    if(in_array(1,$future_rentType)){
                        for ($k=0; $k < count($future_rent); $k++) { 
                            if($future_rent[$k]["ARTRentType"]==1){
                                $res = $database->update_data2(array(
                                    "tablesName"=>"agreementrent",
                                    "userData"=>array(
                                        "ARTFutureType"=>0,
                                        "PageName"=>$PageName,
                                        "primaryKey"=>array("key"=>"ARTID","value"=>$future_rent[$k]["ARTID"])
                                    ),
                                    "conditions"=>array()
                                ));
                                break;
                            }
                        }
                    }else{


                        $res = $database->insert_data2("agreementrent",array(
                            "PageName"=>$PageName,
                            "ARTAGRFORID"=>$AGRID,
                            "ARTDate"=>$currentDate,
                            "ARTRent"=>$AGRServiceRental,
                            "ARTRentAftDis"=>$AGRServiceRental,
                            "ARTRentType"=>1,
                            "ARTPaidType"=>($AGRServiceRental==0?2:0)
                        ));
                    }
                    if($AGRWatchPaymentType==1){
                        if(in_array(2,$future_rentType)){
                            for ($k=0; $k < count($future_rent); $k++) { 
                                if($future_rent[$k]["ARTRentType"]==2){
                                    $res = $database->update_data2(array(
                                        "tablesName"=>"agreementrent",
                                        "userData"=>array(
                                            "ARTFutureType"=>0,
                                            "PageName"=>$PageName,
                                            "primaryKey"=>array("key"=>"ARTID","value"=>$future_rent[$k]["ARTID"])
                                        ),
                                        "conditions"=>array()
                                    ));
                                    break;
                                }
                            }
                        }else{
                            $res = $database->insert_data2("agreementrent",array(
                                "PageName"=>$PageName,
                                "ARTAGRFORID"=>$AGRID,
                                "ARTDate"=>$currentDate,
                                "ARTRent"=>$AGRElectricRental,
                                "ARTRentAftDis"=>$AGRElectricRental,
                                "ARTRentType"=>2
                            ));
                        }
                    }
                    
                    
                    
                }
            }
        }
        if($_POST["type"] == "renew"){
            $AGRDuration =explode(",",$database->return_data2(array(
                "tablesName"=>array("agreement"),
                "columnsName"=>array("AGRDuration"),
                "conditions"=>array(
                    array("columnName"=>"AGRID","operation"=>"=","value"=>$_POST["AGRID_RIZP"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["AGRDuration"]);
            $newAGRDuration=($AGRDuration[0]+$_POST["EMPYearN"]).",".($AGRDuration[1]+$_POST["EMPMonthN"]);
            $res = $database->update_data2(array(
                "tablesName"=>"agreement",
                "userData"=>array(
                    "AGRType"=>0,
                    "AGRDuration"=>$newAGRDuration,
                    "AGRShopRental"=>$_POST["AGRShopRental_UIR"],
                    "AGRServiceRental"=>$_POST["AGRServiceRental_UIR"],
                    "PageName"=>$_POST["PageName"],
                    "primaryKey"=>array("key"=>"AGRID","value"=>$_POST["AGRID_RIZP"])
                ),
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
        if ($_POST["type"] == "delete") {	
            $validation=new class_validation($_POST,"AGR");
			$data=$validation->returnLastVersion();
            extract($data);	

            $AGRSOPFORID = $database->return_data2(array(
                "tablesName"=>array("agreement"),
                "columnsName"=>array("AGRSOPFORID"),
                "conditions"=>array(
                    array("columnName"=>"AGRID","operation"=>"=","value"=>$AGRID,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["AGRSOPFORID"];
            $res = $database->update_data2(array(
                "tablesName"=>"shop",
                "userData"=>array(
                    "SOPType"=>2,
                    "PageName"=>$_POST["PageName"],
                    "primaryKey"=>array("key"=>"SOPID","value"=>$AGRSOPFORID)
                ),
                "conditions"=>array()
            ));
            $database->delete_data3(array(
                "tablesName"=>"agreementrent",
                "userData"=>array(
                    "PageName"=>$_POST["PageName"],
                    "foreignKey"=>array("key"=>"ARTAGRFORID","value"=>$AGRID)
                ),
                "conditions"=>array(
                    array("columnName"=>"ARTDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"ART"
            ));


			$res = $database->delete_data2(array(
				"tablesName"=>"agreement",
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
        if ($_POST["type"] == "finish") {	
            $validation=new class_validation($_POST,"AGR");
			$data=$validation->returnLastVersion();
            extract($data);	

            $AGRSOPFORID = $database->return_data2(array(
                "tablesName"=>array("agreement"),
                "columnsName"=>array("AGRSOPFORID"),
                "conditions"=>array(
                    array("columnName"=>"AGRID","operation"=>"=","value"=>$AGRID,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["AGRSOPFORID"];
            $res = $database->update_data2(array(
                "tablesName"=>"shop",
                "userData"=>array(
                    "SOPType"=>2,
                    "PageName"=>$_POST["PageName"],
                    "primaryKey"=>array("key"=>"SOPID","value"=>$AGRSOPFORID)
                ),
                "conditions"=>array()
            ));
			$res = $database->update_data2(array(
                "tablesName"=>"agreement",
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
    }else{
        header("Location:../");
        exit;
    }

?>
