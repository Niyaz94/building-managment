<?php
    include_once "database.php";
    include_once 'ssp.php';
    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    $_SESSION['database_name']   = "kt_royalmall";
    $datatable_connection = array('user' =>"niyaz",'pass'=>"61#2d2A2j51^_4",'db'=>$_SESSION['database_name'],'host' =>"localhost");
    $database=new class_database("localhost","niyaz","61#2d2A2j51^_4",$_SESSION['database_name']);

    //$_SESSION['database_name']   = "keentzro_royal";
    //$datatable_connection = array('user' =>"keentzro_royal",'pass'=>"ku-9^A!gMHVb;7nr",'db'=>$_SESSION['database_name'],'host' =>"localhost");
    //$database=new class_database("localhost","keentzro_royal","ku-9^A!gMHVb;7nr",$_SESSION['database_name']);
?>