<?php 

session_start();
unset($_SESSION['is_login'] );
unset($_SESSION['user_id'] );
unset($_SESSION['profile_type'] );
unset($_SESSION['access_token'] ); 
session_destroy();
header("Location: ../ ");



?>

<script  >

sessionStorage.clear();

window.location='../';


</script>