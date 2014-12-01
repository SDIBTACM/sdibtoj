<?
	@session_start();
	if(!isset($_SESSION['user_id']))
	{
		echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
		exit(0);
	}
	if (!(isset($_SESSION['administrator'])
		||isset($_SESSION['contest_creator']))){
	echo "You have no privilege";
	echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
	exit(1);
	}
	require_once("../../include/db_info.inc.php");
?>
<?
	if(isset($_GET['eid'])&&isset($_GET['users']))
	{
		if(isset($_GET['rjd']))
			$rejudge=1;
		else
			$rejudge=0;
		$eid=intval(trim($_GET['eid']));
		$users=trim($_GET['users']);
		$users=mysql_real_escape_string($users);
		$sql ="SELECT `start_time`,`end_time`,`creator` FROM `exam` WHERE `exam_id`='$eid'";
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
		$creator = $row[2];
		$start_timeC=strftime("%Y-%m-%d %X",($starttimeC));
		$end_timeC=strftime("%Y-%m-%d %X",($endtimeC));
		$now=time();
		mysql_free_result($result);

		if($rejudge==0)//if not rejudge
		{
			$sql="SELECT COUNT(*) FROM `ex_privilege` WHERE `user_id`='".$users."' AND `rightstr`='e$eid'";
			$result=mysql_query($sql) or die(mysql_error());
			$cnt1=mysql_result($result, 0);
			mysql_free_result($result);
			if($cnt1==0)
			{
				echo "<script language='javascript'>\n";
				echo "alert(\"Student ID is wrong!\");\n";
				echo "location.href='./exam_user_score.php?eid=$eid'";
				echo "</script>";
			}
		}
		if($now<$starttimeC)
		{
			echo "<h1>Exam Not Start</h1>";
			exit(0);
		}

		$sql = "SELECT COUNT(*) FROM `ex_student` WHERE `exam_id`='$eid' AND `user_id`='".$users."'";
		$result = mysql_query($sql) or die(mysql_error());
		$mark = mysql_result($result,0);
		mysql_free_result($result);

		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "<script language='javascript'>\n";
			echo "alert(\"You have no privilege to do it!\");\n";
			echo "location.href='./exam_user_score.php?eid=$eid'";
			echo "</script>";
		}
		else
		{
			//test paper
			require_once("../func.php");
			rejudgepaper($users,$eid,$start_timeC,$end_timeC,$mark);
			//
			echo "<script language='javascript'>\n";
        	echo "location.href='./exam_user_score.php?eid=$eid'";
        	echo "</script>";
		}
	}
	else
	{
		echo "<script language='javascript'>\n";
	    echo "alert(\"Invaild path\");\n";  
        echo "history.go(-1);\n";
        echo "</script>";
	}
?>
