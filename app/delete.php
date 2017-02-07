<!DOCTYPE html>
<html>
  <head>
<?php
session_name('doShare');
session_start();
include('php/_Config.php');
$myname = $myemail = "";
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
   $sql = "SELECT name,email FROM _doshare_users WHERE email='$myinfo'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    $myname = $row["name"];
                    $myemail = $row["email"];
            }
                }
?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Delete IOU's,Bills,Expenses | mydoShare</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" >
    <link rel="stylesheet" href="dist/css/ionicons.min.css" >
    
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css" >
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css" >
	<link href='https://fonts.googleapis.com/css?family=Play|Shadows+Into+Light|Pacifico|Orbitron|Dancing+Script|Kaushan+Script' rel='stylesheet' type='text/css' >
	 <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
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
	<style>
	#delerr
	{
		display:none;
		color: red;
	}
	</style>
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
          <div class="user-panel" style="padding: 20px;">
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
			<li  class="active"><a href="delete.php"><i class="fa fa-times"></i> <span>Edit/Delete Expense</span></a></li>
            <li><a href="showbalance.php"><i class="fa fa-pie-chart"></i> <span>Show Balance</span></a></li>
            <li><a href="sendemail.php"><i class="fa fa-envelope-o"></i> <span>Send Email</span></a></li>				
          </ul>
        </section>
      </aside>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Expenses
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="index.php">Dashboard</a></li>
             <li class="active">Expenses</li>
          </ol>
        </section>
        <section class="content">
          <div class="row" style="width:auto">
            <div class="col-md-12" style="width:auto">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">All Expenses</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                     <div class="box-body table-responsive no-padding">
					 <div align="center" id="delerr">Sorry, Something went wrong, Please try again.</div>
                      <table class="table table-hover">                                 
                                <tbody id="content">
                                    <tr>
                                        <td style="padding:10px;"> Category </td>
                                        <td style="padding:10px;" > Bill Name </td>
                                        <td style="padding:10px;"> Bill Total </td>
                                        <td style="padding:10px;"> Updated At </td>                                        
                                       <?php
                                        $grpn = $_SESSION["gpname"];
                                        $i=0;
                                        $names [] = array();
                                            $users = "SELECT name from _doshare_groups WHERE groupn='$grpn'";
                                            $getusers = $conn->query($users);
                                            $i = 0;
                                            if($getusers->num_rows > 0)
                                            {
                                                while($data = $getusers->fetch_assoc())
                                                {
                                                    ?>
                                                        <td style="padding:10px;" ><?php echo $data['name'];?> </td>
                                                    <?php
                                                }
                                            }
                                       ?>
									   <td style="padding:10px;"> EDIT / DELETE </td>  
                                    </tr>
                                    <?php
                                    $users = "SELECT uname from _doshare_groups WHERE groupn='$grpn'";
                                            $getusers = $conn->query($users);
                                            $i = 0;
                                            if($getusers->num_rows > 0)
                                            {
                                                while($data = $getusers->fetch_assoc())
                                                {
                                                    $names[$i]=$data['uname'];
                                                    $i++;
                                                }
                                            }
                                            $grpp = $_SESSION["gpname"];
                                            $gpp1 = $grpp.'_bills';
                                            $recet = "SELECT MAX(id) as rval from $gpp1";
                                            $getrecent = $conn->query($recet);
                                            if($getrecent->num_rows > 0)
                                            {
                                                while ($recentdata = $getrecent->fetch_assoc()) {
                                                    $rvalue = $recentdata['rval'];
                                                }
                                            }
                                    for($z=$rvalue;$z>0;$z--)
                                    {
                                    $i=0;
                                    $grp = $_SESSION["gpname"];
                                    $gp1 = $grp.'_bills';
                                    $get = "SELECT * from $gp1 where id=$z AND createdby='$myname'";
                                     $result = $conn->query($get);
                                        if ($result->num_rows > 0) {
                                                 while($row = $result->fetch_assoc()) {
                                                        ?><tr>
                                                            <td style="padding:10px;" rowspan="2" ><?php echo $row["category"];?></td> 
                                                            <td style="padding:10px;" rowspan="2" ><?php echo $row["billname"];?></td>
                                                                <td style="padding:10px;" rowspan="2" ><?php echo $row["total"];?></td> 
                                                                <td style="padding:10px;" rowspan="2" ><?php echo $row["c_time"];?></td>                                                        
                                                        <?php               
                                    $gpn1 = $grpn.'_bills';
                                    $gpn2 = $grpn.'_expense';
                                    $exp = "SELECT * from $gpn1 where id=$z AND createdby='$myname'";
                                    $res = $conn->query($exp);
                                        if ($res->num_rows > 0) {
                                                 while($getr = $res->fetch_assoc()) {
                                                        $max = sizeof($names);
                                                            for($i = 0; $i < $max;$i++)
                                                                {
                                                                    $getrv = $names[$i];
                                                                    ?>
                                                           <td style="padding:10px;color:green;" ><?php if($getr[$getrv]==0) {echo '-';}else{echo "&#8377;".round($getr[$getrv], 2);}?></td>
                                                           <?php
                                                                }
                                                                ?>
																<td style="padding:10px;" rowspan="1" ><input style="background : #3c8dbc !important;border:none;color:white;" type="button" name="<?php echo $z; ?>" onclick="editexpense(<?php echo $z; ?>)" value="&nbsp;&nbsp;Edit&nbsp;&nbsp;"></button></td>  
                                                                    </tr>
                                                                <?php                                                        
                                                    }
                                              }
                                          ?>
                                    <?php
                                    $exp = "SELECT * from $gpn2 where id=$z AND createdby='$myname'";
                                    $res = $conn->query($exp);
                                        if ($res->num_rows > 0) {
                                                 while($getr = $res->fetch_assoc()) {
                                                        $max = sizeof($names);
                                                        ?>
                                                        <tr>
                                                        <?php
                                                            for($i = 0; $i < $max;$i++)
                                                                {
                                                                    $getrv = $names[$i];

                                                                    ?>
                                                           <td style="padding:10px;color:red;" ><?php if($getr[$getrv]==0) {echo '-';}else{echo "&#8377;".round($getr[$getrv], 2);}?></td>
                                                        <?php
                                                                }
                                                                ?>
																<td style="padding:10px;" rowspan="1" ><input style="background : #3c8dbc !important;border:none;color:white;" type="button" name="<?php echo $z; ?>" onclick="deleteexpense(<?php echo $z; ?>,'<?php echo $row["billname"];?>','<?php echo $row["total"];?>','<?php echo $myname;?>')" value="Delete"></button></td>
                                                            </tr>
                                                            <?php                                                        
                                                            }
                                                        }
                                                    }
                                                }
                                             }
                                    ?>                                                 
                             </tbody>
                            </table><br>                      
                    </div></div><br><br>
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
	<script  src="plugins/iCheck/icheck.min.js"></script>

    
 
    
	
	<script  src="dist/js/jquery.confirm.js"></script>
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
	<script>
	function editexpense(id)
	{
		$.confirm({
        text: "This action can edit the expense values which affects all peoples involved in this expense from your sharegroup, Are you sure want to edit this expense?",
        confirm: function(button) {
			$.ajax({
			type: 'POST',
			url: 'setedit.php',
			data: {'id': id},
			success:function(response) {
							 window.open('edit.php','_self');
                }
			});
			
        },
        cancel: function(button) {
        }
    });
	}
	function deleteexpense(id,billname,billtotal,juser)
	{
		$.confirm({
        text: "This action cannot be undone and affects all peoples involved in this expense from your sharegroup, Are you sure want to delete this expense?",
        confirm: function(button) {
             $.ajax({
                url: 'php/deletebill.php',
                data: 'id='+id+'&bill='+billname+'&total='+billtotal+'&user='+juser,
                type: 'POST',
				success:function(response) {
                         if(response=="success")
							 location.reload();
						 else
							 document.getElementById("delerr").style.display="block";
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