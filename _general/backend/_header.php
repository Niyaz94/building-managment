<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    if(!isset($_SESSION['is_login']) ){
        header("location: login.php");
        echo '<script>window.location="login.php";</script>';
    }
    date_default_timezone_set($_SESSION['SYSTimezone']);

    require_once "../_general/backend/validationData.php";
    require_once "../_general/backend/db_connect.php";
    require_once "../_general/backend/generalFunctions.php";
?>