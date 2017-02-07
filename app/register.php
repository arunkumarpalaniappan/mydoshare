<?php
        include('php/_Config.php');
        require("php/sendgrid-php/sendgrid-php.php");
		$sendgrid = new SendGrid('SendGridKey');
		session_name('doShare');
		
        session_start();
        $name = $username = $email = $password = $cpassword = $passvalue = $emailvalue = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $name = $_POST["name"];
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $cpassword = $_POST["cpassword"];
            $key = mt_rand();
            if($password != $cpassword)
            {
               $passvalue = "Password and Confirm Password doesnot match.<br>";
           }
            else
            {
                $sql = "SELECT email FROM _doshare_users WHERE email='$email'";
                $sql1 = "SELECT username FROM _doshare_users WHERE username='$username'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                         $emailvalue = "User is already registered.<br>";
                }
                else {
                    $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {
                         $emailvalue = "User is already registered.<br>";
                }
                else
                {
				$password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO _doshare_users (name, username, email, password, skey) VALUES ('$name', '$username', '$email', '$password','$key')";
                $create = "INSERT INTO _doshare_groups (name, uname, email,groupn) VALUES ('$name', '$username', '$email','na')";
                if ($conn->query($create) === TRUE) {
                         if ($conn->query($sql) === TRUE) {     
						  $sendemail = new SendGrid\Email();
						  $sendemail
							    ->addTo("$email")
								->setFrom('noreply@mydoshare.com')
								->setFromName('mydoShare Support')
								->setSubject('Action Required: Please confirm your email')    
								->setText('Please enable HTML to view this email')
								->setHtml("<!DOCTYPE HTML><html><meta charset='utf-8'><head><title>mydoShare Alerts</title><link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'><link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'></head><body style='background: #d2d6de;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;'><br><div class='introtitle'><a href='http://www.mydoshare.com' target='_blank' style='float:left;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 35px;background: transparent;color: #444;'><img src='http://www.mydoshare.com/images/mydoshare.PNG' /></a></div><div class='toprightcnt' > <a href='mailto:info@mydoshare.com' style='float: right;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 15px;background: transparent;color: #444;'>info@mydoshare.com</a></div><br><div style='width: 80%;margin: 7%'><div class='msgbody' style='background: #fff;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;padding:20px;'>Dear $name,<br><br>Welcome to mydoShare, We are really happy to have you on board. Recently you have signed up on mydoShare. <br> Please confirm your email address. Click on the confirm button below to do the same.<br><br><div align='center'><span style='font-size:14px!important;width:40%;background-color:#2774d0;line-height:50px;display:inline-block;color:#ffffff;border-radius:1px;text-align:center'><a href='http://secure.mydoshare.com/verification/$key' style='text-decoration:none;color:#fff;font-size:15px;'>Confirm your account </a> </span><br> <br><font size='2px'>If it doesn't work,copy paste the link in your browser to confirm your account http://secure.mydoshare.com/verification/$key</font></div><br><br>Regards,<br>mydoShare.<br></div></div><div class='downloadapp' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'><h4>Download our app today</h4><a href='https://play.google.com/store/apps/details?id=io.gonative.android.kqazb&hl=en' target='_blank'><img width='120' height='40' title='' alt='' src='http://www.mydoshare.com/images/googleplay.png' /></a></div><div class='socialmedia' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'> <h4>Connect with us on Facebook</h4><a href='https://www.facebook.com/mydoshare'><img width='30' height='30' title='' style='border:1px grey solid' alt='' src='http://www.mydoshare.com/images/fb.png' /></a></div><br><br><br><br><br><br><br><br><div class='footer1' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'>Copyright &copy; 2016,<br>mydoShare.com</div><div class='footer2' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'>This is autogenerated email,<br>don't reply to this.</div></body></html>")
								;
								$res = $sendgrid->send($sendemail);
								 $SESSION['state']="Verify your Email address to continue.";
							    header('Location: login.php?ref=hmp&signup=success');
                         } else {
                                $passvalue = "Error: " . $sql . "<br>" . $conn->error;
                        }
                } else {
                    $passvalue = "Error: " . $sql . "<br>" . $conn->error;
                }
                }
            }
            }
        }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration | mydoShare</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" lazyload>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" lazyload>
		 <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
   <link rel="stylesheet" href="dist/css/ionicons.min.css" lazyload>
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css" lazyload>
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css" lazyload>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="og:title" content="mydoShare.com - Split your group expenses,bills and IOUs in a single click"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://www.mydoshare.com/"/>
<meta property="og:image" content="http://www.mydoshare.com/images/mydoshare.PNG"/>
<meta property="og:site_name" content="mydoShare.com"/>
<meta property="og:description" content="Share Bills and Expenses"/>
<meta name="author" content="">
<meta name="theme-color" content="#367fa9">
<meta http-equiv="Content-Language" content="en" />
<meta name="description" content="Sharing Bills and Expenses made simple. Split household bills, daily expenses and personal IOUs with friends and flatmates in a click. Ideal for rent, groceries, utilities, travel, bbqs, dinner, drinks, sport clubs and more"/>
<meta name="keywords" content="my do share, mydo share, my doshare, mydoshare.com, wwww.mydoshare.com, bill, expense, share, sharing, split, splitting, utility, rent, student, houseshare, flatshare, flatmate, roommate, whosebill, ious, expenses, bills, shared, money, joint, group, pool, social, settle, offset, loan, lend, borrow, debt, mobile, iphone, ioweyou" />
	<link href='https://fonts.googleapis.com/css?family=Play|Shadows+Into+Light|Pacifico|Orbitron|Dancing+Script|Kaushan+Script' rel='stylesheet' type='text/css' lazyload>

<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
 <script>
	$(window).load(function() {
		$(".se-pre-con").fadeOut("slow");;
	});
</script>

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition register-page">
  <div class="se-pre-con"></div>
    <div class="register-box">
      <div class="register-logo">
        <a href="http://www.mydoshare.com">mydoShare</a>
      </div>
      <div class="register-box-body">
        <p class="login-box-msg">Register a new account</p>
        <div align="center"><?php echo $emailvalue;?></div>
        <form action=""<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="User name" name="username" id="username" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Full name" name="name" id="name" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email" id="email" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div align="center"><?php echo $passvalue;?></div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Retype password" name="cpassword" id="cpassword" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-6">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign Up</button>
            </div>
            <div class="col-xs-6">
              <button type="button" class="btn btn-primary btn-block btn-flat" onclick="window.open('login.php','_self');">Sign In</button>
            </div>
          </div>
        </form>  
      </div>
    </div>
	<div align="center">
         <small>இது ஒரு இந்தியனின் படைப்பு</small> 
		 </div>
   
    <script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="dist/js/async.js"></script>
	<script  src="plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' 
        });
      });
    </script>
	<?php
  include('php/analytics.php');
  ?>
  </body>
</html>