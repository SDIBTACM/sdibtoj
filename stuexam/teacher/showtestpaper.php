<?php
	@session_start();
	require_once("myinc.inc.php");
	checkuserid();
	if(!isset($_GET['eid']))
	{
		echo "No Such Exam";
		exit(0);
	}
	$eid=intval(trim($_GET['eid']));
	$user_id=$_SESSION['user_id'];
	$users=trim($_GET['users']);
	$users=mysql_real_escape_string($users);
	$sql ="SELECT `title`,`end_time`,`creator` FROM `exam` WHERE `exam_id`='$eid'";
	$row=fetchOne($sql);
	if(!$row)
	{
		echo "No Such Exam";
		exit(0);
	}
	$title=$row['title'];
	$end_time=$row['end_time'];
	$endtimeC=strtotime($end_time);
	$now=time();
	$creator=$row['creator'];
	$priflag=0;
	if($now>$endtimeC&&isset($_SESSION['contest_creator']))
		$priflag=1;
	if(checkAdmin(5,$creator,$priflag)){
		if($now<$endtimeC){
			echo "<h1>Exam is not Over</h1>";
			exit(0);
		}
	}
	$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
	$rowc=fetchOne($prisql);
	$creator=$rowc['creator'];
	if(checkAdmin(5,$creator,$priflag))
	{
		alertmsg("You have no privilege to see it!");
	}
	$prisql="SELECT COUNT(*) as `numc` FROM `ex_privilege` WHERE `user_id`='".$users."' AND `rightstr`='e$eid'";
	$row=fetchOne($prisql);
	$num=$row['numc'];
	if(!$num)
	{
		alertmsg("The student have no privilege to take part in it","exam_user_score.php?eid={$eid}",0);
	}
	else
	{
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
  	<link href="../css/examsys.css" rel="stylesheet" type="text/css">
	<title>程序设计考试系统</title>
</head>
<script src="../css/jquery.js" type="text/javascript"></script>
<script src="../css/bootstrap.min.js" type="text/javascript"></script>
<body>
	<div class="jumbotron">
  		<div class="container" align="center" style="color:white">
  			<h1>程序设计考试系统</h1>
  		</div>
		<div class=\"pull-right btn-start\">
		<button type="button" id="examsubmit" onclick="javascript:history.go(-1);">返回</button>
		</div>
  	</div>
	<div class="container">
		<div style="text-align:center">
			<h1><?=$title?></h1>
			<h3>考生账号:<?=$users?></h3>
		</div>
		<div style="text-align:right"><span id="saveover" name="saveover"></span></div>
		<br>
		<table class="pull-left huanhang jiacu" style="width:100%">
		<tr><td><h4>一.选择题</h4></td></tr>
		<?php
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

			$choosearr = array();
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='".$users."' AND `exam_id`='$eid' AND `type`='1'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){
				$choosearr[$row['question_id']]=$row['answer'];
			}
			mysql_free_result($result);
			$numofchoose=0;
			$query1="SELECT `ex_choose`.`choose_id`,`question`,`ams`,`bms`,`cms`,`dms`,`answer` FROM `ex_choose`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='1' AND `ex_choose`.`choose_id`=`exp_question`.`question_id` ORDER BY `choose_id`";
			$result1=mysql_query($query1) or die(mysql_error());
			while($row1=mysql_fetch_object($result1)){
				$numofchoose++;
				$question=$row1->question;
				if(isset($choosearr[$row1->choose_id]))
					$myanswer=$choosearr[$row1->choose_id];
				else
					$myanswer="";
				echo "<tr><td><pre><font color=red>($choosescore 分)</font>$numofchoose.$question</pre></td></tr>";
				echo "<tr><td><pre>(A) $row1->ams\n";
				echo "(B) $row1->bms\n";
				echo "(C) $row1->cms\n";
				echo "(D) $row1->dms</pre></td></tr>";
				echo "<tr><td><pre>";
				echo "<strong>我的答案:$myanswer\t正确答案:$row1->answer</strong>";
				echo "</pre></td></tr>";
			}
			mysql_free_result($result1);
		?>
		<tr><td><h4>二.判断题</h4></td></tr>
		<?php
			$judgearr = array();
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='".$users."' AND `exam_id`='$eid' AND `type`='2'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){
				$judgearr[$row['question_id']]=$row['answer'];
			}
			mysql_free_result($result);
			$numofjudge=0;
			$query2="SELECT `ex_judge`.`judge_id`,`question`,`answer` FROM `ex_judge`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='2' AND `ex_judge`.`judge_id`=`exp_question`.`question_id` ORDER BY `judge_id`";
			$result2=mysql_query($query2) or die(mysql_error());
			while($row2=mysql_fetch_object($result2)){
				$numofjudge++;
				echo "<tr>";
				$question=$row2->question;
				echo "<td><pre><font color=red>($judgescore 分)</font>$numofjudge.$question";
				echo "<span class=\"pull-right\">";
				if(isset($judgearr[$row2->judge_id]))
					$myanswer=$judgearr[$row2->judge_id];
				else
					$myanswer="";
				echo "<strong>我的答案:$myanswer\t正确答案:$row2->answer</strong>";
				echo "</span></pre></td>";
				echo "</tr>";
			}
			mysql_free_result($result2);
		?>
		<tr><td><h4>三.填空题</h4></td></tr>
		<?php
			$fillarr=array();
			$anstmp=array();
			$query="SELECT `question_id`,`answer_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='".$users."' AND `exam_id`='$eid' AND `type`='3'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){

				$fillarr[$row['question_id']][$row['answer_id']]=$row['answer'];
			}
			mysql_free_result($result);
			$fillnum=0;
			$query3="SELECT `ex_fill`.`fill_id`,`question`,`answernum`,`kind` FROM `ex_fill`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='3' AND `ex_fill`.`fill_id`=`exp_question`.`question_id` ORDER BY `fill_id`";
			$result3=mysql_query($query3) or die(mysql_error());
			while($row3=mysql_fetch_object($result3)){
				$fillnum++;
				echo "<tr>";
				$question=$row3->question;
				if($row3->kind==1)
					$tmpscore=$fillscore*$row3->answernum;
				else if($row3->kind==2)
					$tmpscore=$prgans;
				else 
					$tmpscore=$prgfill;
				$tmpsql="SELECT `answer_id`,`answer` FROM `fill_answer` WHERE `fill_id`='$row3->fill_id' ORDER BY `answer_id`";
				$tmpresult=mysql_query($tmpsql)  or die(mysql_error());
				echo "<td><pre><font color=red>($tmpscore 分)</font>$fillnum.$question</pre></td>";
				echo "</tr>";
				$name=$row3->fill_id."tkda";
				echo "<tr><td><pre>";
				for($i=1;$i<=$row3->answernum;$i++)
				{
					if(isset($fillarr[$row3->fill_id][$i])&&!empty($fillarr[$row3->fill_id][$i])||$fillarr[$row3->fill_id][$i]=="0")
					{
						$myanswer=$fillarr[$row3->fill_id][$i];
					}
					else
						$myanswer="";
					if($i==1)
						echo "<strong>我的答案:答案($i) $myanswer    </strong>";
					else
						echo "<strong>答案($i) $myanswer    </strong>";
				}
				while($tmprow=mysql_fetch_object($tmpresult)){
					if($tmprow->answer_id==1)
						echo "<strong>\n正确答案:答案($tmprow->answer_id) $tmprow->answer    </strong>";
					else
						echo "<strong>答案($tmprow->answer_id) $tmprow->answer    </strong>";
				}
				mysql_free_result($tmpresult);
				echo "</pre></td></tr>";
			}
			mysql_free_result($result3);
			?>
		</table>
	</div>
</body>
</html>
<?php
}
?>