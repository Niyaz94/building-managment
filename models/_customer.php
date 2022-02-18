<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
                    $table = "(
                        select 
                            customer.*,
                            (
                                SELECT
                                    count(AGRCUSFORID)
                                FROM
                                    agreement
                                WHERE
                                    AGRCUSFORID=CUSID AND
                                    AGRDeleted=0
                            ) as totalagreement
                        from
                            customer
                        where
                            CUSDeleted<>1
                    ) as table1";
                    $primaryKey = "CUSID";
                    $where="";
                    $columns =  array(
                        array( "db" => "CUSID", "dt" => 0 ),
                        array( "db" => "CUSName", "dt" => 1 ),  
                        array( "db" => "CUSAddress", "dt" => 2 ),  
                        array( "db" => "CUSPhone", "dt" => 3 ),  
                        array( "db" => "CUSEmail", "dt" => 4 ),  
                        array( "db" => "totalagreement", "dt" => 5 ) //this is use to not delete customer that have agreement
                    );
                    echo json_encode(
                        SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
                    );
                    exit;
        }
        if ($_POST["type"] == "create") {	
            $fileUpload=uploadingImageFileV2($_FILES,"../_general/image/customer/");
            if(!is_array($fileUpload)){
                echo $fileUpload;
                exit;
            }else{
                $_POST=array_merge($fileUpload,$_POST);
            }
            $validation=new class_validation($_POST,"CUS");
            $data=$validation->returnLastVersion();
            $res = $database->insert_data2("customer",$data);
            if ($res) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            //testData($_FILES,0);
            $fileUpload=uploadingImageFileV2($_FILES,"../_general/image/customer/");
            if(!is_array($fileUpload)){
                echo $fileUpload;
                exit;
            }else{
                $_POST=array_merge($fileUpload,$_POST);
            }
            $validation=new class_validation($_POST,"CUS");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->update_data2(array(
                "tablesName"=>"customer",
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
    