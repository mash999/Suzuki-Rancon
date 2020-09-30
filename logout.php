<?php 
session_start();
session_destroy();
unset($_SESSION['rancon_user_id']);
unset($_SESSION['rancon_access_level']);
$_SESSION[] = array();
echo "<script>location.href='index.php';</script>";
?>