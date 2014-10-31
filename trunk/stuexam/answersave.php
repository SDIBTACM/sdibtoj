<?
	@session_start();
	if(!isset($_SESSION['user_id']))
	{
		echo "<a href='../loginpage.php'>Please Login First!</a>";
		exit(0);
	}
	if(!isset($_POST['eid']))
	{
		echo "No Such Exam";
		exit(0);
	}
	require_once("../include/db_info.inc.php");
	$eid=intval(trim($_POST['eid']));
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
	$now=time();
	mysql_free_result($result);
	if($now<$starttimeC)
	{
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
		echo "<script language='javascript'>\n";
	    echo "alert(\"You have no privilege\");\n";  
        echo "location='./'\n";
        echo "</script>";
	}
	//start
	$cntchoose=0;
	$tempsql="";
	$query="DELETE FROM `ex_stuanswer` WHERE `user_id`='$user_id' AND `exam_id`='$eid'";
	mysql_query($query) or die(mysql_error());
	$query="SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='1'";
	$result=mysql_query($query) or die(mysql_error());
	while($row=mysql_fetch_object($result)){
		if(isset($_POST["xzda$row->question_id"])){
			$myanswer=trim($_POST["xzda$row->question_id"]);
			if($cntchoose==0){
				$tempsql="INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','1','$row->question_id','$myanswer')";
				$cntchoose=1;
			}
			else
				$tempsql=$tempsql.",('$user_id','$eid','1','$row->question_id','$myanswer')";
		}
	}
	if(!empty($tempsql))
		mysql_query($tempsql) or die(mysql_error());
	mysql_free_result($result);
	//choose over
	$cntjudge=0;
	$tempsql="";
	$query="SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='2'";
	$result=mysql_query($query) or die(mysql_error());
	while($row=mysql_fetch_object($result)){
		if(isset($_POST["pdda$row->question_id"])){
			$myanswer=trim($_POST["pdda$row->question_id"]);
			if($cntjudge==0){
				$tempsql="INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','2','$row->question_id','$myanswer')";
				$cntjudge=1;
			}
			else
				$tempsql=$tempsql.",('$user_id','$eid','2','$row->question_id','$myanswer')";
		}
	}
	if(!empty($tempsql))
		mysql_query($tempsql) or die(mysql_error());
	mysql_free_result($result);
	//judge over
	$cntfill=0;
	$tempsql="";
	$query="SELECT `fill_id`,`answer_id` FROM `fill_answer` WHERE `fill_id` IN 
	( SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='3')";
	$result=mysql_query($query) or die(mysql_error());
	while($row=mysql_fetch_object($result)){
		$name=$row->fill_id."tkda";
		$myanswer = $_POST["$name$row->answer_id"];
		if(isset($_POST["$name$row->answer_id"])&&(!empty($myanswer)||$myanswer=="0")){
  			$myanswer = trim($myanswer);
  			//$myanswer = stripslashes($myanswer);
  			$myanswer = htmlspecialchars($myanswer);
  			$myanswer = mysql_real_escape_string($myanswer);
  			if($cntfill==0){
				$tempsql="INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','3','$row->fill_id','$row->answer_id$myanswer')";
				$cntfill=1;
			}
			else
				$tempsql=$tempsql.",('$user_id','$eid','3','$row->fill_id','$row->answer_id$myanswer')";
		}
	}
	if(!empty($tempsql))
		mysql_query($tempsql) or die(mysql_error());
	mysql_free_result($result);
	//fillover
	echo "True";
?>
