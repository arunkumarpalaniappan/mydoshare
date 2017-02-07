<?php
session_name('doShare');
session_start();
include('php/_Config.php');
include('php/_Update.php');
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
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $total = $_POST["amount"];
        $bill = $_POST["billname"];
        $cate = $_POST["categories"];
        $ins = "INSERT INTO $grp1 (billname,total,createdby,category) VALUES ('$bill','$total','$myname','$cate')";
        $inst = "INSERT INTO $grp2 (billname,total,createdby,category) VALUES ('$bill','$total','$myname','$cate')";
        if (($conn->query($ins) === TRUE)&&($conn->query($inst) === TRUE))
        {
        $get = "SELECT name,uname from `_doshare_groups` WHERE groupn='$grpid'";
        $result = $conn->query($get);
        if ($result->num_rows > 0) {
			$grp = "SELECT MAX(id) as cval FROM `$grp1`";
                 $rst = $conn->query($grp);
                         if($rst->num_rows > 0)
                         {
                             while($row = $rst->fetch_assoc()) {
                                $grpval = $row["cval"];
                                
                            }
                         }
        while($row = $result->fetch_assoc()) {
                $name= $row["uname"];
                $ename = $name.'e';
                if(isset($_POST["$ename"]))
				{
					$expense = $_POST["$ename"];
				}
				else{
					$expense=0;
				}
                if(isset($_POST["$name"]))
				{
					$bills = $_POST["$name"];
				}
				else{
					$bills=0;
				}
                update($grpid,$name,$expense,$bills,$grpval);
        }
        }
        else
        {
            $myname = "err";
        }
        }
        else
        {
            $myname1 = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>
<!DOCTYPE html>
<html>
  <head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Share Expense | mydoShare</title>
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
	var myuserarray = new Array();
	var userenabledarray = new Array();
	var usercnt=0;
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
                    
                     <div align="center"><?php echo $myname; ?><br>
                      <small><?php echo $myemail; ?></small>
                    </div>
                    
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
			<li class="active"><a href="share.php"><i class="fa fa-edit"></i> <span>Share Your Expense</span></a></li>
			<li><a href="delete.php"><i class="fa fa-times"></i> <span>Edit/Delete Expense</span></a></li>
            <li><a href="showbalance.php"><i class="fa fa-pie-chart"></i> <span>Show Balance</span></a></li>
            <li><a href="sendemail.php"><i class="fa fa-envelope-o"></i> <span>Send Email</span></a></li>		
          </ul>
        </section>
      </aside>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Share Expense
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
             <li class="active">Share Expense</li>
          </ol>
        </section>
        <section class="content">
          <div class="row" >
            <div class="col-md-12" >
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Share Expense</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                    <form method="post" id="share" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <table>                                
                                <tbody id="content">
                                    <tr>
                                        <td style="padding:25px;"><div class="form-group"><select name="categories" class="form-control select2"  ><option selected="selected">Food</option><option>Travel</option><option>Snacks</option><option>Bills</option><option>TV and Internet</option><option>Utility Bills</option><option>Movie</option><option>Others</option></select></div></td>
                                        <td style="padding:10px;"><input type="text" class="form-control" placeholder="Enter Bill Name" name="billname" required /></td>
                                        <td style="padding:10px;"><input type="number" class="form-control" placeholder="Enter Total Amount" onchange="calc()" name="amount" id="amount" required /></td>
                                    </tr>
									<tr>
                                        <td style="padding:10px;"><input type="text" class="form-control" value="Name" disabled /></td>
                                        <td style="padding:10px;"><input type="text" class="form-control" value="Paid Amount" disabled /></td>
                                        <td style="padding:10px;"><input type="text" class="form-control" value="Expense Amount" disabled /></td>
                                    </tr>
                                    <?php
                                    $grp = $_SESSION["gpname"];
                                    $get = "SELECT name,uname from `_doshare_groups` WHERE groupn='$grp'";
                                     $result = $conn->query($get);
									 $cnt=0;
                                        if ($result->num_rows > 0) {
                                                 while($row = $result->fetch_assoc()) {
                                                        ?>
                                                            <tr><td style="padding-top:10px;padding-right:10px;padding-bottom:10px;"><div style="display:inline-block"><input type="checkbox" name="<?php echo $row["uname"].'c';?>" onchange="check('<?php echo $row["uname"];?>',<?php echo $cnt;?>)" id="<?php echo $row["uname"].'c';?>" checked /></div><div  style="display:inline-block;"><input type="text"  class="form-control" value="<?php echo $row["name"];?>" disabled /></div></td><td style="padding:10px;"><input type="number" step="any" class="form-control" value="0" name="<?php echo $row["uname"];?>" id="<?php echo $row["uname"];?>" required /></td><td style="padding:10px;"><input type="number" step="any" class="form-control" value="0" name="<?php echo $row["uname"].'e';?>" id="<?php echo $row["uname"].'e';?>" required/></td></tr>
                                                        <script>
														myuserarray[usercnt]=<?php echo $row["uname"];?>;
														usercnt++;
														</script>
														<?php
														$cnt++;
                                                    }
                                            }
                                    ?>
									
                                    <tr>
									<td style="padding:10px;"></td>
									<td style="padding:10px;"><input type="submit" id="b2" class="btn btn-primary btn-block btn-flat" value="Share Expense" /></td>
									<td style="padding:10px;"></td>
									</tr>
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
	<script src="plugins/iCheck/icheck.min.js"></script>
	<script src="dist/js/async.js"></script>
		<script>	
		var totfinamt=0;
		var totbillamt=0;
		var f=0;
		var b=0;
		$(document).ready(function() {
		$('#share').on('submit', function(e){
			totfinamt=0;
			totbillamt=0;
			f=0;
			b=0;
			for(var i=0;i<myuserarray.length;i++)
				{
					if(userenabledarray[i]==1)
					{
						totfinamt=parseFloat(totfinamt)+parseFloat(document.getElementById(myuserarray[i].id+'e').value);
						totbillamt=parseFloat(totbillamt)+parseFloat(document.getElementById(myuserarray[i].id).value);
					}
				}
				var camtint = document.getElementById("amount").value;
				var valid=false;
				if(parseInt(totfinamt)!=parseInt(camtint))
				{
					alert('We think you have an error in your expense - Total bill amount '+parseInt(camtint)+' is not equal to Total expense amount '+parseInt(totfinamt));
				}
				else
				{
					f=1;
				}
			    if(parseInt(totbillamt)!=parseInt(camtint))
				{
					alert('We think you have an error in your expense - Total bill amount '+parseInt(camtint)+' is not equal to Total paid amount '+parseInt(totbillamt));
				}
				else
				{
					b=1;
				}
				if((f==1)&&(b==1))
				{
					valid=true;
				}
			if(!valid) {
		e.preventDefault();
		}
	});
	});
			
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
    <script type="text/javascript">
    var names = [];
    var c=0;
    var famount = 0;
	var ctot=0;
	for(var i=0;i<myuserarray.length;i++)
		userenabledarray[i]=1;
	function check(name,id)
	{
		var cbox=name;
		name=name+'c';
		if(document.getElementById(name).checked) {
				userenabledarray[id]=1;
		} else {
				userenabledarray[id]=0;
		}
		ctot=0;
    for(var i=0;i<myuserarray.length;i++)
		{
			if(userenabledarray[i]==1)
			ctot+=1;	
		}
		var tamount = parseFloat(document.getElementById("amount").value).toFixed(2);
		var camount = parseFloat(tamount/ctot).toFixed(2);
		for(var i=0;i<myuserarray.length;i++)
		{
			if(userenabledarray[i]==1)
			{
				document.getElementById(myuserarray[i].id+'e').value=camount;
			}
			else{
				document.getElementById(myuserarray[i].id+'e').value=0;
				document.getElementById(myuserarray[i].id).value=0;
			}
		}
		if(userenabledarray[id]==1)
		{
			document.getElementById(cbox).disabled = false;
			document.getElementById(cbox+'e').disabled = false;
		}
		else
		{
			document.getElementById(cbox).disabled = true;
			document.getElementById(cbox+'e').disabled = true;
		}
	}
	
            function calc()
            {
                var amount = document.getElementById("amount").value;
                famount = document.getElementById("amount").value;; 
                document.getElementById("<?php echo $myusr ?>").value = amount;
				setcal();
            }
            function setcal()
            {
				var tamt = parseFloat(document.getElementById("amount").value).toFixed(2);
				var tcnt=0;
				for(var i=0;i<myuserarray.length;i++)
					{
						if(userenabledarray[i]==1)
							tcnt+=1;	
					}
				var camt = parseFloat(tamt/tcnt).toFixed(2);
				for(var i=0;i<myuserarray.length;i++)
				{
					if(userenabledarray[i]==1)
			{
				document.getElementById(myuserarray[i].id+'e').value=camt;
			}
			else{
				document.getElementById(myuserarray[i].id+'e').value=0;
			}
				}
            }
            </script>
  </body>
</html>
<?php
}
else
{
  header('Location:login.php');
}
?>