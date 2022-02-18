<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "expenses_group";
            $primaryKey = "EXGID";
            $where="EXGDeleted=0 ";
            $columns =  array(
                array( "db" => "EXGID", "dt" => 0 ),  
                array( "db" => "EXGName", "dt" => 1 ),  
                array( "db" => "EXGNote", "dt" => 2 ,"formatter"=>function($d){
                    return html_entity_decode($d);
                }),  
                array( "db" => "EXGDeleted", "dt" => 3 ),  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            $validation=new class_validation($_POST,"EXG");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->return_data2(array(
                "tablesName"=>array("expenses_group"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"EXGName","operation"=>"=","value"=>$EXGName,"link"=>"and"),
                    array("columnName"=>"EXGDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,7);
                exit;
            }
            $res = $database->insert_data2("expenses_group",$data);
            if ($res) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            //testData($_POST,0);
            $validation=new class_validation($_POST,"EXG");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->return_data2(array(
                "tablesName"=>array("expenses_group"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"EXGDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"EXGName","operation"=>"=","value"=>$EXGName,"link"=>"and"),
                    array("columnName"=>"EXGID","operation"=>"!=","value"=>$EXGID,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,7);
                exit;
            }
            $res = $database->update_data2(array(
                "tablesName"=>"expenses_group",
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