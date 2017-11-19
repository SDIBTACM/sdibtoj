<?
	@session_start();
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
	if(!isset($_GET['id']))
	{
		echo "No Such Problem";
		exit(0);
	}
	require_once("../include/db_info.inc.php");
	if(isset($OJ_LANG))
		require_once("../lang/$OJ_LANG.php");
	else
		require_once("../lang/en.php");
	require_once("../include/const.inc.php");

	$eid=intval($_GET['eid']);
	$id=intval($_GET['id']);
	$user_id=$_SESSION['user_id'];

	$sql ="SELECT `start_time`,`end_time` FROM `exam` WHERE `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$cnt = mysql_num_rows($result);
	if($cnt==0)
	{
		echo "No Such Exam";
		exit(0);
	}
	$row=mysql_fetch_array($result);
	$start_time=$row[0];
	$starttimeC=strtotime($start_time);
	$end_time=$row[1];
	$endtimeC=strtotime($end_time);

	$start_timeC=strftime("%Y-%m-%d %X",($starttimeC));
	$end_timeC=strftime("%Y-%m-%d %X",($endtimeC));

	$now=time();
	mysql_free_result($result);
	if($now<$starttimeC){
		echo "<h1>Exam Not Start</h1>";
		exit(0);
	}
	if($now>$endtimeC){
		echo "<h1>Exam Over</h1>";
		exit(0);
	}
	$prisql="SELECT COUNT(*) FROM `ex_privilege` WHERE `user_id`='".$user_id."' AND `rightstr`='e$eid'";
	$priresult=mysql_query($prisql) or die(mysql_error());
	$num=mysql_result($priresult, 0);
	mysql_free_result($priresult);
	if(!(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||$num))
	{
	    echo "You have no privilege";  
        exit(0);
	}
	$sql="SELECT `user_id` FROM `ex_student` WHERE `user_id`='".$user_id."' AND `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$row_cnt=mysql_num_rows($result);
	mysql_free_result($result);
	if($row_cnt)
	{
	    echo "You have taken part in it"; 
	    exit(0);
	}
	$query="SELECT COUNT(*) FROM `solution` WHERE `problem_id`='$id' AND `user_id`='".$user_id."' AND `result`=4 AND `in_date`>'$start_timeC' AND `in_date`<'$end_timeC'";
	$result=mysql_query($query) or die(mysql_error());
	$row_cnt=mysql_result($result, 0);
	mysql_free_result($result);
	if($row_cnt)
	{
		echo "<font color=blue size=3px>此题已正确,请不要重复提交</font>";
	}
	else
	{
		$query="SELECT `result` FROM `solution` WHERE `problem_id`='$id' AND `in_date`>'$start_timeC' AND `in_date`<'$end_timeC' AND `user_id`='".$user_id."' ORDER BY `solution_id` DESC limit 1";
		$result=mysql_query($query) or die(mysql_error());
		$row_cnt=mysql_num_rows($result);
		$row=mysql_fetch_array($result);
		$ans=$row['result'];
		if($row_cnt==0)
		{
			echo "<font color=green size=5px>未提交</font>";
		}
		else
		{
			echo "<font color=$judge_color[$ans] size=5px>$judge_result[$ans]</font>";
		}
		mysql_free_result($result);
	}
?>