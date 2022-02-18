<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                SELECT
                    SOPNumber,
                    SOPType,
                    WTCID,
                    WTCName,
                    WTCDeleted,
                    ifnull((
                        SELECT
                            AGRWatchPaymentType	
                        FROM
                            agreement
                        WHERE
                            AGRType=0 AND
                            AGRDeleted=0 AND
                            AGRSOPFORID=SOPID
                    ),0) as AGRWatchPaymentType,
                    WTCSOPFORID
                FROM
                    watch,
                    shop
                WHERE
                    SOPID=WTCSOPFORID and 
                    SOPDeleted=0 AND
                    WTCDeleted=0
            ) as table1";
            $primaryKey = "WTCID";
            $where="";
            $columns =  array(
                array( "db" => "WTCID", "dt" => 0 ), 
                array( "db" => "SOPNumber", "dt" => 1 ),  
                array( "db" => "WTCName", "dt" => 2 ),  
                array( "db" => "SOPType", "dt" => 3 ),
                array( "db" => "WTCSOPFORID", "dt" => 4 ),    
                array( "db" => "AGRWatchPaymentType", "dt" => 5 )   
 
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            //testData($_POST,0);
            $validation=new class_validation($_POST,"WTC");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->insert_data2("watch",$data);
            if ($res) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            $validation=new class_validation($_POST,"WTC");
            $data=$validation->returnLastVersion();
            extract($data);
            //testData($data,0);
            $res = $database->return_data2(array(
                "tablesName"=>array("watch"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"WTCName","operation"=>"=","value"=>$WTCName,"link"=>"and"),
                    array("columnName"=>"WTCDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"WTCSOPFORID","operation"=>"=","value"=>$WTCSOPFORID,"link"=>"and"),
                    array("columnName"=>"WTCID","operation"=>"!=","value"=>$WTCID,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,7);
                exit;
            }
            $res = $database->update_data2(array(
                "tablesName"=>"watch",
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
    