<?php

	include_once "../_general/backend/_header.php";

	if (isset($_POST['type']) || isset($_GET['type'])){
		//change by Niyaz
		if( isset($_GET['type']) && ($_GET['type'] == "load")){
			$table  =  '(
				SELECT
					KEYID,
					KEYName,
					LANID,
					LANName,
					LANSymbol,
					LANDir,
					LAKID,
					LAKFORIDLAN,
					LAKFORIDKEY,
					LAKTranslated
				FROM
					keywords  KW
					LEFT JOIN languages L ON (L.LANDeleted=0)
					LEFT JOIN languages_keywords LKW ON (
						LKW.LAKFORIDKEY = KW.KEYID AND 
						LKW.LAKFORIDLAN = L.LANID AND 
						LKW.LAKDeleted=0
					)
				WHERE
					KW.KEYDeleted = 0 
					'.(isset($_GET['id'])? ' AND L.LANID='.$_GET['id']:'').' 
			) AS ALLTBL';
			$primaryKey = 'KEYID';
			$columns = array(
			    array( 'db' => 'KEYID', 'dt' => 0 ),
			    array( 'db' => 'KEYName', 'dt' => 1 ),
			    array( 'db' => 'LAKTranslated', 'dt' => 2 ),
			    array( 'db' => 'LANName', 'dt' => 3 ),
			    array( 'db' => 'LANSymbol', 'dt' => 4 ),
			    array( 'db' => 'LANDir', 'dt' => 5 ),
			    array( 'db' => 'LANID', 'dt' => 6 ),
			    array( 'db' => 'LAKID', 'dt' => 7 )   
			);
			echo json_encode(
			    SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, '' )
			);
			exit;
		}
		//change by Niyaz
		if ($_POST['type'] == "delete") {
			$validation=new class_validation($_POST,"KEY");
			$data=$validation->returnLastVersion();
			extract($data);
			//testData($data,0);
			$database->delete_data3(array(
                "tablesName"=>"languages_keywords",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"LAKFORIDKEY","value"=>$KEYID)
                ),
                "conditions"=>array(
                    array("columnName"=>"LAKDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"LAK"
            ));
			if(is_int($res) && $res==-1){
				//echo jsonMessages(false,8);
				//exit;
			}else if (!$res) {
				echo jsonMessages(false,1);
				exit;
			}
			//testData($data,0);
			$res = $database->delete_data2(array(
				"tablesName"=>"keywords",
				"userData"=>$data,
				"conditions"=>array()
			));
			if ($res) {
				echo jsonMessages(true,3);
				exit;
			}else{
				echo jsonMessages(false,1);
				exit;
			}
		}
		//change by Niyaz
		if ($_POST['type']=="save_single") {
			extract($_POST);

			$res = $database->return_data2(array(
				"tablesName"=>array("keywords"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"KEYName","operation"=>"=","value"=>$KEYName,"link"=>"and"),
					array("columnName"=>"KEYDeleted","operation"=>"=","value"=>0,"link"=>"and"),
					array("columnName"=>"KEYID","operation"=>"=","value"=>$KEYID,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"row_count"
			));
			if ($res==0) {
				$res = $database->update_data2(array(
					"tablesName"=>"keywords",
					"userData"=>array("KEYName"=>$KEYName,"PageName"=>$PageName,"primaryKey"=>array("key"=>"KEYID","value"=>$KEYID)),
					"conditions"=>array()
				));
				if (!$res){
					echo jsonMessages(false,1);
					exit;
		     	}else{
					echo jsonMessages(true,1);
					exit;
				 }
			}
			//testData($_POST,0);

			$res = $database->return_data2(array(
				"tablesName"=>array("languages_keywords"),
				"columnsName"=>array("count(*) as total,LAKID"),
				"conditions"=>array(
					array("columnName"=>"LAKFORIDLAN","operation"=>"=","value"=>$LAKFORIDLAN,"link"=>"and"),
					array("columnName"=>"LAKDeleted","operation"=>"=","value"=>0,"link"=>"and"),
					array("columnName"=>"LAKFORIDKEY","operation"=>"=","value"=>$KEYID,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"key"
			));
			//testData($res,0);
			extract($res);
			if($total==0){
				$res = $database->insert_data2("languages_keywords",array(
					"LAKFORIDLAN"=>$LAKFORIDLAN,
					"LAKFORIDKEY"=>$KEYID,
					"LAKTranslated"=>$LAKTranslated,
					"PageName"=>$PageName
				));

				if ($res){
					echo jsonMessages(true,1);
					exit;
				}else{
					echo jsonMessages(false,1);
					exit;
				}
			}else{
				$res = $database->update_data2(array(
					"tablesName"=>"languages_keywords",
					"userData"=>array("LAKTranslated"=>$LAKTranslated,"PageName"=>$PageName,"primaryKey"=>array("key"=>"LAKID","value"=>$LAKID)),
					"conditions"=>array()
				));
				if (!$res){
					echo jsonMessages(false,1);
					exit;
		     	}else{
					echo jsonMessages(true,1);
					exit;
				}
			}
  			
		}
		//change by Niyaz
		if ($_POST['type'] == "create") {
			//testData($_POST,0);
			$validation=new class_validation($_POST,"KEY");
			$data=$validation->returnLastVersion();
			extract($data);
			$res = $database->return_data2(array(
				"tablesName"=>array("keywords"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"KEYDeleted","operation"=>"=","value"=>0,"link"=>"and"),
					array("columnName"=>"KEYName","operation"=>"=","value"=>$KEYName,"link"=>""),
				),
				"others"=>"",
				"returnType"=>"key_all"
			));
			if (count($res)>0) {
				echo jsonMessages(false,2);
				exit;
			}else{	
				$res = $database->insert_data2("keywords",$data);
				if ($res) {	
					echo jsonMessages(true,2);
					exit;
				}else{
					echo jsonMessages(false,1);
					exit;
				}
			}
		}	

		if ($_POST['type'] == "createALLNotExist"){

			$keyword=json_decode($_POST["keyword"]);
			$keywordLen=count($keyword);
			for ($i=0; $i < $keywordLen; $i++) {
				
				$res = $database->return_data2(array(
					"tablesName"=>array("keywords"),
					"columnsName"=>array("*"),
					"conditions"=>array(
						array("columnName"=>"KEYDeleted","operation"=>"=","value"=>0,"link"=>"and"),
						array("columnName"=>"KEYName","operation"=>"=","value"=>trim($keyword[$i]),"link"=>""),
					),
					"others"=>"",
					"returnType"=>"key_all"
				));
				if (count($res)>0) {
					continue;
				}
				$res = $database->insert_data2("keywords",array(
					"KEYName"=>trim($keyword[$i]),
					"PageName"=>"/opt/lampp/htdocs/myproject/Keentech/RoyallMoll/views/translation.php"
				));
			}
		}

	}
	else{
		header("Location:../");
		exit;
	}
?>
