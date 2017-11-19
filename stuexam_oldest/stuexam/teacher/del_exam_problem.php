<?php
	@session_start();
	require_once("myinc.inc.php");
	checkuserid();
	checkAdmin(2);
?>
<?php
	if(isset($_GET['eid'])&&isset($_GET['type'])&&isset($_GET['qid']))
	{
		$eid=intval($_GET['eid']);
		$type=intval($_GET['type']);
		$qid=intval($_GET['qid']);
		if(is_numeric($eid)&&is_numeric($type)&&is_numeric($qid)&&$eid>0&&$type>0&&$qid>0)
		{
			$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
			$row=fetchOne($prisql);
			$creator=$row['creator'];
			if(checkAdmin(4,$creator))
			{
				alertmsg("You have no privilege to delete it!");
			}
			else
			{
				if($type==1)
				{
					Delete('exp_question',"exam_id='{$eid}' and type='1' and question_id='{$qid}'");
					echo "<script language=javascript>location='add_exam_problem.php?eid=$eid&type=5';</script>";
				}
				else if($type==2)
				{
					Delete('exp_question',"exam_id='{$eid}' and type='2' and question_id='{$qid}'");
					echo "<script language=javascript>location='add_exam_problem.php?eid=$eid&type=5';</script>";
				}
				else if($type==3)
				{
					Delete('exp_question',"exam_id='{$eid}' and type='3' and question_id='{$qid}'");
					echo "<script language=javascript>location='add_exam_problem.php?eid=$eid&type=5';</script>";
				}
				else{
					alertmsg("Invaild type");
				}
			}
		}
		else{
			alertmsg("Invaild data");
		}
	}
	else{
		alertmsg("Invaild path");
	}
?>