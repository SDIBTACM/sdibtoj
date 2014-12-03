<?
function rejudgepaper($users,$eid,$start_timeC,$end_timeC,$mark)
{
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
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `exam_id`='$eid' AND `type`='1' AND `user_id`='".$users."'";
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
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `exam_id`='$eid' AND `type`='2' AND `user_id`='".$users."'";
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
			$query="SELECT `question_id`,`answer_id`,`answer` FROM `ex_stuanswer` WHERE `exam_id`='$eid' AND `type`='3' AND `user_id`='".$users."'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){

				$fillarr[$row['question_id']][$row['answer_id']]=$row['answer'];
			}
			mysql_free_result($result);

			$query="SELECT `fill_answer`.`fill_id`,`answer_id`,`answer`,`answernum`,`kind` FROM `fill_answer`,`ex_fill` WHERE  
			`fill_answer`.`fill_id`=`ex_fill`.`fill_id` AND `fill_answer`.`fill_id` IN ( SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='3')";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_object($result)){
				if(isset($fillarr[$row->fill_id][$row->answer_id])&&(!empty($fillarr[$row->fill_id][$row->answer_id])||$fillarr[$row->fill_id][$row->answer_id]=="0")){
					$myanswer = trim($fillarr[$row->fill_id][$row->answer_id]);
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
			if($mark==0)// if the student has not submitted the paper
				$sql="INSERT INTO `ex_student` VALUES('".$users."','$eid','$sum','$choosesum','$judgesum','$fillsum','$programsum')";
			else
				$sql="UPDATE `ex_student` SET `score`='$sum',`choosesum`='$choosesum',`judgesum`='$judgesum',`fillsum`='$fillsum',`programsum`='$programsum'
				WHERE `user_id`='".$users."' AND `exam_id`='$eid'";
			mysql_query($sql) or die(mysql_error());
}
?>