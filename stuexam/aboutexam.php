<?
	require_once("../include/db_info.inc.php");
	if(!isset($_SESSION['user_id']))
	{
		echo "<a href='../loginpage.php'>Please Login First!</a>";
		exit(0);
	}
	if(!isset($_GET['eid']))
	{
		echo "No Such Exam";
		exit(0);
	}
	$eid=intval(trim($_GET['eid']));
	$user_id=$_SESSION['user_id'];
	$prisql="SELECT COUNT(*) FROM `ex_privilege` WHERE `user_id`='".$user_id."' AND `rightstr`='e$eid'";
	$priresult=mysql_query($prisql) or die(mysql_error());
	$num=mysql_result($priresult, 0);
	mysql_free_result($priresult);
	if (!(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||$num))
	{
		echo "<script language='javascript'>\n";
	    echo "alert(\"You have no privilege\");\n";  
        echo "location='./'\n";
        echo "</script>";
	}
	$sql = "SELECT `title`,`start_time`,`end_time`,`isvip`,`visible` FROM `exam` WHERE `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$cnt = mysql_num_rows($result);
	$row=mysql_fetch_array($result);
	if($cnt==0||$row['visible']=='N')
	{
		echo "No Such Exam";
		exit(0);
	}
	$title=$row[0];
	$start_time=$row[1];
	$end_time=$row[2];
	$isvip=$row[3];
	mysql_free_result($result);
	if($isvip=='Y')
    {           
		$today=date('Y-m-d');
		$ip1=$_SERVER['REMOTE_ADDR'];
		$sql="SELECT * FROM `loginlog` WHERE `user_id`='$user_id' AND `time`>='$today' AND ip<>'$ip1' AND 
		 `user_id` NOT IN( SELECT `user_id` FROM `privilege` WHERE `rightstr`='administrator' or `rightstr`='contest_creator') ORDER BY `time` DESC limit 0,1";
		$result=mysql_query($sql) or die(mysql_error());
		$row_cnt=mysql_num_rows($result);
		if($row_cnt>0)
		{  
			$row=mysql_fetch_row($result);
			mysql_free_result($result);
			echo "<script language='javascript'>\n";
			echo "alert('Do not login in diff machine,Please Contact administrator');\n";  
			echo "history.go(-1);\n";
			echo "</script>";
			exit(0);
		}
		mysql_free_result($result);
	}
	$sql="SELECT `nick` FROM `users` WHERE `user_id`='".$user_id."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$name=$row[0];
	mysql_free_result($result);
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
<div class="mainpage">
	<div class="container">
		<h5><?echo $title?></h5>
	</div>
	<div class="container">
	<div class="pull-left inforleft">
		<input type="hidden" name='eid' value=<?echo $eid?> />
		<h4>考生信息:</h4>
		<label>姓名:</label>
		<input type="text" id="name" maxlength="12" style="height:30px" value="<?echo $name?>" readonly/>
		<label>账号:</label>
		<input type="text" id="userid" maxlength="15" style="height:30px" value="<?echo $user_id?>" readonly/>
		<div>
		<input type="button" class="btn btn-warning" value="修改信息" 
		onclick="window.location.href='../modifypage.php'"/>
		</div>
	</div>
	<div class="pull-right inforright">
		<h4>考生须知:</h4>
		<ol>
			<li>请认真检查左侧个人真实信息,关系最后考试成绩,姓名为个人真实姓名,
			<br>账号格式为专业缩写+学号后八位,如jk11171101.</li>
			<li>请各位将手机关闭，考试期间手机响按作弊处理.</li>
			<li>考场内凡交头接耳、抄袭、打手势或大声喧哗的按作弊处理.</li>
			<li>考试过程中，一旦提交试卷，将显示得分，并且考试结束，请谨慎选择.<br>
			考试时间到，未提交试卷的，考试系统将自动提交试卷.</li>
			<li>采取各种方式抄录试题的按照严重作弊取理.</li>
			<li>请及时保存答案,防止特殊情况发生答案丢失.刷新界面前,请先保存答案</li>
		</ol>
	</div>
	</div>
	<div class="container" id="examtime">
	<div class="pull-left inforleft">
	<table class="table" border="0" >
	<tr>
		<td>Start Time:</td>
		<td><?echo $start_time?></td>
	</tr>
	<tr>
		<td>End Time:</td>
		<td><?echo $end_time?></td>
	</tr>
	<tr>
		<td>Current Time:</td>
		<td><span id="nowdate"><?=date("Y-m-d H:i:s")?></span></td>
	</tr>
	</table>
	</div>
	<?
		$start_timeC=strtotime($start_time);
		$end_timeC=strtotime($end_time);
		$now=time();
		if($now<$start_timeC||$now>$end_timeC){
			echo "<div class=\"pull-right btn-start\">";
			echo "<a href=\"#\" disabled=\"disabled\">考试未进行</a>";
			echo "</div>";
		}
		else{
			echo "<div class=\"pull-right btn-start\">";
			//echo "<a href=\"../status.php\" onclick=\"window.open('./showquestion.php?eid=$eid')\">开始考试</a>";
			echo "<a href=\"./showquestion.php?eid=$eid\")>开始考试</a>";
			echo "</div>";
		}
	?>
	</div>
</div>
<script>
var diff=new Date("<?=date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
function clock(){
     var x,h,m,s,n,xingqi,y,mon,d;
     var x = new Date(new Date().getTime()+diff);
     y = x.getYear()+1900;
      if (y>3000) y-=1900;
     mon = x.getMonth()+1;
     d = x.getDate();
     xingqi = x.getDay();
     h=x.getHours();
     m=x.getMinutes();
     s=x.getSeconds();
     n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
     document.getElementById('nowdate').innerHTML=n;
     setTimeout("clock()",1000);
   } 
   clock();
</script>
</body>
<?
	require_once("exam-footer.php");
?>