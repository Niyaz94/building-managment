<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){  
            
            $max=$database->return_data("
                SELECT
                    ifnull(max(WNTID),0) as max
                FROM
                    shop,
                    watch,
                    watchrent,
                    agreementrent
                WHERE
                    WTCID=WNTWTCFORID AND
                    SOPID=WTCSOPFORID AND
                    ARTID=WNTARTFORID AND
                    SOPID=".$_GET["SOPID"]." AND
                    WTCID=".$_GET["WTCID"]." AND
                    SOPDeleted=0 AND
                    WTCDeleted=0 AND
                    WNTDeleted<>1
            ","key")["max"];
            $table = "(
                SELECT
                    WNTID,
                    SOPID,
                    WNTWTCFORID,
                    WNTARTFORID,
                    SOPNumber,
                    SOPElectricRate,
                    WTCName,
                    WNTOldRead,
                    WNTNewRead,
                    ARTPaidType,
                    ARTDate,
                    WNTDeleted , 
                    CASE 
                        WHEN WNTID = $max  THEN '1' 
                        WHEN WNTID <> $max  THEN '0' 
                    END AS edit_last
                FROM
                    shop,
                    watch,
                    watchrent,
                    agreementrent
                WHERE
                    WTCID=WNTWTCFORID AND
                    SOPID=WTCSOPFORID AND
                    ARTID=WNTARTFORID AND
                    SOPID=".$_GET["SOPID"]." AND
                    WTCID=".$_GET["WTCID"]." AND
                    SOPDeleted=0 AND
                    WTCDeleted=0 AND
                    WNTDeleted<>1
            ) as table1";
            $primaryKey = "WNTID";
            $where="";
            $columns =  array(
                array( "db" => "WNTID", "dt" => 0 ),  
                array( "db" => "SOPID", "dt" => 1 ),  
                array( "db" => "WNTWTCFORID", "dt" => 2 ),
                array( "db" => "SOPNumber", "dt" => 3 ),  
                array( "db" => "WTCName", "dt" => 4 ),  
                array( "db" => "SOPElectricRate", "dt" => 5 ),  
                array( "db" => "WNTOldRead", "dt" => 6 ),  
                array( "db" => "WNTNewRead", "dt" => 7 ),  
                array( "db" => "ARTDate", "dt" => 8 ,"formatter"=>function($d){
                    $new=explode("-",$d);
                    return $new[0]."-".$new[1];
                }),  
                array( "db" => "ARTPaidType", "dt" => 9 ),  
                array( "db" => "WNTARTFORID", "dt" => 10 ) ,
                array( "db" => "WNTDeleted", "dt" => 11 ), 
                array( "db" => "edit_last", "dt" => 12 ) 
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            //check if entering date greater than the date inside database
            $maxDate = $database->return_data("
                SELECT
                    max(ARTDate) as max
                FROM
                    agreementrent,
                    watchrent
                WHERE
                    WNTARTFORID=ARTID AND
                    ARTDeleted=0 AND
                    WNTDeleted=0 AND
                    ARTAGRFORID=".$_POST["ARTAGRFORID_IIZN"]." AND
                    WNTWTCFORID=".$_POST["WNTWTCFORID_IIZN"]."
            ","key")["max"];
            if($maxDate!=null){
                $_POST["ARTDate_IKZN"]=$_POST["ARTDate_IKZN"]."-".explode("-",$maxDate)[2];
                if($maxDate>=$_POST["ARTDate_IKZN"]){
                    echo jsonMessages(false,13);
                    exit;
                }   
            }else{
                $maxDate=$database->return_data2(array(
                    "tablesName"=>array("agreement"),
                    "columnsName"=>array("AGRPaymentStart"),
                    "conditions"=>array(
                        array("columnName"=>"AGRID","operation"=>"=","value"=>$_POST["ARTAGRFORID_IIZN"],"link"=>""),
                    ),
                    "others"=>"",
                    "returnType"=>"key"
                ))["AGRPaymentStart"];
                $_POST["ARTDate_IKZN"]=$_POST["ARTDate_IKZN"]."-".explode("-",$maxDate)[2];
                if($maxDate>$_POST["ARTDate_IKZN"]){
                    echo jsonMessages(false,13);
                    exit;
                }   
            }            
            $validation=new class_validation($_POST,"ART");
            $data=$validation->returnLastVersion();
            extract($data);
            $SOPElectricRate = $database->return_data2(array(
                "tablesName"=>array("shop"),
                "columnsName"=>array("SOPElectricRate"),
                "conditions"=>array(
                    array("columnName"=>"SOPID","operation"=>"=","value"=>$_POST["SOPID"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["SOPElectricRate"];
            $validation=new class_validation($_POST,"WNT");
            $wntdata=$validation->returnLastVersion();
            extract($wntdata);
            $data["ARTRent"]=$SOPElectricRate*($WNTNewRead-$WNTOldRead);
            $data["ARTRentAftDis"]=$SOPElectricRate*($WNTNewRead-$WNTOldRead);
            $WNTARTFORID= $database->insert_data2("agreementrent",$data);
            if ($WNTARTFORID) {	
                $wntdata["WNTARTFORID"]=$WNTARTFORID;
                $res = $database->insert_data2("watchrent",$wntdata);
                if ($res) {	
                    echo jsonMessages(true,2);
                    exit;
                }else{
                    echo jsonMessages(false,1);
                    exit;
                }
            }else{
                echo jsonMessages(false,1);
                exit;
            } 
        }
        if ($_POST["type"] == "update") {
            $oldARTDate = $database->return_data2(array(
                "tablesName"=>array("agreementrent"),
                "columnsName"=>array("ARTDate"),
                "conditions"=>array(
                    array("columnName"=>"ARTID","operation"=>"=","value"=>$_POST["WNTARTFORID_UIRN"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["ARTDate"];
            //theck for date below this date
            $_POST["ARTDate_UKRN"]=$_POST["ARTDate_UKRN"]."-".explode("-",$oldARTDate)[2];
            if($oldARTDate>$_POST["ARTDate_UKRN"]){
                echo jsonMessages(false,13);
                exit;
            }
            $validation=new class_validation($_POST,"WNT");
            $datawatch=$validation->returnLastVersion();
            extract($datawatch);

            $SOPElectricRate = $database->return_data2(array(
                "tablesName"=>array("shop"),
                "columnsName"=>array("SOPElectricRate"),
                "conditions"=>array(
                    array("columnName"=>"SOPID","operation"=>"=","value"=>$_POST["SOPID"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["SOPElectricRate"];
            $res = $database->update_data2(array(
                "tablesName"=>"watchrent",
                "userData"=>$datawatch,
                "conditions"=>array()
            ));
            if ($res) {
                $validation=new class_validation($_POST,"ART");
                $data=$validation->returnLastVersion();
                $data["primaryKey"]=array("key"=>"ARTID","value"=>$WNTARTFORID);
                $data["ARTRent"]=$SOPElectricRate*($WNTNewRead-$WNTOldRead);
                $data["ARTRentAftDis"]=$SOPElectricRate*($WNTNewRead-$WNTOldRead);
                $data["ARTRentD"]=0;
                $resART = $database->update_data2(array(
                    "tablesName"=>"agreementrent",
                    "userData"=>$data,
                    "conditions"=>array()
                ));
                if ($resART) {
                    echo jsonMessages(true,1);
                    exit;
                }else{
                    echo jsonMessages(false,1);
                    exit;
                }
                
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "delete") {
           // testData($_POST,0);	
            
            $validation=new class_validation($_POST,"ART");
			$dataART=$validation->returnLastVersion();
            
			$resART = $database->delete_data2(array(
				"tablesName"=>"agreementrent",
				"userData"=>$dataART,
				"conditions"=>array()
            ));
            
            $validation=new class_validation($_POST,"WNT");
			$dataWNT=$validation->returnLastVersion();
            
			$resWNT = $database->delete_data2(array(
				"tablesName"=>"watchrent",
				"userData"=>$dataWNT,
				"conditions"=>array()
            ));
            
			if ($resWNT && $resART) {
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
    