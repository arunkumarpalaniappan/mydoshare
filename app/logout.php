<?php
	session_name('doShare');
	session_start();
	$_SESSION["user"]="";
	header('Location:../?src=logout&ref=button');
?>
<html>
<?php
  include('php/analytics.php');
  ?>
</html>