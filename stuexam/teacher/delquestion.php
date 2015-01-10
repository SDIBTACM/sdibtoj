<?php
	@session_start();
	require_once("myinc.inc.php");
	checkuserid();
	checkAdmin(3);
	if(!isset($_GET['getkey']))
		exit(1);
	require_once("../../include/check_get_key.php");
?>
<?php
	if(isset($_GET['type'])&&isset($_GET['id'])){
		$type=intval($_GET['type']);
		$id=intval($_GET['id']);
		if(filter_var($type, FILTER_VALIDATE_INT)&&filter_var($id, FILTER_VALIDATE_INT)&&$id>0){
			if($type==1)//delete choose
			{
				$prisql="SELECT `creator`,`isprivate` FROM `ex_choose` WHERE `choose_id`='$id'";
				$row=fetchOne($prisql);
				$creator=$row['creator'];
				$isprivate=$row['isprivate'];
				if(checkAdmin(4,$creator))
				{
					alertmsg("You have no privilege to delete it!");
				}
				else if($isprivate==2&&!checkAdmin(1))
				{
					alertmsg("You have no privilege to delete it!","admin_choose.php",0);
				}
				else
				{
					Delete('ex_choose',"choose_id={$id}");
					Delete('exp_question',"question_id={$id} and type=1");
					Delete('ex_stuanswer',"question_id={$id} and type=1");
					echo "<script language=javascript>location='admin_choose.php';</script>";
				}
			}
			else if($type==2)//delete judge
			{
				$prisql="SELECT `creator`,`isprivate` FROM `ex_judge` WHERE `judge_id`='$id'";
				$row=fetchOne($prisql);
				$creator=$row['creator'];
				$isprivate=$row['isprivate'];
				if(checkAdmin(4,$creator))
				{
					alertmsg("You have no privilege to delete it!");
				}
				else if($isprivate==2&&!checkAdmin(1))
				{
					alertmsg("You have no privilege to delete it!","admin_judge.php",0);
				}
				else
				{
					Delete('ex_judge',"judge_id={$id}");
					Delete('exp_question',"question_id={$id} and type=2");
					Delete('ex_stuanswer',"question_id={$id} and type=2");
					echo "<script language=javascript>location='admin_judge.php';</script>";
				}
			}
			else if($type==3)//delete fill
			{
				$prisql="SELECT `creator`,`isprivate` FROM `ex_fill` WHERE `fill_id`='$id'";
				$row=fetchOne($prisql);
				$creator=$row['creator'];
				$isprivate=$row['isprivate'];
				if(checkAdmin(4,$creator))
				{
					alertmsg("You have no privilege to delete it!");
				}
				else if($isprivate==2&&!checkAdmin(1))
				{
					alertmsg("You have no privilege to delete it!","admin_fill.php",0);
				}
				else
				{
					Delete('ex_fill',"fill_id={$id}");
					Delete('fill_answer',"fill_id={$id}");
					Delete('exp_question',"question_id={$id} and type=3");
					Delete('ex_stuanswer',"question_id={$id} and type=3");
					echo "<script language=javascript>location='admin_fill.php';</script>";
				}
			}
			else if($type==9)//delete exam
			{
				$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$id'";
				$row=fetchOne($prisql);
				$creator=$row['creator'];
				if(checkAdmin(4,$creator))
				{
					alertmsg("You have no privilege to delete it!");
				}
				else
				{
					//if the exam was deleted
					//the info of exam was deleted
					Update('exam',array("visible"=>"N"),"exam_id={$id}");
					// //the info of question in the exam was deleted
					// $query="DELETE FROM `exp_question` WHERE `exam_id`='$id'";
					// mysql_query($query) or die(mysql_error());
					// //students have privilege were deleted
					// $query="DELETE FROM `ex_privilege` WHERE `rightstr`='e$id'";
					// mysql_query($query) or die(mysql_error());
					// //students' save_answers were deleted
					// $query="DELETE FROM `ex_stuanswer` WHERE `exam_id`='$id'";
					// mysql_query($query) or die(mysql_error());
					// //the scores of students were deleted
					// $query="DELETE FROM `ex_student` WHERE `exam_id`='$id'";
					// mysql_query($query) or die(mysql_error());
					echo "<script language=javascript>location='./';</script>";
				}
			}
			else{
				alertmsg("Invaild type");
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