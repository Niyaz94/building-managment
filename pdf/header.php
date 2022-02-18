<?php
    require_once '../../API/vendor/autoload.php';
	require_once "../_general/backend/db_connect.php";
	require_once "../_general/backend/generalFunctions.php";
	include_once "../models/_reportClass.php";
    $_report=new _reportClass($database);
    ini_set('memory_limit','1024M');	
?>