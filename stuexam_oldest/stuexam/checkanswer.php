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

	$start_timeC=strftime("%Y-%m-%d %X",($starttimeC));
	$end_timeC=strftime("%Y-%m-%d %X",($endtimeC));

	$now=time();
	mysql_free_result($result);
	if($now<$starttimeC)
	{
		echo "<h1>Exam Not Start</h1>";
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
	$sql="SELECT COUNT(*) FROM `ex_student` WHERE `user_id`='".$user_id."' AND `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$row_cnt=mysql_result($result, 0);
	mysql_free_result($result);
	if($row_cnt)
	{
		echo "<script language='javascript'>\n";
	    echo "alert(\"You have submitted\");\n";  
        echo "location='./'\n";
        echo "</script>";
	}
?>
<?
	$sql="SELECT `choosescore`,`judgescore`,`fillscore`,`prgans`,`prgfill`,`programscore` FROM `exam` WHERE `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($result);
	$choosescore=$row['choosescore'];
	$judgescore=$row['judgescore'];
	$fillscore=$row['fillscore'];
	$prgans=$row['prgans'];
	$prgfill=$row['prgfill'];
	$programscore=$row['programscore'];
	mysql_free_result($result);

	$choosesum=0;
	$judgesum=0;
	$fillsum=0;
	$programsum=0;
	$sum=0;

	$cntchoose=0;
	$tempsql="";
	// $query="DELETE FROM `ex_stuanswer` WHERE `user_id`='$user_id' AND `exam_id`='$eid'";
	// mysql_query($query) or die(mysql_error());
	$query="SELECT `choose_id`,`answer` FROM `ex_choose` WHERE `choose_id` IN
	(SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='1')";
	$result=mysql_query($query) or die(mysql_error());
	while($row=mysql_fetch_object($result)){
		if(isset($_POST["xzda$row->choose_id"])){
			$myanswer=trim($_POST["xzda$row->choose_id"]);
			if($cntchoose==0){
				$tempsql="INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','1','$row->choose_id','1','$myanswer')";
				$cntchoose=1;
			}
			else
				$tempsql=$tempsql.",('$user_id','$eid','1','$row->choose_id','1','$myanswer')";
			if($myanswer==$row->answer)
				$choosesum+=$choosescore;
		}
	}
	if(!empty($tempsql))
	{
		$tempsql=$tempsql." on duplicate key update `answer`=values(`answer`)";
		mysql_query($tempsql) or die(mysql_error());
	}
	mysql_free_result($result);
	//choose over

	$cntjudge=0;
	$tempsql="";
	$query="SELECT `judge_id`,`answer` FROM `ex_judge` WHERE `judge_id` IN 
	(SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='2')";
	$result=mysql_query($query) or die(mysql_error());
	while($row=mysql_fetch_object($result)){
		if(isset($_POST["pdda$row->judge_id"])){
			$myanswer=trim($_POST["pdda$row->judge_id"]);
			if($cntjudge==0){
				$tempsql="INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','2','$row->judge_id','1','$myanswer')";
				$cntjudge=1;
			}
			else
				$tempsql=$tempsql.",('$user_id','$eid','2','$row->judge_id','1','$myanswer')";
			if($myanswer==$row->answer)
				$judgesum+=$judgescore;
		}
	}
	if(!empty($tempsql))
	{
		$tempsql=$tempsql." on duplicate key update `answer`=values(`answer`)";
		mysql_query($tempsql) or die(mysql_error());
	}
	mysql_free_result($result);
	//judge over

	$cntfill=0;
	$tempsql="";
	$query="SELECT `fill_answer`.`fill_id`,`answer_id`,`answer`,`answernum`,`kind` FROM `fill_answer`,`ex_fill` WHERE  
	`fill_answer`.`fill_id`=`ex_fill`.`fill_id` AND `fill_answer`.`fill_id` IN ( SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='3')";
	$result=mysql_query($query) or die(mysql_error());
	while($row=mysql_fetch_object($result)){
		$name=$row->fill_id."tkda";
		if(isset($_POST["$name$row->answer_id"])){
			$myanswer = trim($_POST["$name$row->answer_id"]);
			//$myanswer = stripslashes($myanswer);
  			$myanswer = htmlspecialchars($myanswer);
  			$myanswer = mysql_real_escape_string($myanswer);
  			if($cntfill==0){
				$tempsql="INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','3','$row->fill_id','$row->answer_id','$myanswer')";
				$cntfill=1;
			}
			else
				$tempsql=$tempsql.",('$user_id','$eid','3','$row->fill_id','$row->answer_id','$myanswer')";

			$rightans = trim($row->answer);
			$rightans = mysql_real_escape_string($rightans);

			if($myanswer==$rightans&&strlen($myanswer)==strlen($rightans)){
				if($row->kind==1)
					$fillsum+=$fillscore;
				else if($row->kind==2)
					$fillsum=$fillsum+$prgans/$row->answernum;
				else if($row->kind==3)
					$fillsum=$fillsum+$prgfill/$row->answernum;
			}
		}
	}
	if(!empty($tempsql))
	{
		$tempsql=$tempsql." on duplicate key update `answer`=values(`answer`)";
		mysql_query($tempsql) or die(mysql_error());
	}
	mysql_free_result($result);
	//fillover
	
	$query="SELECT distinct `question_id`,`result` FROM `exp_question`,`solution` WHERE `exam_id`='$eid' AND `type`='4' AND `result`='4'  
	AND `in_date`>'$start_timeC' AND `in_date`<'$end_timeC' AND `user_id`='".$user_id."' AND `exp_question`.`question_id`=`solution`.`problem_id`";
	$result=mysql_query($query) or die(mysql_error());
	$row_cnt=mysql_num_rows($result);
	$programsum=$row_cnt*$programscore;
	mysql_free_result($result);
	//$program over
	$sum=$choosesum+$judgesum+$fillsum+$programsum;
	$sql="INSERT INTO `ex_student` VALUES('".$user_id."','$eid','$sum','$choosesum','$judgesum','$fillsum','$programsum')";
	mysql_query($sql) or die(mysql_error());
	echo "<script>location='aboutuser.php'</script>";
?>
