<?
	@session_start();
	if(!isset($_SESSION['user_id']))
	{
		echo "<a href='../loginpage.php'>Please Login First!</a>";
		exit(0);
	}
	if(!isset($_GET['eid']))
	{
		echo "No Such Exam";
		exit(0);
	}
	require_once("../include/db_info.inc.php");
	$eid=intval(trim($_GET['eid']));
	$user_id=$_SESSION['user_id'];
	$sql ="SELECT `title`,`start_time`,`end_time`,`isvip` FROM `exam` WHERE `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$cnt = mysql_num_rows($result);
	if($cnt==0)
	{
		echo "No Such Exam";
		exit(0);
	}
	$row=mysql_fetch_array($result);
	$title=$row[0];
	$start_time=$row[1];
	$starttimeC=strtotime($start_time);
	$end_time=$row[2];
	$endtimeC=strtotime($end_time);
	$now=time();
	$isvip=$row[3];
	mysql_free_result($result);
	if($now<$starttimeC){
		echo "<h1>Exam Not Start</h1>";
		exit(0);
	}
	if($now>$endtimeC){
		echo "<h1>Exam Over</h1>";
		exit(0);
	}
	$prisql="SELECT COUNT(*),`randnum` FROM `ex_privilege` WHERE `user_id`='".$user_id."' AND `rightstr`='e$eid'";
	$priresult=mysql_query($prisql) or die(mysql_error());
	$row=mysql_fetch_array($priresult);
	$num=$row[0];
	$randnum=$row[1];
	mysql_free_result($priresult);
	if(!(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||$num))
	{
		echo "<script language='javascript'>\n";
	    echo "alert(\"You have no privilege\");\n";  
        echo "location='./'\n";
        echo "</script>";
	}
	if(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator']))
	{
		$randnum=0;
	}
	$sql="SELECT `user_id` FROM `ex_student` WHERE `user_id`='".$user_id."' AND `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$row_cnt=mysql_num_rows($result);
	mysql_free_result($result);
	if($row_cnt)
	{
		echo "<script language='javascript'>\n";
	    echo "alert(\"You have taken part in it\");\n";  
        echo "location='./'\n";
        echo "</script>";
	}
	if($isvip=='Y')
    {           
		$today=date('Y-m-d');
		$ip1=$_SERVER['REMOTE_ADDR'];
		$sql="SELECT * FROM `loginlog` WHERE `user_id`='$user_id' AND `time`>='$today' AND ip<>'$ip1' AND 
		 `user_id` NOT IN( SELECT `user_id` FROM `privilege` WHERE `rightstr`='administrator' or `rightstr`='contest_creator') ORDER BY `time` DESC limit 0,1";
		$result=mysql_query($sql) or die(mysql_error());
		$row_cnt=mysql_num_rows($result);
		if($row_cnt>0)
		{  
			$row=mysql_fetch_row($result);
			mysql_free_result($result);
			echo "<script language='javascript'>\n";
			echo "alert('Do not login in diff machine,Please Contact administrator');\n";  
			echo "history.go(-1);\n";
			echo "</script>";
			exit(0);
		}
		mysql_free_result($result);
	}
	$lefttime=$endtimeC-time();
?>
<?
	function array_remove(&$arr,$offset)
	{
		array_splice($arr, $offset,1);
	}

	function makesx($myarr,$num,$flag,$stunum)
	{
		$fac=array(1,1,2,6,24,120,720,5040,40320,362880,3628800,39916800);
		$rightnum=$stunum%$fac[$num];
		//$stunum>=1 and $stunum<=39916800
		//$stunum-1>=0 and $stunum-1<=$fac[$num]-1;
		//$rightsum>=0 and $rightsum<=$fac[$num]-1;
		$tmp=array();
		for($i=1;$i<=$num;$i++)
			$tmp[]=$i;
		$ans=array();
		if($flag==1)
			$ans[]="choose_id";
		else if($flag==2)
			$ans[]="judge_id";
		else if($flag==3)
			$ans[]="fill_id";

		for($i=1;$i<=$num;$i++)
		{
			$div=floor($rightnum/$fac[$num-$i]);
			$rightnum=$rightnum%$fac[$num-$i];
			$ans[]=$myarr[$tmp[$div]-1];
			array_remove($tmp,$div);
		}
		for($i=1;$i<=floor($num/2);$i++)
		{
			$t=$ans[$i];
			$ans[$i]=$ans[$num-$i+1];
			$ans[$num-$i+1]=$t;
		}
		unset($tmp);unset($fac);
		$tmp=join(",",$ans);
		unset($ans);
		return $tmp;
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  	<link href="css/examsys.css" rel="stylesheet" type="text/css">
	<title>程序设计考试系统</title>
</head>
<script src="css/jquery.js" type="text/javascript"></script>
<script src="css/bootstrap.min.js" type="text/javascript"></script>
<script src="../include/checksource.js"></script>
<script src="css/questionadd.js"></script>
<script type="text/javascript">
// var EndTime=<?=$endtimeC*1000?>;
// var dTime = new Date("<?=date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();//计算出服务器和客户端的时间差
var isalert = false;
var left=<?=$lefttime?>*1000;
var savetime=(300+<?=$randnum?>%300)*1000;
var runtimes=0;

function GetRTime(){
	nMS=left-runtimes*1000;
	// var NowTime = new Date(new Date().getTime()+dTime);
	//var nMS=EndTime-NowTime;
	if(nMS>0){
		var nH=Math.floor(nMS/(1000*60*60)) % 24; 
		var nM=Math.floor(nMS/(1000*60)) % 60;
		var nS=Math.floor(nMS/1000) % 60;
		document.getElementById("RemainH").innerHTML=(nH>=10?nH:"0"+nH);
		document.getElementById("RemainM").innerHTML=(nM>=10?nM:"0"+nM);
		document.getElementById("RemainS").innerHTML=(nS>=10?nS:"0"+nS);
		if(nMS<=5*60*1000&&isalert==false)
		{
			$('.tixinga').css("color","red");
			$('.tixingb').css("color","red");
			isalert=true;
		}
		if(nMS>0&&nMS<=1*1000)
		{
			$('#exam').submit();
		}
		//如果出现问题，注释掉下面的if语句
		if(nMS%savetime==0&&nMS>savetime)
		{
			saveanswer();
			setTimeout('history.go(0)',5000);
		}
		runtimes++;
		setTimeout("GetRTime()",1000);
	}
}
window.onload=GetRTime;
</script>

<style>
	.nocopy{
		-moz-user-select:none;
	}
	input[type="radio"]{
		margin-top: 0px;
	}
	.jiacu pre{
  		font-size: 16px;
	}
</style>
<body onselectstart="return false" class="nocopy">
	<div class="jumbotron">
  		<div class="container" align="center" style="color:white">
  			<h1>程序设计考试系统</h1>
  			<ul>
  			<li class='tixinga'><h4>距考试结束还有:</h4></li>
  			<li class="tixingb"><h4><strong id="RemainH">XX</strong>:<strong id="RemainM">XX</strong>:<strong id="RemainS">XX</strong>
  			</h4></li>
  			<li class='tixing pull-left'><button type="button" id="saveanswer" onclick="saveanswer()">保存答案</button></li>
  		</ul>
  		</div>
  	</div>
	<div class="container">
		<div style="text-align:center">
			<h1><?=$title?></h1>
		</div>
		<div style="text-align:right"><span id="saveover" name="saveover"></span></div>
		<br>
		<form name="exam" id="exam" action="checkanswer.php" method="post">
		<input type="hidden" name="eid" value="<?=$eid?>">
		<div class=\"pull-right btn-start\">
		<button type="submit" id="examsubmit" onclick="return suredo('确定交卷?')">交卷</button>
		</div>
		<table class="pull-left huanhang jiacu" style="width:100%">
		<tr><td><h4>一.选择题</h4></td></tr>
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

			$choosearr = array();
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='$user_id' AND `exam_id`='$eid' AND `type`='1'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){
				$choosearr[$row['question_id']]=$row['answer'];
			}
			mysql_free_result($result);

			//start to shuffle the data
			$choosesx=array();
			$query="SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='1' ORDER BY `question_id`";
			$result=mysql_query($query) or die(mysql_error());
			$numproblem=mysql_num_rows($result);
			while($row=mysql_fetch_array($result)){
				$choosesx[]=$row['question_id'];
			}
			mysql_free_result($result);
			if($numproblem!=0)
				$tmpchoose=makesx($choosesx,$numproblem,1,$randnum);
			else
				$tmpchoose="";
			unset($choosesx);
			//the end

			$numofchoose=0;
			/*$query1="SELECT `ex_choose`.`choose_id`,`question`,`ams`,`bms`,`cms`,`dms` FROM `ex_choose`,`exp_choose` 
			WHERE `exam_id`='$eid' and `ex_choose`.`choose_id`=`exp_choose`.`choose_id` ORDER BY `choose_id`";*/
			
			if(isset($tmpchoose[0]))
			{
				$query1="SELECT `ex_choose`.`choose_id`,`question`,`ams`,`bms`,`cms`,`dms` FROM `ex_choose`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='1' AND `ex_choose`.`choose_id`=`exp_question`.`question_id` ORDER BY field($tmpchoose)";
				$result1=mysql_query($query1) or die(mysql_error());
			}
			else
			{
				$query1="SELECT `ex_choose`.`choose_id`,`question`,`ams`,`bms`,`cms`,`dms` FROM `ex_choose`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='1' AND `ex_choose`.`choose_id`=`exp_question`.`question_id`";
				$result1=mysql_query($query1) or die(mysql_error());
			}
			while($row1=mysql_fetch_object($result1)){
				$numofchoose++;
				$question=$row1->question;
				if(isset($choosearr[$row1->choose_id]))
					$myanswer=$choosearr[$row1->choose_id];
				else
					$myanswer="";
				echo "<tr><td><pre><font color=red>($choosescore 分)</font>$numofchoose.$question</pre></td></tr>";
				echo "<tr><td><pre>";
				if($myanswer=='A')
					echo "<input class=\"xzda\" type=\"radio\" name=\"xzda$row1->choose_id\" value=\"A\" checked>";
				else
					echo "<input class=\"xzda\" type=\"radio\" name=\"xzda$row1->choose_id\" value=\"A\">";
				echo "(A) $row1->ams\n";
				if($myanswer=='B')
					echo "<input class=\"xzda\" type=\"radio\" name=\"xzda$row1->choose_id\" value=\"B\" checked>";
				else
					echo "<input class=\"xzda\" type=\"radio\" name=\"xzda$row1->choose_id\" value=\"B\">";
				echo "(B) $row1->bms\n";
				if($myanswer=='C')
					echo "<input class=\"xzda\" type=\"radio\" name=\"xzda$row1->choose_id\" value=\"C\" checked>";
				else
					echo "<input class=\"xzda\" type=\"radio\" name=\"xzda$row1->choose_id\" value=\"C\">";
				echo "(C) $row1->cms\n";
				if($myanswer=='D')
					echo "<input class=\"xzda\" type=\"radio\" name=\"xzda$row1->choose_id\" value=\"D\" checked>";
				else
					echo "<input class=\"xzda\" type=\"radio\" name=\"xzda$row1->choose_id\" value=\"D\">";
				echo "(D) $row1->dms</pre></td></tr>";
				echo "<tr><td><pre>";
				echo "</pre></td></tr>";
			}
			mysql_free_result($result1);
			unset($choosearr);
		?>
		<tr><td><h4>二.判断题</h4></td></tr>
		<?
			$judgearr = array();
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='$user_id' AND `exam_id`='$eid' AND `type`='2'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){
				$judgearr[$row['question_id']]=$row['answer'];
			}
			mysql_free_result($result);

			$judgesx=array();
			$query="SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='2' ORDER BY `question_id`";
			$result=mysql_query($query) or die(mysql_error());
			$numproblem=mysql_num_rows($result);
			while($row=mysql_fetch_array($result)){
				$judgesx[]=$row['question_id'];
			}
			mysql_free_result($result);
			if($numproblem!=0)
				$tmpjudge=makesx($judgesx,$numproblem,2,$randnum);
			else
				$tmpjudge="";
			unset($judgesx);

			$numofjudge=0;

			if(isset($tmpjudge[0]))
			{
				$query2="SELECT `ex_judge`.`judge_id`,`question` FROM `ex_judge`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='2' AND `ex_judge`.`judge_id`=`exp_question`.`question_id` ORDER BY field($tmpjudge)";
				$result2=mysql_query($query2) or die(mysql_error());
			}
			else
			{
				$query2="SELECT `ex_judge`.`judge_id`,`question` FROM `ex_judge`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='2' AND `ex_judge`.`judge_id`=`exp_question`.`question_id`";
				$result2=mysql_query($query2) or die(mysql_error());
			}
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
				if($myanswer=='Y')
					echo "Ture.<input class=\"pdda\" type=\"radio\" name=\"pdda$row2->judge_id\" value=\"Y\" checked>\t";
				else
					echo "Ture.<input class=\"pdda\" type=\"radio\" name=\"pdda$row2->judge_id\" value=\"Y\">\t";
				if($myanswer=='N')
					echo "False.<input class=\"pdda\" type=\"radio\" name=\"pdda$row2->judge_id\" value=\"N\" checked>\t</span>";
				else
					echo "False.<input class=\"pdda\" type=\"radio\" name=\"pdda$row2->judge_id\" value=\"N\">\t</span>";
				echo "</pre></td>";
				echo "</tr>";
			}
			mysql_free_result($result2);
			unset($judgearr);
		?>
		<tr><td><h4>三.填空题</h4></td></tr>
		<?
			$fillarr=array();
			$query="SELECT `question_id`,`answer` FROM `ex_stuanswer` WHERE `user_id`='$user_id' AND `exam_id`='$eid' AND `type`='3'";
			$result=mysql_query($query) or die(mysql_error());
			while($row=mysql_fetch_assoc($result)){

				$fillarr[$row['question_id']][$row['answer'][0]]=substr($row['answer'], 1);
			}
			mysql_free_result($result);

			$fillsx=array();
			$query="SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='3' ORDER BY `question_id`";
			$result=mysql_query($query) or die(mysql_error());
			$numproblem=mysql_num_rows($result);
			while($row=mysql_fetch_array($result)){
				$fillsx[]=$row['question_id'];
			}
			mysql_free_result($result);
			if($numproblem!=0)
				$tmpfill=makesx($fillsx,$numproblem,3,$randnum);
			else
				$tmpfill="";
			unset($fillsx);

			$fillnum=0;
			/*$query3="SELECT `ex_fill`.`fill_id`,`question`,`answernum` FROM `ex_fill`,`exp_fill` 
			WHERE `exam_id`='$eid' and `ex_fill`.`fill_id`=`exp_fill`.`fill_id` ORDER BY `fill_id`";*/
			
			if(isset($tmpfill[0])){
				$query3="SELECT `ex_fill`.`fill_id`,`question`,`answernum`,`kind` FROM `ex_fill`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='3' AND `ex_fill`.`fill_id`=`exp_question`.`question_id` ORDER BY field($tmpfill)";
				$result3=mysql_query($query3) or die(mysql_error());
			}
			else
			{
				$query3="SELECT `ex_fill`.`fill_id`,`question`,`answernum`,`kind` FROM `ex_fill`,`exp_question` 
			WHERE `exam_id`='$eid' AND `type`='3' AND `ex_fill`.`fill_id`=`exp_question`.`question_id`";
				$result3=mysql_query($query3) or die(mysql_error());
			}
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
				echo "<td><pre><font color=red>($tmpscore 分)</font>$fillnum.$question</pre></td>";
				echo "</tr>";
				$name=$row3->fill_id."tkda";
				echo "<tr><td><pre>";
				for($i=1;$i<=$row3->answernum;$i++)
				{
					if(isset($fillarr[$row3->fill_id][$i])&&(!empty($fillarr[$row3->fill_id][$i])||$fillarr[$row3->fill_id][$i]=="0"))
						$myanswer=$fillarr[$row3->fill_id][$i];
					else
						$myanswer="";
					echo "答案$i.<input type=\"text\" maxlength=\"100\" name=\"$name$i\" value=\"$myanswer\"><br/>";
				}
				echo "</pre></td></tr>";
			}
			mysql_free_result($result3);
			unset($fillarr);
			?>
			</form>
			<tr><td><h4>四.程序设计题</h4></td></tr>
			<?
				$numofprogram=0;
				/*$query4="SELECT `program_id`,`title`,`description`,`input`,`output`,`sample_input`,`sample_output` FROM `exp_program`,`problem` 
				WHERE `exam_id`='$eid' AND `program_id`=`problem_id`";*/
				$query4="SELECT `question_id` as `program_id`,`title`,`description`,`input`,`output`,`sample_input`,`sample_output` FROM `exp_question`,`problem` 
				WHERE `exam_id`='$eid' AND `type`='4' AND `question_id`=`problem_id`";
				$result4=mysql_query($query4) or die(mysql_error());
				while($row4=mysql_fetch_object($result4)){
					$numofprogram++;
					echo "<tr>";
					echo "<td><pre><font color=red>($programscore 分)</font><span id=\"whethershow\">$numofprogram.</span><strong>$row4->title</strong></pre></td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td><pre><h4>Description</h4>$row4->description<h4>Input</h4>$row4->input<h4>Output</h4>
					$row4->output<h4>Sample Input</h4>$row4->sample_input<h4>sample_output</h4>$row4->sample_output</pre></td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td><pre><label for=\"code$row4->program_id\">Code here:</label>";
					echo "<textarea style=\"width:900px;height:480px\" id=\"code$row4->program_id\" name=\"code$row4->program_id\"></textarea></pre>";
					echo "<select id=\"language$row4->program_id\" class='span3'>
						  <option value=\"0\">C</option>
						  <option value=\"1\" selected>C++</option>
						  <option value=\"2\">Pascal</option>		
						  <option value=\"3\">Java</option>
						  </select>\t";
					echo "<span class='span3' id='span$row4->program_id'><font color=green size=3px>未提交</font></span>";
					echo "<span class='span3'><a href=\"javascript:void(0);\" onclick=\"updateresult('span$row4->program_id','$row4->program_id','$eid')\">[点击刷新结果]</a></span>";
					echo "<input type=\"button\" class=\"btn btn-success pull-right span2\" value=\"提交\"
					onclick=\"submitcode('span$row4->program_id','code$row4->program_id','language$row4->program_id','$row4->program_id','$eid')\">";
					echo "</td></tr>";
				}
			?>
		</table>
	</div>
