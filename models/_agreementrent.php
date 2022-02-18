<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        function addingTodate($date,$year=0,$month=0,$day=0){
            return date("Y-m-d",strtotime("+".$year." year +".$month." month +".$day." day",strtotime($date)));
        }
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                SELECT
                    ARTID,
                    ARTDate,
                    ARTRent,
                    ARTRentD,
                    ARTRentAftDis,
                    ARTRentType,
                    ARTPaidType,
                    IF(ARTDate<AGRPaymentStart,1,0) as delete_type
                FROM
                    agreement,
                    agreementrent
                WHERE
                    AGRDeleted = 0 AND 
                    ARTDeleted = 0 AND 
                    AGRID = ARTAGRFORID AND 
                    AGRID = ".$_GET["agrid"]."
            )as table1";
            $primaryKey = "ARTID";
            $where="";
            $columns =  array(
                array( "db" => "ARTID", "dt" => 0 ),  
                array( "db" => "ARTDate", "dt" => 1 ),  
                array( "db" => "ARTRentType", "dt" => 2 ),  
                array( "db" => "ARTRent", "dt" => 3 ),  
                array( "db" => "ARTRentAftDis", "dt" => 4),                 
                array( "db" => "ARTPaidType", "dt" => 5 ),
                array( "db" => "delete_type", "dt" => 6 )
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "update") {
            $validation=new class_validation($_POST,"ART");
            $data=$validation->returnLastVersion();
            extract($data);
            //$data["ARTRentAftDis"]=number_format($_POST["ARTRent_UIRA"]-$_POST["ARTRentD_UIRN"],0,'.','');
            $data["ARTRentAftDis"]=$_POST["ARTRentD_UIRN"];
            if($data["ARTRentAftDis"]==0){
                $data["ARTPaidType"]=2;
            }else{
                $data["ARTPaidType"]=0;
            }
            $res = $database->update_data2(array(
                "tablesName"=>"agreementrent",
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
        if($_POST["type"]=="futureInsert"){
            extract($_POST);
            $agreement = $database->return_data2(array(
                "tablesName"=>array("agreement"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"AGRID","operation"=>"=","value"=>$AGRID,"link"=>"and "),
                    array("columnName"=>"AGRDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));

            $day=explode("-",$agreement["AGRDateStart"])[2];
            $month=json_decode($future_month);
            $type=json_decode($future_type);
            $new_date=array();
            for ($j=0,$jL=count($type); $j <$jL ; $j++) { 
                for ($i=0,$iL=count($month); $i < $iL; $i++) { 
                    array_push($new_date,array(
                        "date"=>$future_year."-".(strlen($month[$i])==1?"0".$month[$i]:$month[$i])."-".$day,
                        "type"=>$type[$j],
                        "insert"=>0
                    ));
                }
            }
            $agreementrent=$database->return_data2(array(
                "tablesName"=>array("agreementrent"),
                "columnsName"=>array("ARTID","ARTDate","ARTRentType"),
                "conditions"=>array(
                    array("columnName"=>"ARTAGRFORID","operation"=>"=","value"=>$AGRID,"link"=>"and"),
                    array("columnName"=>"ARTDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            for ($i=0,$iL=count($new_date); $i < $iL; $i++) { 
                for ($j=0,$jL=count($agreementrent); $j < $jL; $j++) {
                    if($new_date[$i]["date"]==$agreementrent[$j]["ARTDate"] && $new_date[$i]["type"]==$agreementrent[$j]["ARTRentType"]){
                        $new_date[$i]["insert"]=1;
                        break;
                    } 
                }
            }
            $splitEndDate=explode(",",$agreement["AGRDuration"]);
            $endDate=addingTodate($agreement["AGRDateStart"],$splitEndDate[0],$splitEndDate[1]);
            $no_inserted=0;
            //
            for ($i=0,$iL=count($new_date); $i < $iL; $i++) { 
                if($new_date[$i]["insert"]==0 && $new_date[$i]["date"]>=$agreement["AGRDateStart"] && $endDate>=$new_date[$i]["date"]){
                    $money=$agreement["AGRShopRental"];
                    if($new_date[$i]["type"]==1){
                        $money=$agreement["AGRServiceRental"];
                    }else if($new_date[$i]["type"]==2){
                        $money=$agreement["AGRElectricRental"];
                    }
                    $database->insert_data2("agreementrent",array(
                        "PageName"=>$PageName,
                        "ARTAGRFORID"=>$AGRID,
                        "ARTDate"=>$new_date[$i]["date"],
                        "ARTRent"=>$money,
                        "ARTRentAftDis"=>$money,
                        "ARTRentType"=>$new_date[$i]["type"],
                        "ARTPaidType"=>0,
                        "ARTFutureType"=>1
                    ));
                }else{
                    ++$no_inserted;
                }
            }
            echo jsonMessages2(true,$no_inserted);
            exit;
        }
    }else{
        header("Location:../");
        exit;
    }
?>