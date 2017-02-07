<?php
include ('_Config.php');
require 'PHPMailerAutoload.php';
$grpval=array();
$gc=0;
$getgrp ="SELECT DISTINCT(groupn) FROM `_doshare_groups` WHERE groupn!='na'";
$grprst = $conn->query($getgrp);
if($grprst->num_rows > 0)
{
	while($grow = $grprst->fetch_assoc())
	{
		$grpval[$gc]=$grow["groupn"];
		$gc++;
	}
}
for($z=0;$z<count($grpval);$z++)
{
	$grp=$grpval[$z];
	$get = "SELECT name,uname,email from `_doshare_groups` WHERE groupn='$grp'";
	$result = $conn->query($get);
    if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		$myuser=$row["uname"];
		$gpname = $grp;
        $grp1 = $gpname.'_expense';
        $grp2 = $gpname.'_bills';
        $get = "SELECT SUM($myuser) AS total FROM `$grp2`";
        $rst = $conn->query($get);
        if($rst->num_rows > 0)
        {
            while($row1 = $rst->fetch_assoc()) {
                $my = $row1["total"];                                
                    }
            }
        $set = "SELECT SUM($myuser) AS set1 FROM `$grp1`";
        $rst = $conn->query($set);
        if($rst->num_rows > 0)
            {
                while($row2 = $rst->fetch_assoc()) {
                    $set = $row2["set1"];                                
                }
            }
        $bal = $my - $set;
		$email = $row["email"];
		$name = $row["name"];
		$cdate=date("Y-m-d");
		                  $mail = new PHPMailer;
                          $mail->isSMTP();
                          $mail->Debugoutput = 'html';
                          $mail->Host = "smtp.zoho.com";
                          $mail->Port = 587;
                          $mail->SMTPAuth = true;
                          $mail->Username = "username";
                          $mail->Password = "password";
                          $mail->setFrom('alerts@mydoshare.com', 'mydoShare{ }');
                          $mail->addReplyTo('info@mydoshare.com', 'mydoShare{ }');
                          $mail->addAddress($email, $name);
                          $mail->Subject = 'mydoShare{ } Balance EOD - '.date("Y-m-d");
                          $mail->msgHTML("<html><body style='background-color:d2d6de;width: 360px; margin: 7% auto;font-family: Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight: 400;font-size: 14px;line-height: 1.42857143;color: #333;'><div class='login-box'><div align='center' style='padding:5%;'><a href='http://apps.grkweb.com/doShare/' style='text-decoration:none;'><h3><b><span ><font color='black' >doShare</b>()</font></span></h3></a></div><div style='background-color:#fff;padding:3%;'><div align='left'>Dear $name,</div><br><div align='justify'>The Balance in your doShare{ } account as of $cdate is INR <b><i>$bal</i></b>.<br>You have received alert by subscribing for doShare{ } InstaAlerts .<br><br><br>Regards,<br>doShare{ }</div><br></div></div><div align='center'><br><small>&copy; doShare{ } 2015</small></div></body></html>");
                          $mail->AltBody = 'Please enable HTML to view this email';
                          if (!$mail->send()) {
                              echo "fail";
                          } else {
                              echo "success<br>";
                          }
        }
    }
	
}
?>
<html>
<title>EOD</title>
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//apps.mydoshare.com/webanalytics/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
</html>