</body>
<script type="text/javascript">
function suredo(q){
	var ret;
	ret = confirm(q);
	return ret;
}
$(function(){
	var x=<?=$numofprogram?>;
	if(x==1)
		$('#whethershow').html("");
});
</script>
<script language="javascript">
$("body").keydown(function(event){
	if(event.keyCode==116){
		event.returnValue=false;
		alert("当前设置不允许使用F5刷新键");
		return false;
	  }
});
function submitcode(spanid,codeid,languageid,pid,examid)
{
	var flag=mychecksource($('#'+codeid).val(),$('#'+languageid).val());
	if(flag)
	{
		$.ajax({
			url:'programsubmit.php',
			data:'language='+$('#'+languageid).val()+'&id='+pid+'&eid='+examid+'&source='+encodeURIComponent($('#'+codeid).val()),
			type:'POST',
			success:function(data){
				$('#'+spanid).html(data);
			},
			error:function(){
				alert('sorry,something error');
			}
		});
	}
}
function saveanswer()
{
	$.ajax({
		url:'answersave.php',
		data:$('#exam').serialize(),
		type:'POST',
		success:function(data){
			 if(data=="True")
			 	$('#saveover').html("[已保存]");
			 else
				$('#saveover').html(data);
		},
		error:function(){
			alert('sorry,something error');
		}
	});
}
</script>
</html>
