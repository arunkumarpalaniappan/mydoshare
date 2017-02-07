<!DOCTYPE html>
<html>
  <head>
<?php
session_name('doShare');
session_start();
include('php/_Config.php');
$myname = $myemail = "";
$tot = $grp = $my = $bal = 0;
if($_SESSION["user"]!="")
{
    $myinfo = $_SESSION["user"];  
     $sql = "SELECT name,username,email FROM _doshare_users WHERE email='$myinfo'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                                $myuser = $row["username"];
                    }
                }
   $check ="SELECT * FROM _doshare_groups WHERE email='$myinfo'";
     $result = $conn->query($check);
     if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        if($row["groupn"]!="na")
        {
          header("location: index.php");
        }
            else
            {
                $sql = "SELECT name,email FROM _doshare_users WHERE email='$myinfo'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                                $myname = $row["name"];
                                $myemail = $row["email"];
                    }
                }
            }
        }
    }
?>    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>mydoShare - Split your group expenses,bills and IOUs in a single click</title>
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
            <li class="active treeview">
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
            Dashboard
            <small>Version 1.0</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
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
                        <p style="font-family: 'Roboto Condensed', sans-serif;font-size:15px;">You are not in any share groups.</p><br>
                          <div class="btn-group margin-bottom-2x" role="group">
                              <button type="button" id="b1" class="btn btn-default" onclick="window.open('create.php?ref=nogrp','_self');"><i class="fa fa-sign-out"></i>Create Sharegroup</button>
                          </div>
                          <div class="btn-group margin-bottom-2x" role="group">
                              <button type="button" id="b2" class="btn btn-default" onclick="window.open('join.php?ref=nogrp','_self');"><i class="fa fa-sign-out"></i>Join Sharegroup</button>
                          </div>                  
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