<?php
function update($gp,$rowname,$expense,$bill,$id)
{
			include('_Config.php');
			$grp1 = $gp.'_expense';
			$grp2 = $gp.'_bills';
     		$exp = "UPDATE `$grp1` SET $rowname=$expense WHERE id=$id";
     		$bill = "UPDATE $grp2 SET $rowname=$bill WHERE id='$id'";
     		mysqli_query($conn,$exp) or die ("Error".mysqli_error($conn));
     		mysqli_query($conn,$bill) or die ("Error".mysqli_error($conn));
     		header('location:index.php');
     		//echo $conn;
     	}
?>