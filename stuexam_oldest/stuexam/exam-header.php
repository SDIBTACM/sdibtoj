<?
	@session_start();
	if(!isset($_SESSION['user_id']))
	{
		echo "<a href='../loginpage.php'>Please Login First!</a>";
		exit(0);
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="./css/examsys.css" rel="stylesheet" type="text/css">
  <title>程序设计考试系统</title>
  </head>
  <script src="./css/jquery.js" type="text/javascript"></script>
  <script src="./css/bootstrap.min.js" type="text/javascript"></script>
  <body>
  	<div class="nav">
  		<div class="container" style="width:85%">
  			<ul class="pull-left">
  				<li><a href="../">Online Judge</a></li>
          <li><a href="./">Exam List</a></li>
  			</ul>
  			<ul class="pull-right">
          <font color=red>(*请不要使用版本过低的浏览器,建议使用Chrome或Firefox)</font>
  				<li><a>欢迎您&nbsp;:&nbsp;<?echo $_SESSION['user_id']?></a>|</li>
  				<?
 					if (isset($_SESSION['administrator'])||
 						isset($_SESSION['contest_creator'])||
 						isset($_SESSION['problem_editor'])){
						print "<li><a href=\"./teacher/\">教师管理</a>|</li>";
					}
					else	
  						print "<li><a href=\"./aboutuser.php\">个人中心</a>|</li>";
  				?>
  				<li><a href="../logout.php">LOG OUT</a></li>
  			</ul>
  		</div>
  		<div class="jumbotron">
  			<div class="container">
  				<h1>程序设计考试系统</h1>
  			</div>
  		</div>
      <?
        $fp=fopen("../admin/msg.txt","r");
        $msg="";
        while (!feof($fp)){
          $strtmp=fgets($fp);
          $msg=$msg.$strtmp;
        }
        if(strlen($msg)>5){
          echo "<marquee scrollamount=3 behavior=ALTERNATE scrolldelay=150 style='padding-top:10px'>";
          echo "<font color=#0CA6F4 size=4px><strong>";
          echo $msg;
          echo "</strong></font>";
          echo "</marquee>";
        }
      ?>
  	</div>