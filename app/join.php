<!DOCTYPE html>
<html>
  <head>
<?php
session_name('doShare');
session_start();
include('php/_Config.php');
require("php/sendgrid-php/sendgrid-php.php");
$sendgrid = new SendGrid('SendGridKey');
$myname = $myemail = $grpvalue = $gpname = $myuser = "";
$myinfo = $_SESSION["user"];   
if($_SESSION["user"]!="")
{  
   $sql = "SELECT name,username,email FROM _doshare_users WHERE email='$myinfo'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    $myname = $row["name"];
                    $myemail = $row["email"];
                                $myuser = $row["username"];
            }
                }
      $check ="SELECT * FROM _doshare_groups WHERE email='$myinfo'";
     $result = $conn->query($check);
     if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        if($row["groupn"]!="na")
        {
         //header("location: index.php");
        }
		else if($row["groupn"]=="needtobeapproved")
		{
			//header('Location:index-notaccepted.php');
		}
            else
            {
    if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $gpname = $_POST["gpname"];
			$gpname =  strtolower ($gpname);
            $_SESSION["gpname"] = $gpname;
			$skey = mt_rand();
            $check = "SELECT DISTINCT(groupn) FROM _doshare_groups";
            $result = $conn->query($check);
            if($result->num_rows > 0){
                 $mysql1 = "INSERT INTO `_doshare_nogrp` (myname,username,email,groupn,tkey) VALUES ('$myname','$myuser','$myemail','$gpname','$skey');";
               if($conn->query($mysql1) === TRUE)
                {
					 $mysql3 = "UPDATE `_doshare_groups` SET groupn='needtobeapproved' WHERE email='$myemail';";
					 $conn->query($mysql3);
			   $sql = "SELECT name,email FROM _doshare_groups WHERE groupn='$gpname'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
						$useremail = $row['email'];
						$name = $row['name'];
						$grpemail = new SendGrid\Email();
						  $grpemail
							    ->addTo("$useremail")
								->setFrom('noreply@mydoshare.com')
								->setFromName('mydoShare Alerts')
								->setReplyTo("noreply@mydoshare.com")
								->setSubject("A New user has requested to join your sharegroup $gpname")    
								->setText('Please enable HTML to view this email')
								->setHtml("<!DOCTYPE HTML><html><meta charset='utf-8'><head><title>mydoShare Alerts</title><link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'><link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'></head><body style='background: #d2d6de;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;'><br><div class='introtitle'><a href='http://www.mydoshare.com' target='_blank' style='float:left;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 35px;background: transparent;color: #444;'><img src='http://www.mydoshare.com/images/mydoshare.PNG' /></a></div><div class='toprightcnt' > <a href='mailto:info@mydoshare.com' style='float: right;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 15px;background: transparent;color: #444;'>info@mydoshare.com</a></div><br><div style='width: 80%;margin: 7%'><div class='msgbody' style='background: #fff;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;padding:20px;'>Dear $name,<br><br>$myname($myemail) has requested acccess to be a part of your sharegroup $gpname. <br> Accept $myname request by clicking this link <a href='http://secure.mydoshare.com/approve/$skey'>http://secure.mydoshare.com/approve/$skey</a><br><br><div align='center'></div><br><br>Regards,<br>mydoShare.<br></div></div><div class='downloadapp' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'><h4>Download our app today</h4><a href='https://play.google.com/store/apps/details?id=io.gonative.android.kqazb&hl=en' target='_blank'><img width='120' height='40' title='' alt='' src='http://www.mydoshare.com/images/googleplay.png' /></a></div><div class='socialmedia' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'> <h4>Connect with us on Facebook</h4><a href='https://www.facebook.com/mydoshare'><img width='30' height='30' title='' style='border:1px grey solid' alt='' src='http://www.mydoshare.com/images/fb.png' /></a></div><br><br><br><br><br><br><br><br><div class='footer1' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'>Copyright &copy; 2016,<br>mydoShare.com</div><div class='footer2' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'>This is autogenerated email,<br>don't reply to this.</div></body></html>");
								$res = $sendgrid->send($grpemail);
								//if($res->getCode()==200)
									//$mystate = "Your email has been sent successfully.";		
					}
                }
				 $sendemail = new SendGrid\Email();
						  $sendemail
							    ->addTo("$myemail")
								->setFrom('noreply@mydoshare.com')
								->setFromName('mydoShare Support')
								->setSubject("Your request to join share group $gpname has been submitted")    
								->setText('Please enable HTML to view this email')
								->setHtml("<!DOCTYPE HTML><html><meta charset='utf-8'><head><title>mydoShare Alerts</title><link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'><link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'></head><body style='background: #d2d6de;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;'><br><div class='introtitle'><a href='http://www.mydoshare.com' target='_blank' style='float:left;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 35px;background: transparent;color: #444;'><img src='http://www.mydoshare.com/images/mydoshare.PNG' /></a></div><div class='toprightcnt' > <a href='mailto:info@mydoshare.com' style='float: right;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 15px;background: transparent;color: #444;'>info@mydoshare.com</a></div><br><div style='width: 80%;margin: 7%'><div class='msgbody' style='background: #fff;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;padding:20px;'>Hi $myname,<br><br>Your request to join the sharegroup $gpname has been sent to the group memebers, ask you friends to approve your request. Once they approved it you will be the part of the sharegroup.<br><br><div align='center'></div><br><br>Regards,<br>mydoShare.<br></div></div><div class='downloadapp' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'><h4>Download our app today</h4><a href='https://play.google.com/store/apps/details?id=io.gonative.android.kqazb&hl=en' target='_blank'><img width='120' height='40' title='' alt='' src='http://www.mydoshare.com/images/googleplay.png' /></a></div><div class='socialmedia' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'> <h4>Connect with us on Facebook</h4><a href='https://www.facebook.com/mydoshare'><img width='30' height='30' title='' style='border:1px grey solid' alt='' src='http://www.mydoshare.com/images/fb.png' /></a></div><br><br><br><br><br><br><br><br><div class='footer1' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'>Copyright &copy; 2016,<br>mydoShare.com</div><div class='footer2' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'>This is autogenerated email,<br>don't reply to this.</div></body></html>")
								;
								$res = $sendgrid->send($sendemail);
                    header('Location: index.php');
               }
                else
                {
                   header('Location: index.php');
				   //echo mysql_error($conn);
                }
            }
            else
            {
                $grpvalue = "ShareGroup Doesn't Exists.<br>Try again.";
            }
        }
      }
    }
  }
    ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Join Sharegroup | mydoShare</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" >
    <link rel="stylesheet" href="dist/css/ionicons.min.css" >
    	 <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css" >
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css" >
	<link href='https://fonts.googleapis.com/css?family=Play|Shadows+Into+Light|Pacifico|Orbitron|Dancing+Script|Kaushan+Script' rel='stylesheet' type='text/css' >
