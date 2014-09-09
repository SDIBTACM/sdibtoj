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
	if(isset($_GET['eid'])&&isset($_GET['type'])&&isset($_GET['qid']))
	{
		$eid=intval($_GET['eid']);
		$type=intval($_GET['type']);
		$qid=intval($_GET['qid']);
		if(is_numeric($eid)&&is_numeric($type)&&is_numeric($qid)&&$eid>0&&$type>0&&$qid>0)
		{
			$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
			$priresult=mysql_query($prisql) or die(mysql_error());
			$creator=mysql_result($priresult, 0);
			mysql_free_result($priresult);
			if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
			{
				echo "<script language='javascript'>\n";
	    		echo "alert(\"You have no privilege to delete it!\");\n";
	    		echo "history.go(-1);";
        		echo "</script>";
			}
			else
			{
				if($type==1)
				{
					//$sql="DELETE FROM `exp_choose` WHERE `exam_id`='$eid' AND `choose_id`='$qid'";
					$sql="DELETE FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='1' AND `question_id`='$qid'";
					mysql_query($sql) or die(mysql_error());
					echo "<script language=javascript>location='add_exam_problem.php?eid=$eid&type=5';</script>";
				}
				else if($type==2)
				{
					//$sql="DELETE FROM `exp_judge` WHERE `exam_id`='$eid' AND `judge_id`='$qid'";
					$sql="DELETE FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='2' AND `question_id`='$qid'";
					mysql_query($sql) or die(mysql_error());
					echo "<script language=javascript>location='add_exam_problem.php?eid=$eid&type=5';</script>";
				}
				else if($type==3)
				{
					//$sql="DELETE FROM `exp_fill` WHERE `exam_id`='$eid' AND `fill_id`='$qid'";
					$sql="DELETE FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='3' AND `question_id`='$qid'";
					mysql_query($sql) or die(mysql_error());
					echo "<script language=javascript>location='add_exam_problem.php?eid=$eid&type=5';</script>";
				}
				else
				{
					echo "<script language='javascript'>\n";
		    		echo "alert(\"Invaild type\");\n";  
	        		echo "history.go(-1);\n";
	        		echo "</script>";
				}
			}
		}
		else
		{
			echo "<script language='javascript'>\n";
	    	echo "alert(\"Invaild data\");\n";  
        	echo "history.go(-1);\n";
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