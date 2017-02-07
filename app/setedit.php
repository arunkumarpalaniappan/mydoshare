<?php
session_name('doShare');
session_start();
//$cookie_name = "billid";
$cookie_value = $_POST['id'];
$_SESSION["bill"]=$cookie_value;
echo $_SESSION["bill"];
//setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); 
?>