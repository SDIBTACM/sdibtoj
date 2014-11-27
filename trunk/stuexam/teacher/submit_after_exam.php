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
		if($now<$starttimeC)
		{
			echo "<h1>Exam Not Start</h1>";
			exit(0);
		}

		$sql = "SELECT COUNT(*) FROM `ex_student` WHERE `exam_id`='$eid' AND `user_id`='$users'";
		$result = mysql_query($sql) or die(mysql_error());
		$mark = mysql_result($result,0);
		mysql_free_result($result);

		if($mark!=0)
		{
			echo "<script language='javascript'>\n";
			echo "alert(\"The student can't submit again!\");\n";
			echo "history.go(-1);";
			echo "</script>";
		}
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "<script language='javascript'>\n";
			echo "alert(\"You have no privilege to do it!\");\n";
			echo "history.go(-1);";
			echo "</script>";
		}
		else
		{
			//test paper
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

			$choosearr = array();
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='$users' AND `exam_id`='$eid' AND `type`='1'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){
				$choosearr[$row['question_id']]=$row['answer'];
			}
			mysql_free_result($result);

			$query="SELECT `choose_id`,`answer` FROM `ex_choose` WHERE `choose_id` IN
			(SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='1')";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_object($result)){
				if(isset($choosearr[$row->choose_id])){
					$myanswer=$choosearr[$row->choose_id];
					if($myanswer==$row->answer)
						$choosesum+=$choosescore;
				}
			}
			mysql_free_result($result);
			unset($choosearr);
			//choose over

			$judgearr = array();
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='$users' AND `exam_id`='$eid' AND `type`='2'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){
				$judgearr[$row['question_id']]=$row['answer'];
			}
			mysql_free_result($result);
			$query="SELECT `judge_id`,`answer` FROM `ex_judge` WHERE `judge_id` IN 
			(SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='2')";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_object($result)){
				if(isset($judgearr[$row->judge_id])){
					$myanswer=$judgearr[$row->judge_id];
					if($myanswer==$row->answer)
						$judgesum+=$judgescore;
				}
			}
			mysql_free_result($result);
			unset($judgearr);
			//judge over

			$fillarr=array();
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='$users' AND `exam_id`='$eid' AND `type`='3'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){

				$fillarr[$row['question_id']][$row['answer'][0]]=substr($row['answer'], 1);
			}
			mysql_free_result($result);

			$query="SELECT `fill_answer`.`fill_id`,`answer_id`,`answer`,`answernum`,`kind` FROM `fill_answer`,`ex_fill` WHERE  
			`fill_answer`.`fill_id`=`ex_fill`.`fill_id` AND `fill_answer`.`fill_id` IN ( SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='3')";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_object($result)){
				if(isset($fillarr[$row->fill_id][$answer_id])&&(!empty($fillarr[$row->fill_id][$answer_id])||$fillarr[$row->fill_id][$answer_id]=="0")){
					$myanswer = trim($fillarr[$row->fill_id][$answer_id]);
					//$myanswer = stripslashes($myanswer);
		  			$myanswer = htmlspecialchars($myanswer);
		  			$myanswer = mysql_real_escape_string($myanswer);
		  			
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
			mysql_free_result($result);
			unset($fillarr);
			//fillover
			
			$query="SELECT distinct `question_id`,`result` FROM `exp_question`,`solution` WHERE `exam_id`='$eid' AND `type`='4' AND `result`='4'  
			AND `in_date`>'$start_timeC' AND `in_date`<'$end_timeC' AND `user_id`='".$users."' AND `exp_question`.`question_id`=`solution`.`problem_id`";
			$result=mysql_query($query) or die(mysql_error());
			$row_cnt=mysql_num_rows($result);
			$programsum=$row_cnt*$programscore;
			mysql_free_result($result);
			//$program over
			$sum=$choosesum+$judgesum+$fillsum+$programsum;
			$sql="INSERT INTO `ex_student` VALUES('".$users."','$eid','$sum','$choosesum','$judgesum','$fillsum','$programsum')";
			mysql_query($sql) or die(mysql_error());

			//
			echo "<script language='javascript'>\n";
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

