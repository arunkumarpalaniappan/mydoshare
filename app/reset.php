<?php
        include('php/_Config.php');
        require("php/sendgrid-php/sendgrid-php.php");
		$sendgrid = new SendGrid('SendGridKey');
		session_name('doShare');
        session_start();
		$cid = $_SESSION["pass"]["id"] ;
		$email = $_SESSION["pass"]["mail"] ;
        $email = "";
		$emailvalue ="";
        if(isset($_SESSION['state']))
        {
          $emailvalue = $_SESSION['state'];
        }        
         if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $emaila = $_POST["email"];
            if($emaila==$cid)
			{
				$_SESSION["pass"]["id"] = "success";
				header('Location: resetpassword.php');
			}
            else {
                    $emailvalue = "Invalid OTP <br>";
            }
        }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"> 
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Forget Password | mydoShare</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" lazyload>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" lazyload>
    <link rel="stylesheet" href="dist/css/ionicons.min.css" lazyload>
	<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css" lazyload>
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css" lazyload>
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
  <body class="hold-transition login-page">
  <div class="se-pre-con"></div>
    <div class="login-box">
      <div class="login-logo">
        <a href="http://www.mydoshare.com">mydoShare</a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">We have Sent an OTP to your email, It may take upto 10 minutes to receive OTP.</p>
        <div align="center"><?php echo $emailvalue; ?></div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="OTP" id="email" name="email" required>
          </div>
          <div class="row">
            <div class="col-xs-6">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
            </div>
            <div class="col-xs-6">
              <button type="button" class="btn btn-primary btn-block btn-flat" onclick="window.open('login.php','_self');">Sign In</button>
            </div>
          </div>
        </form>
        <!--<a href="#">I forgot my password</a>--><br>
      </div>
    </div>
        <div align="center">
         <small>இது ஒரு இந்தியனின் படைப்பு</small> 
		 </div>
   
    <script  src="bootstrap/js/bootstrap.min.js"></script>
    <script  src="plugins/iCheck/icheck.min.js"></script>
	<script src="dist/js/async.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%'
        });
      });
    </script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
	<?php
  include('php/analytics.php');
  ?>
  </body>
</html>
