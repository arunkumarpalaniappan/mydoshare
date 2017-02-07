<!DOCTYPE html>
<html>
  <head>
<?php
session_name('doShare');
session_start();
include('php/_Config.php');
$myname = $myemail = "";
$tot = $grp = $my = $bal = 0;
if($_SESSION['user']!="")
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
        if($row["groupn"]=="na")
        {
          header('Location:index-nogroup.php');
        }
		else if($row["groupn"]=="needtobeapproved")
		{
			header('Location:index-notaccepted.php');
		}
        else
        {
                $gpname = $_SESSION["gpname"];
                $grp1 = $gpname.'_expense';
                $grp2 = $gpname.'_bills';
                $get = "SELECT SUM($myuser) AS total FROM `$grp2`";
                $rst = $conn->query($get);
                         if($rst->num_rows > 0)
                         {
                             while($row = $rst->fetch_assoc()) {
                                $my = $row["total"];
                                
                            }
                         }
                $set = "SELECT SUM($myuser) AS set1 FROM `$grp1`";
                $rst = $conn->query($set);
                         if($rst->num_rows > 0)
                         {
                             while($row = $rst->fetch_assoc()) {
                                $set = $row["set1"];
                                
                            }
                         }
                         $bal = $my - $set;
			    $get = "SELECT SUM($myuser) AS total FROM `$grp2`  WHERE MONTH(`c_time`) = MONTH(CURDATE()) ";
                $rst = $conn->query($get);
                         if($rst->num_rows > 0)
                         {
                             while($row = $rst->fetch_assoc()) {
                                $my = $row["total"];
                                
                            }
                         }
                $set = "SELECT SUM($myuser) AS cnt FROM `$grp1` WHERE MONTH(`c_time`) = MONTH(CURDATE());";
                 $rst = $conn->query($set);
                         if($rst->num_rows > 0)
                         {
                             while($row = $rst->fetch_assoc()) {
                                $tot = $row["cnt"];
                                
                            }
                         }
                $grp = "SELECT COUNT(id) AS grp FROM `_doshare_groups` where groupn='$gpname'";
                 $rst = $conn->query($grp);
                         if($rst->num_rows > 0)
                         {
                             while($row = $rst->fetch_assoc()) {
                                $grp = $row["grp"];
                                $grp--;                                
                            }
                         }
        }
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
    <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>mydoShare - Split your group expenses,bills and IOUs in a single click</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" >
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
          <div class="user-panel" >
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
            <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<li><a href="share.php"><i class="fa fa-edit"></i> <span>Share Your Expense</span></a></li>
			<li><a href="delete.php"><i class="fa fa-times"></i> <span>Edit/Delete Expense</span></a></li>
            <li><a href="showbalance.php"><i class="fa fa-pie-chart"></i> <span>Show Balance</span></a></li>
            <li><a href="sendemail.php"><i class="fa fa-envelope-o"></i> <span>Send Email</span></a></li>		
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
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>
        <section class="content">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
			  <?php
			  if($bal>0)
			  {
				  ?>
					<span class="info-box-icon bg-green">
				  <?php
			  }
			  else
			  {
				  ?>
					<span class="info-box-icon bg-red">
				  <?php
			  }
			  ?><i class="ion ion-ios-email-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Balance</span>
                  <span class="info-box-number"><small>&#8377;</small><?php echo round($bal, 2);?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-pie-graph"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">My Shares</span>
                  <span class="info-box-number" id="myshares"><small>&#8377;</small><?php echo round($my, 2);?><small><br/>(Current Month)</small></span>
                </div>
              </div>
            </div>
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-clipboard"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Expenses</span>
                  <span class="info-box-number" id="myexpense"><small>&#8377;</small><?php echo round($tot, 2);?><small><br/>(Current Month)</small></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-plum"><i class="ion ion-ios-people"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Friends</span>
                  <span class="info-box-number"> <?php echo $grp;?></span>
                </div>
              </div>
            </div>
          </div>
		  <div id="containerexp" style="width:100%"></div>
			 <div class="row" style="width:auto">
            <div class="col-md-12" style="width:auto">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Recent Bills</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
					<div class="box-body table-responsive no-padding">
                      <table class="table table-hover">                                
                                <tbody id="content">
                                    <tr>
                                     <td style="padding:10px;"> Category </td>
                                        <td style="padding:10px;" > Bill Name </td>
                                        <td style="padding:10px;"> Bill Total </td>
                                        <td style="padding:10px;"> Updated By </td>
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
                                            $rvalue = 0;
                                            if($getrecent->num_rows > 0)
                                            {
                                                while ($recentdata = $getrecent->fetch_assoc()) {
                                                    $rvalue = $recentdata['rval'];
                                                }
                                            }
                                    for($z=$rvalue;$z>$rvalue-10;$z--)
                                    {
                                    $i=0;
                                    $grp = $_SESSION["gpname"];
                                    $gp1 = $grp.'_bills';
                                    $get = "SELECT * from $gp1 where id=$z";
                                     $result = $conn->query($get);
                                        if ($result->num_rows > 0) {
                                                 while($row = $result->fetch_assoc()) {
                                                        ?><tr>
                                                        <td style="padding:10px;" rowspan="2" ><?php echo $row["category"];?></td> 
                                                            <td style="padding:10px;" rowspan="2" ><?php echo $row["billname"];?></td>
                                                                <td style="padding:10px;" rowspan="2" ><?php echo $row["total"];?></td> 
                                                                <td style="padding:10px;" rowspan="2" ><?php echo $row["createdby"];?></td> 
                                                                <td style="padding:10px;" rowspan="2" ><?php echo $row["c_time"];?></td>           
                                    <?php                 
                                    $gpn1 = $grpn.'_bills';
                                    $gpn2 = $grpn.'_expense';
                                    $exp = "SELECT * from $gpn1 where id=$z";
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
                                                           </tr>
                                                      <?php                                                        
                                                    }
                                            }
                                        ?>
                                    <?php
                                    $exp = "SELECT * from $gpn2 where id=$z";
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
                                                           <td style="padding:10px;color:red;" > <?php if($getr[$getrv]==0) {echo '-';}else{echo "&#8377;".round($getr[$getrv], 2);}?></td>
                                                          <?php
                                                                }
                                                                ?>
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
                    <div align="center">
                            <br><a href="showmore.php" class="btn btn-default"><i class="fa fa-sign-out"></i> Show More</a>
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
    <script src="dist/js/jquery.confirm.js"></script>
   
    
	<script  src="dist/js/highcharts.js"></script>
	<script  src="dist/js/exporting.js"></script>
		<script  src="plugins/iCheck/icheck.min.js"></script>
		
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
	<script type="text/javascript">
		var grp = '<?php echo $grpn; ?>';
		var usr = '<?php echo $myuser; ?>';
		var my = '<?php echo $my; ?>';
		var tot = '<?php echo $tot; ?>';
		  $.ajax({
                url: 'php/chartexpense.php',
				data: 'grpname='+grp+'&user='+usr,
                type: 'POST',
            success:function(response) {
                          createChart(response);
                }
      });
	  function createChart(data)
	  {
	      
		      var arr = []
                data=JSON.parse(data);
           for(item in data)
		   {
            var obj = {};
            obj.name = data[item].category;
            obj.y = parseInt(data[item].y);
            arr.push(obj);
        }
        var myJsonString = JSON.stringify(arr);
        var jsonArray = JSON.parse(JSON.stringify(arr));
	  	  drawChart(jsonArray);	
		  
	  }
	

function drawChart(mydata)
{
    $('#containerexp').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Expense Distributions(Current Month)'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: Rs. {point.y} ',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Expense',
            colorByPoint: true,
            data: mydata
        }]
    });
	drawChart1();
}
function drawChart1()
{
    $('#containerpaid').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Paid vs Expense'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: Rs. {point.y} ',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Paid',
            colorByPoint: true,
            data: [{
				name: 'Expense',
				y: parseInt(tot)
			},{
				name: 'Paid',
				y: parseInt(my)
			}]
        }]
    });
}
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
<?php
}
else
{
  header('Location:login.php');
}
?>