<?php
session_name('doShare');
session_start();
include('php/_Config.php');
$myname = $myemail = "";
$myinfo = $_SESSION["user"];
$grpid = $_SESSION["gpname"];
$grp1 = $grpid."_bills";
$grp2 = $grpid."_expense";
$grpid = $_SESSION["gpname"];
if($_SESSION["user"]!="")
{
   $sql = "SELECT * FROM _doshare_users WHERE email='$myinfo'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    $myname = $row["name"];
                    $myemail = $row["email"];
                    $myusr = $row["username"];
            }
                }
    $grp = "SELECT id FROM `$grp1`";
                 $rst = $conn->query($grp);
                         if($rst->num_rows > 0)
                         {
                             while($row = $rst->fetch_assoc()) {
                                $grpval = $row["id"];
                                
                            }
                         }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Show Balance | mydoShare</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" lazyload>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" lazyload>
    <link rel="stylesheet" href="dist/css/ionicons.min.css" lazyload>
	<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css" lazyload>
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css" lazyload>
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
					<div class="pull-left">
                      <a onclick="leavegroup()" class="btn btn-default btn-flat">Leave Group</a>
                    </div>
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
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<li><a href="share.php"><i class="fa fa-edit"></i> <span>Share Your Expense</span></a></li>
			<li><a href="delete.php"><i class="fa fa-times"></i> <span>Edit/Delete Expense</span></a></li>
            <li  class="active"><a href="showbalance.php"><i class="fa fa-pie-chart"></i> <span>Show Balance</span></a></li>
            <li><a href="sendemail.php"><i class="fa fa-envelope-o"></i> <span>Send Email</span></a></li>		
          </ul>
        </section>
      </aside>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Show Balance
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
             <li class="active">Show Balance</li>
          </ol>
        </section>
        <section class="content">
          <div class="row" >
            <div class="col-md-12" >
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Show Balances</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                            <table>                                
                                <tbody id="content">
                                 <tr>
                                        <td style="padding:10px;"><input type="text"class="form-control" value="Name" name="billname" readonly /></td>
                                        <td style="padding:10px;"><input type="text"class="form-control" value="Balance" name="amount" id="amount" readonly /></td>
                                    </tr>
                                    <?php
                                    $grp = $_SESSION["gpname"];
                                    $get = "SELECT name,uname from `_doshare_groups` WHERE groupn='$grp'";
                                     $result = $conn->query($get);
                                        if ($result->num_rows > 0) {
                                                 while($row = $result->fetch_assoc()) {
													 $myuser=$row["uname"];
													 $gpname = $_SESSION["gpname"];
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
                                                        ?>
                                                            <tr><td style="padding:10px;"><input type="text"  class="form-control" value="<?php echo $row["name"];?>" disabled /></td><td style="padding:10px;"><input type="text" class="form-control" placeholder=" Paid Amount" name="<?php echo $row["uname"];?>" value="<?php echo round($bal, 2)?>" readonly /></td></tr>
                                                        <?php
                                                    }
                                            }
                                    ?>        
                             </tbody>
                            </table>
                        </form>                  
                    </div><br><br>
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
   
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/app.min.js"></script>
	<script src="plugins/iCheck/icheck.min.js"></script>
	<script src="dist/js/async.js"></script>
		<script>
	
	function leavegroup()
	{
		$.confirm({
        text: "Are you sure want to leave this share group?",
        confirm: function(button) {
             $.ajax({
                url: 'php/leave.php',
				success:function(response) {
                         if(response=="success")
							 location.reload();
						 else
							  location.reload();
                }
			});
        },
        cancel: function(button) {
        }
    });
	}
	</script>
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