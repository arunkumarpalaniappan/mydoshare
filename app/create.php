<!DOCTYPE html>
<html>
  <head>
<?php
session_name('doShare');
session_start();
include('php/_Config.php');
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
								//echo $myuser;
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
    if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $gpname = $_POST["gpname"];
$gpname = preg_replace("/[^A-Za-z0-9]/", "", $gpname);
$gpname = preg_replace('/\s+/', '', $gpname);
			$gpname =  strtolower ($gpname);
            $_SESSION["gpname"] = $gpname;
            $grp1 = $gpname."_bills";
            $grp2 = $gpname."_expense";
            $check = "SELECT DISTINCT(uname) FROM _doshare_groups WHERE groupn='$gpname'";
            $result = $conn->query($check);
            if($result->num_rows >0){
                $grpvalue = "ShareGroup name not available.<br>Try a different name.";
            }
            else
            {
                $mysql1 = "CREATE TABLE `$grp1` (id int not null AUTO_INCREMENT primary key,billname varchar(50),total float,c_time TIMESTAMP default current_timestamp,createdby varchar(50),category varchar(50),$myuser )";
                $mysql2 = "CREATE TABLE `$grp2` (id int not null AUTO_INCREMENT primary key,billname varchar(50),total float,c_time TIMESTAMP default current_timestamp,createdby varchar(50),category varchar(50),$myuser )";
                $mysql3 = "UPDATE `_doshare_groups` SET groupn='$gpname' WHERE email='$myemail'";
               if(($conn->query($mysql1) === TRUE)&&($conn->query($mysql2)===TRUE))
                {
                    if($conn->query($mysql3))
                    {
                        header('Location: index.php');
                    }
                    else
                    {
                        $grpvalue = "ShareGroup name not available.<br>Try a different name.";
                    }
               }
                else
                {
                    $grpvalue ="ShareGroup name not available.<br>Try a different name.";
                }
            }
        }
      }
    }
  }
?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create Sharegroup | mydoShare</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" >
    <link rel="stylesheet" href="dist/css/ionicons.min.css" >
	 <script src="http://www.mydoshare.com/app/plugins/jQuery/jQuery-2.1.4.min.js"></script>
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
            Create a Share Group
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="index.php">Dashboard</a></li>
            <li class="active">Create a Share Group</li>
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
                                        <td style="padding:10px;" colspan="2"><div style="font-family: 'Roboto Condensed', sans-serif;" align="center"><?php echo $grpvalue;?></div><input type="text"class="btn btn-default" placeholder="Enter Group Name" name="gpname" style="text-transform:lowercase" pattern="[a-z0-9]+" title="Small Letters and Number only allowed" id="gpname" size="35" required/></td></tr>
                                    <tr>
                                            <td style="padding:10px;" colspan="1">
                                                     <div class="btn-group margin-bottom-2x" role="group" align="center">
                                            <button type="submit" id="b2" class="btn btn-default" ><i class="fa fa-sign-in"></i>&nbsp;do Create</button>
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
