<?php
include ('_Config.php');
require("sendgrid-php/sendgrid-php.php");
$sendgrid = new SendGrid('sendgridkey');
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
	$chksql = "SELECT DATEDIFF(CURRENT_TIMESTAMP,(SELECT MAX(c_time) from $grpval[$z]_bills)) AS DiffDate";
	echo $chksql;
	$chkemailrslt = $conn->query($chksql);
	if($chkemailrslt->num_rows > 0)
	{
		while($chkemailrow = $chkemailrslt->fetch_assoc())
		{
			$datediff = $chkemailrow['DiffDate'];
		}
	}
	else
	echo mysqli_error($conn);
	if($datediff==0)
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
			if(is_numeric($my)&&is_numeric($set))
			{
				$bal = $my - $set;
				$bal = round($bal, 2);
				$emailadd = $row["email"];
				$name = $row["name"];
				$cdate=date("Y-m-d");
				$email= new SendGrid\Email();
				$email
					->addTo("$emailadd")
					->setFrom('noreply@mydoshare.com')
					->setFromName('mydoShare Updates')
					->setReplyTo('noreply@mydoshare.com')
					->setSubject("doShare Balance EOD - $cdate")    
					->setText('Please enable HTML to view this email')
					->setHtml("<!DOCTYPE HTML><html><meta charset='utf-8'><head><title>mydoShare Alerts</title><link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'><link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'></head><body style='background: #d2d6de;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;'><br><div class='introtitle'><a href='http://www.mydoshare.com' target='_blank' style='float:left;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 35px;background: transparent;color: #444;'><img src='http://www.mydoshare.com/images/mydoshare.PNG' /></a></div><div class='toprightcnt' > <a href='mailto:info@mydoshare.com' style='float: right;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 15px;background: transparent;color: #444;'>info@mydoshare.com</a></div><br><div style='width: 80%;margin: 7%'><div class='msgbody' style='background: #fff;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;padding:20px;'>Dear $name,<br><br>The Balance in your mydoShare account as of $cdate is INR <b>$bal</b>.<br/><br/>Check A/c for current balance . Credits in A/c are subject to clearing .<br><br><div align='center'><span style='font-size:14px!important;width:40%;background-color:#2774d0;line-height:50px;display:inline-block;color:#ffffff;border-radius:1px;text-align:center'><a href='http://secure.mydoshare.com' style='text-transform:none;color:#fff;'>View your account</a></span></div><br><br>Regards,<br>mydoShare.<br></div></div><div class='downloadapp' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'><h4>Download our app today</h4><a href='https://play.google.com/store/apps/details?id=io.gonative.android.kqazb&hl=en' target='_blank'><img width='120' height='40' title='' alt='' src='http://www.mydoshare.com/images/googleplay.png' /></a></div><div class='socialmedia' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'> <h4>Connect with us on Facebook</h4><a href='https://www.facebook.com/mydoshare'><img width='30' height='30' title='' style='border:1px grey solid' alt='' src='http://www.mydoshare.com/images/fb.png' /></a></div><br><br><br><br><br><br><br><br><div class='footer1' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'>Copyright &copy; 2016,<br>mydoShare.com</div><div class='footer2' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'>This is autogenerated email,<br>don't reply to this.</div></body></html>");
					$res = $sendgrid->send($email);
					echo $res->getCode();
			echo '<br>';
			}
			
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