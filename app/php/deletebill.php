<?php
session_name('doShare');
session_start();
include('_Config.php');
include('alertsendgrid.php');
$grp = $_SESSION["gpname"];
$id = $_POST['id'];
$billn = $_POST['bill'];
$billt = $_POST['total'];
$billu = $_POST['user'];
sendalert("deleted",$billn,$billt,$grp,$billu);
$grp1 = $grp.'_bills';
$grp2 = $grp.'_expense';
$sql1 = "DELETE from $grp1 WHERE id=$id";
$sql2 = "DELETE from $grp2 WHERE id=$id";
 $result1 = mysqli_query($conn,$sql1);
 $result2 = mysqli_query($conn,$sql2);
 if(($result1)AND($result2))
 {
	 echo "success";
 }
 else
 {
 	 echo "fail";
 }
 ?>