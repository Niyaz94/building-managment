<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "shop";
            $primaryKey = "SOPID";
            $where='SOPDeleted="0"';

            $columns =  array(
                array( "db" => "SOPID", "dt" => 0),  
                array( "db" => "SOPNumber", "dt" => 1 ),  
                array( "db" => "SOPArea", "dt" => 2 ),  
                array( "db" => "SOPFloor", "dt" => 3 ),  
                array( "db" => "SOPCategory", "dt" => 4 ),  
                array( "db" => "SOPType", "dt" => 5 )
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            $validation=new class_validation($_POST,"SOP");
            $data=$validation->returnLastVersion();
            extract($data);
            //testData($data,0);
            $res = $database->return_data2(array(
                "tablesName"=>array("shop"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"SOPDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"SOPNumber","operation"=>"=","value"=>$SOPNumber,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,7);
                exit;
            }
            $res = $database->insert_data2("shop",$data);
            if ($res) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") { 
            $result=exec('C:/xampp/mysql/bin/mysqldump.exe --user=root --password=root kt_royalmoll  > D:/royal.sql');
            exit;
            $validation=new class_validation($_POST,"SOP");
            $data=$validation->returnLastVersion();
            extract($data);
           // testData($data,0);
            $res = $database->return_data2(array(
                "tablesName"=>array("shop"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"SOPNumber","operation"=>"=","value"=>$SOPNumber,"link"=>"and"),
                    array("columnName"=>"SOPDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"SOPID","operation"=>"!=","value"=>$SOPID,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,7);
                exit;
            }
            $res = $database->update_data2(array(
                "tablesName"=>"shop",
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

function backup_tables($host,$user,$pass,$name,$tables = '*',$database){
	if($tables == '*'){
        $data=$database->return_data("
            SHOW TABLES
        ","index_all");
        $tables=array();
        for ($i=0,$il=count($data); $i <$il ; $i++) { 
            array_push($tables,$data[$i][0]);
        }
	}else{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
    }
	
	//cycle through
	foreach($tables as $table){
		$result = $database->return_data('SELECT * FROM '.$table,"index_all");
		$num_fields = count($result);
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysqli_fetch_row(mysqli_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) {
			while($row = mysqli_fetch_row($result)){
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j < $num_fields; $j++) {
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j < ($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
    }
    testData($tables);

	
	//save file
	$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
	fwrite($handle,$return);
	fclose($handle);
}
?>
                        