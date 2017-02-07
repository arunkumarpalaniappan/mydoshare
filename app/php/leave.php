<?php
session_name('doShare');
session_start();
require_once('_Config.php');
$email = $_SESSION["user"];
$sql2 = "UPDATE _doshare_groups SET groupn='na' WHERE email='$email';";
$rst = $conn->query($sql2);
if($rst==TRUE)
	echo "success";
 else
	 echo "fail";
?>