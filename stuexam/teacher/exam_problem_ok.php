<?
	@session_start();
	if (!(isset($_SESSION['administrator'])
		||isset($_SESSION['contest_creator']))){
	echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
	exit(1);
	}
	require_once("../../include/db_info.inc.php");
	if(isset($_GET['eid'])&&isset($_GET['quesid'])&&isset($_GET['type'])&&isset($_GET['sid']))
	{
		$eid=intval($_GET['eid']);
		$quesid=intval($_GET['quesid']);
		$typeid=intval($_GET['type']);
		$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$creator=mysql_result($priresult, 0);
		mysql_free_result($priresult);
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "No privilege";
		}
		else
		{
			if($typeid==1&&$quesid>0&&$eid>0)
			{
				//$sql="INSERT INTO `exp_choose` VALUES('$eid','$quesid')";
				$sql="INSERT INTO `exp_question`(`type`,`exam_id`,`question_id`) VALUES('1','$eid','$quesid')";
			}
			else if($typeid==2&&$quesid>0&&$eid>0)
			{
				//$sql="INSERT INTO `exp_judge` VALUES('$eid','$quesid')";
				$sql="INSERT INTO `exp_question`(`type`,`exam_id`,`question_id`) VALUES('2','$eid','$quesid')";
			}
			else if($typeid==3&&$quesid>0&&$eid>0)
			{
				//$sql="INSERT INTO `exp_fill` VALUES('$eid','$quesid')";
				$sql="INSERT INTO `exp_question`(`type`,`exam_id`,`question_id`) VALUES('3','$eid','$quesid')";
			}
			mysql_query($sql) or die(mysql_error());
			echo "已添加";
		}
	}
	else
	{
		echo "Invaild path";
	}
?>