<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
 <script>
	$(window).load(function() {
		$(".se-pre-con").fadeOut("slow");;
	});
</script>

    <!--[if lt IE 9]>
        <script  src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script  src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
  <div class="se-pre-con"></div>
    <div class="wrapper">
      <header class="main-header">
        <a href="index.php" class="logo">
          <span class="logo-mini"><b>do</b></span>
          <span class="logo-lg">mydoShare</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class=""><?php echo $myname; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-footer">
                    <p>
                     <div align="center"><?php echo $myname; ?><br>
                      <small><?php echo $myemail; ?></small>
                    </div>
                    </p>
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <aside class="main-sidebar">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image">
              <img src="dist/img/profile.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $myname; ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li>
              <a href="index.php">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span> 
              </a>
            </li>
          </ul>
        </section>
      </aside>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Join a Sharegroup
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="index.php">Dashboard</a></li>
            <li class="active">Join a Sharegroup</li>
          </ol>
        </section>
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div align="center">
                          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <table>
                                <tbody id="content">
                                    <tr>
                                        <td style="padding:10px;" colspan="2"><div style="font-family: 'Roboto Condensed', sans-serif;" align="center"><?php echo $grpvalue;?></div><input type="text"class="btn btn-default" placeholder="Enter Group Name" style="text-transform:lowercase" pattern="[a-z0-9]+" title="Small Letters and Number only allowed" name="gpname" id="gpname" style="text-transform:lowercase" size="35" required /></td></tr>
                                    <tr>
                                            <td style="padding:10px;" colspan="1">
                                                     <div class="btn-group margin-bottom-2x" role="group" align="center">
                                            <button type="submit" id="b2" class="btn btn-default" ><i class="fa fa-sign-in"></i>&nbsp;do Join</button>
                                        </div> 
                                            </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>                
                      </div>
                      <br>                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <small>Beta</small>
        </div>
         <small>இது ஒரு இந்தியனின் படைப்பு</small>
      </footer>     
      <div class="control-sidebar-bg"></div>
    </div>
    <script  src="bootstrap/js/bootstrap.min.js"></script>

    <script  src="dist/js/app.min.js"></script>

    
 
    
	
	<?php
  include('php/analytics.php');
  ?>
  </body>
</html>
<?php
}
else
{
  header('Location:login.php');
}
?>