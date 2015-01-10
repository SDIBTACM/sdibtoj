<?
	@session_start();
	require_once("myinc.inc.php");
	checkAdmin(2);
	if(isset($_GET['eid'])&&isset($_GET['quesid'])&&isset($_GET['type'])&&isset($_GET['sid']))
	{
		$eid=intval($_GET['eid']);
		$quesid=intval($_GET['quesid']);
		$typeid=intval($_GET['type']);
		$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$creator=mysql_result($priresult, 0);
		mysql_free_result($priresult);
		if(checkAdmin(4,$creator))
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
	else{
		echo "Invaild path";
	}
?>
