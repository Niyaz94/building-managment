<?php
	include_once '_general/backend/returnDataFromFile.php'; 
	require_once "db_connect.php";
	$_returnDataFromFile=new _returnDataFromFile("permission");
	date_default_timezone_set($_SESSION['SYSTimezone']);

	if(isset($_SESSION["languageSetting"])){
		$languageSetting=array(
			"CSS"=>$_SESSION["languageSetting"]["CSS"],
			"LANG"=>$_SESSION["languageSetting"]["LANG"],
			"DIR"=>$_SESSION["languageSetting"]["DIR"],
			"ID"=>$_SESSION["languageSetting"]["ID"]
		);
	}else{
		$languagesCheck = $database->return_data2(array(
			"tablesName"=>array("languages"),
			"columnsName"=>array("LANID","LANName","LANDir"),
			"conditions"=>array(
				array("columnName"=>"LANISDefualt","operation"=>"=","value"=>1,"link"=>"And"),
				array("columnName"=>"LANDeleted","operation"=>"=","value"=>0,"link"=>""),
			),
			"others"=>"",
			"returnType"=>"key"
		));
		if(isset($languagesCheck["LANID"])){
			$languageSetting=array(
				"ID"=>$languagesCheck["LANID"],
				"CSS"=>$languagesCheck["LANDir"]=="ltr"?"css_ltr":"css",
				"LANG"=>$languagesCheck["LANName"],
				"DIR"=>$languagesCheck["LANDir"]
			);
		}else{
			$languageSetting=array(
				"ID"=>1,
				"CSS"=>"css_ltr",
				"LANG"=>"English",
				"DIR"=>"ltr"
			);
		}
		
	}
	//print_r($languageSetting);
	$op  = array();
	$res = $database->return_data("
		SELECT 
			KEYName,
			LAKTranslated,
			LANName,
			LANDir 
		FROM 
			keywords KW 
			LEFT JOIN languages L ON (L.LANDeleted=0 )
			LEFT JOIN languages_keywords LKW ON (
				LKW.LAKDeleted = 0  AND 
				LKW.LAKFORIDKEY = KW.KEYID AND
				LKW.LAKFORIDLAN = L.LANID
			)
		WHERE 
			KW.KEYDeleted = 0 AND 
			L.LANID =".$languageSetting['ID']
	,"key_all");
	$_SESSION["languageSetting"]=$languageSetting;
	if ($res) {
		for ($i=0;$i<count($res);++$i) {					
			$result['KEYName'] 			 = trim($res[$i]['KEYName']);
			$result['LAKTranslated']= trim($res[$i]['LAKTranslated']);
			$result['LANName'] 		     = trim($res[$i]['LANName']);
			$result['LANDir'] 		     = trim($res[$i]['LANDir']);	
			array_push($op,$result);
		}
	}
?>
<script >
	var CSS_PATH = '<?php echo $_SESSION["languageSetting"]["CSS"]; ?>';
	var LANName  = '<?php echo $_SESSION["languageSetting"]["LANG"]; ?>';
	var LANDir 	 = '<?php echo $_SESSION["languageSetting"]["DIR"]; ?>';
	var Keyword_Array_Js= <?php echo json_encode($op) ; ?>;

	function languageInfo(){
		return <?php echo json_encode($_SESSION["languageSetting"]);?>;
	}
	function Auto_Translate(){		
		var Js_Array_len 	= Keyword_Array_Js.length;
		addingNewKey=[];
		$(".multi_lang").each(function() {
		  	var Item_Text 	= $(this).text();
		  	if(Item_Text==null || Item_Text == undefined || Item_Text ==''){
		  		Item_Text 	= $(this).attr('placeholder');
		  	}
		  	if(Item_Text !=null && Item_Text != undefined && Item_Text !=''){	
				counter=0;
			   	for (var j = 0; j < Js_Array_len ; ++j) {
			    	if(Item_Text.trim() == Keyword_Array_Js[j].KEYName){
						++counter;	
			    		var Translated_key = Keyword_Array_Js[j].LAKTranslated;
			    		if(Translated_key ==null || Translated_key == ''){
			    			Translated_key = Keyword_Array_Js[j].KEYName
			    		}
			    		$(this).text(Translated_key);
			    		$(this).attr('placeholder',Translated_key);
			    		break;
					}	
				}
				if(counter==0 && !addingNewKey.includes(Item_Text.trim()) && /^[A-Za-z0-9 ]*$/.test(Item_Text.trim())){
					addingNewKey.push(Item_Text.trim());
				}
		  	}	
		});
		if(addingNewKey.length>0){
			$.ajax({
				url: "models/_translation.php",
				type: "POST",
				data: {
					"type":"createALLNotExist",
					"keyword":JSON.stringify(addingNewKey)
				},
				complete: function () {
				},
				beforeSend: function () {
				},
				success: function (res) {
				},
				fail: function (err){
				},
				always:function(){
					console.log("complete");
				}
			});
		}
		
		//console.log(addingNewKey);
	}
	function Translation(msg){
		addingNewKey=[];
		var translated_msg 	= msg;
		if(msg !=null && msg != undefined && msg !=''){
			var len_array		= Keyword_Array_Js.length;
			counter=0;
		    for (var j = 0; j < len_array; ++j) {
		    	if(msg.trim() == Keyword_Array_Js[j].KEYName){
					++counter;	
		    		var Translated_key = Keyword_Array_Js[j].LAKTranslated;
		    		if(Translated_key ==null || Translated_key == ''){
		    			Translated_key = Keyword_Array_Js[j].KEYName
		    		}
		    		return Translated_key;
		    	}
			}
			if(counter==0 && !addingNewKey.includes(msg.trim()) && /^[A-Za-z0-9 ]*$/.test(msg.trim())){
				addingNewKey.push(msg.trim());
			}
		}
		if(addingNewKey.length>0){
			$.ajax({
				url: "models/_translation.php",
				type: "POST",
				data: {
					"type":"createALLNotExist",
					"keyword":JSON.stringify(addingNewKey)
				},
				complete: function () {
				},
				beforeSend: function () {
				},
				success: function (res) {
				},
				fail: function (err){
				},
				always:function(){
					console.log("complete");
				}
			});
		}	
		return translated_msg;
	}
</script>
	

