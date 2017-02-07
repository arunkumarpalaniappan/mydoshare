<?php
        include('php/_Config.php');
		session_name('doShare');
		ini_set('session.gc_maxlifetime', 2880*60);
        session_start();
                $email = $password = $emailvalue= "";
        if(isset($_SESSION['state']))
        {
          $emailvalue = $_SESSION['state'];
		  $_SESSION['state']="";
        }
        if(isset($_GET['signup'])) {
			$emailvalue = "Success! Verify your Email address to continue.";
		}
         if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $sql = "SELECT * FROM _doshare_users WHERE email='$email'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                         while($row1 = $result->fetch_assoc()) {
                                if($row1["emailverified"]==false)
                                {
                                  $_SESSION["state"]="Please Verify your email Address to continue.";
                                  $_SESSION["user"] = "";
                                  header('Location:login.php');
                                }
                                else
                                {
								  $hashpass = $row1['password']; 
								  if(password_verify($password, $hashpass))
								  {
										$_SESSION["user"] = $email;
										$grp = "SELECT * FROM _doshare_groups WHERE email='$email'";
										$rst = $conn->query($grp);
										if($rst->num_rows > 0)
											{
												while($row = $rst->fetch_assoc()) {
                                                        $_SESSION["gpname"] = $row["groupn"];
                                                         header('Location:index.php');
											}                            
										}
								  }
                                  else
								  {
									  $emailvalue ="Invalid Email Password<br>";
									  $emailAddress = $email;
								  }
                                }
                                
                            } 
            }
            else {
                    $emailvalue ="Invalid Email Password<br>";
                    $emailAddress = $email;
            }
        }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"> 
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>mydoShare | Log in</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" >
    <link rel="stylesheet" href="dist/css/ionicons.min.css" >
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css" >
		 <script  src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css" >
	<link href='https://fonts.googleapis.com/css?family=Play|Shadows+Into+Light|Pacifico|Orbitron|Dancing+Script|Kaushan+Script' rel='stylesheet' type='text/css' >
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
  <body class="hold-transition login-page">
  <div class="se-pre-con"></div>
    <div class="login-box">
      <div class="login-logo">
        <a href="http://www.mydoshare.com">mydoShare</a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <div align="center"><?php echo $emailvalue;?></div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email Address" id="email" name="email" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-6">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
            <div class="col-xs-6">
              <button type="button" class="btn btn-primary btn-block btn-flat" onclick="window.open('register.php','_self');">Sign Up</button>
            </div><br><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="forgetpassword.php" >Forget Password? </a>
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
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%'
        });
      });
    </script>
	<script type="text/javascript">
    window.cookieconsent_options = {"message":"Our web application uses cookies to ensure you get the best experience on our website.","dismiss":"Got it!","learnMore":"","link":"","theme":"dark-bottom"};
	</script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
	<?php
  include('php/analytics.php');
  ?>
  </body>
</html>