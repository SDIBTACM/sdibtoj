<?
	require_once("./teacher-header.php");
?>
<?
	if(isset($_GET['student']))
	{
		$student=mysql_real_escape_string($_GET['student']);
		if($student!='')
			$sqladd="WHERE `stu`.`user_id` like '%$student%'";
		else
			$sqladd="";
	}
	else
	{
		$student="";
		$sqladd="";
	}
	if(isset($_GET['scoresx']))
	{
		$scoresx=intval($_GET['scoresx']);
		if($scoresx==1)
			$sqladd=$sqladd." ORDER BY `choosesum` DESC";
		else if($scoresx==2)
			$sqladd=$sqladd." ORDER BY `judgesum` DESC";
		else if($scoresx==3)
			$sqladd=$sqladd." ORDER BY `fillsum` DESC";
		else if($scoresx==4)
			$sqladd=$sqladd." ORDER BY `programsum` DESC";
		else if($scoresx==5)
			$sqladd=$sqladd." ORDER BY `score` DESC";
		else
		{
			$sqladd=$sqladd." ORDER BY `user_id`";
			$scoresx=0;
		}
	}
	else
	{
		$sqladd=$sqladd." ORDER BY `user_id`";
		$scoresx=0;
	}
	if(isset($_GET['eid']))
	{
		$eid=intval($_GET['eid']);
		$prisql="SELECT `creator`,`start_time`,`end_time` FROM `exam` WHERE `exam_id`='$eid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$prirow=mysql_fetch_array($priresult);
		$creator=$prirow['creator'];
		$start_time=$prirow['start_time'];
		$end_time=$prirow['end_time'];
		$start_timeC=strtotime($start_time);
		$end_timeC=strtotime($end_time);
		mysql_free_result($priresult);
		$now=time();
		$priflag=0;
		if($now>$end_timeC&&isset($_SESSION['contest_creator']))
			$priflag=1;
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']||$priflag==1))
		{
			echo "<script language='javascript'>\n";
		    echo "alert(\"You have no privilege of this exam\");\n";  
			echo "location='./'";
		    echo "</script>";
		}
		else
		{
			if(filter_var($eid, FILTER_VALIDATE_INT)&&$eid>0)
			{
				$query="SELECT `stu`.`user_id`,`stu`.`nick`,`choosesum`,`judgesum`,`fillsum`,`programsum`,`score` 
				FROM (SELECT `ex_privilege`.`user_id`,`users`.`nick` FROM `ex_privilege`,`users` WHERE `ex_privilege`.`user_id`=`users`.`user_id` AND
				 `ex_privilege`.`rightstr`=\"e$eid\" )stu left join `ex_student` on `stu`.`user_id`=`ex_student`.`user_id` AND 
				`ex_student`.`exam_id`='$eid' $sqladd";
				$result=mysql_query($query) or die(mysql_error());
		?>
				<div>
				<div class="leftmenu pull-left" id="left">
				<ul class="nav nav-pills bs-docs-sidenav">
				<li class="active"><a href="./">考试管理</a></li>
				<li><a href="admin_choose.php">选择题管理</a></li>
				<li><a href="admin_judge.php">判断题管理</a></li>
				<li><a href="admin_fill.php">填空题管理</a></li>
				<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li><a href=\"admin_point.php\">知识点管理</a></li>";
					}
					?>
				<li><a href="../">退出管理页面</a></li>
				</ul>
				</div>
				<div class="container" style="margin-left:23%" id="right">
				<h2 style="text-align:center">成绩单</h2>
				<div style="height:40px">
				<ul class="nav nav-pills pull-left">
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
				<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
				<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
				<li class="active"><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
				<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
				<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li><a href=\"rejudge.php?eid=$eid\">Rejudge</a></li>";
					}
				?>
				</ul>
				</div>
				<form class="form-search pull-left">
  				<div class="input-append">
  				<input type="hidden" name="scoresx" value="<?=$scoresx?>" >
  				<input type="hidden" name="eid" value="<?=$eid?>" />
   	 			<input type="text" class="span3 search-query" name="student" value="<?=$student?>" placeholder="查询专业或学生关键词">
    			<input type="submit" class="btn" value="Search">
    			</div>
				</form>
				<select class="pull-right" name="select1" onChange="javascript:document.location.href=this.options[this.selectedIndex].value">
				<option <?echo $scoresx==0?"selected":""?> value="exam_user_score.php?eid=<?=$eid?>&student=<?=$student?>">按账号排序</option>
				<option <?echo $scoresx==1?"selected":""?> value="exam_user_score.php?eid=<?=$eid?>&scoresx=1&student=<?=$student?>">按选择题分数排序</option>
				<option <?echo $scoresx==2?"selected":""?> value="exam_user_score.php?eid=<?=$eid?>&scoresx=2&student=<?=$student?>">按判断题分数排序</option>
				<option <?echo $scoresx==3?"selected":""?> value="exam_user_score.php?eid=<?=$eid?>&scoresx=3&student=<?=$student?>">按填空题分数排序</option>
				<option <?echo $scoresx==4?"selected":""?> value="exam_user_score.php?eid=<?=$eid?>&scoresx=4&student=<?=$student?>">按程序题分数排序</option>
				<option <?echo $scoresx==5?"selected":""?> value="exam_user_score.php?eid=<?=$eid?>&scoresx=5&student=<?=$student?>">按总分排序</option>
        		</select>
				<br />
				<table class="table table-hover table-bordered table-striped">
				<thead>
				<tr>
				<th width=5%>Rank</th>
				<th width=7%>账号</th>
				<th width=7%>姓名</th>
				<th width=7%>选择题成绩</th>
				<th width=7%>判断题成绩</th>
				<th width=7%>填空题成绩</th>
				<th width=7%>程序题成绩</th>
				<th width=7%>总成绩</th>
				<th width=7%>试卷</th>
				<th width=7%>操作</th>
				</tr>
				</thead>
				<tbody>
				<?
					$KUserId=array();
					$KNick=array();
					$KChoosesum=array();
					$KJudgesum=array();
					$KFillsum=array();
					$KProgramsum=array();
					$KScore=array();
					$rank=0;
					while($row=mysql_fetch_object($result)){
						$KUserId[$rank]=$row->user_id;
						$KNick[$rank]=$row->nick;
						$KChoosesum[$rank]=$row->choosesum;
						$KJudgesum[$rank]=$row->judgesum;
						$KFillsum[$rank]=$row->fillsum;
						$KProgramsum[$rank]=$row->programsum;
						$KScore[$rank]=$row->score;
						$rank++;
					}
					mysql_free_result($result);

					$isonline=array();
					$sql = "SELECT `user_id`,COUNT(`user_id`) as `cnum` FROM `ex_stuanswer` WHERE `exam_id`='$eid' group by `user_id`";
					$onlineresult=mysql_query($sql) or mysql_error();
					while($row=mysql_fetch_object($onlineresult))
					{
						$isonline[$row->user_id]=$row->cnum;
					}
					mysql_free_result($onlineresult);

					for($i=0;$i<$rank;$i++)
					{
						$t=$i+1;
						echo "<tr>";
						echo "<td>$t</td>";
						echo "<td>$KUserId[$i]</td>";
						echo "<td>$KNick[$i]</td>";
						echo "<td>$KChoosesum[$i]</td>";
						echo "<td>$KJudgesum[$i]</td>";
						echo "<td>$KFillsum[$i]</td>";
						echo "<td>$KProgramsum[$i]</td>";
						echo "<td>$KScore[$i]</td>";

						if($KScore[$i]=="")
						{
							if(!isset($isonline[$KUserId[$i]]))
								echo "<td><font color=green>[未参加]</font></td>";
							else
							{
								if($now>$end_timeC)
									echo "<td><font color=blue>[未交卷]</font></td>";
								else
									echo "<td><font color=red>[正在考试]</font></td>";
							}
						}
						else
							echo "<td><a href=\"showtestpaper.php?users=$KUserId[$i]&eid=$eid\">[查看试卷]</a></td>";
						if($KScore[$i]=="")
						{
							
							if(isset($isonline[$KUserId[$i]])&&$now>$end_timeC)
								echo "<td><a href=\"submit_after_exam.php?users=$KUserId[$i]&eid=$eid\">[提交试卷]</a></td>";
							else
								echo "<td>无</td>";
						}
						else
							echo "<td><a href=\"del_user_score.php?users=$KUserId[$i]&eid=$eid\" onclick=\"return suredo('是否要删除该考生成绩,让考生重新参加考试？')\">[删除分数]</a></td>";
						echo"</tr>";
					}
					unset($KUserId);
					unset($KNick);
					unset($KChoosesum);
					unset($KJudgesum);
					unset($KFillsum);
					unset($KProgramsum);
					unset($KScore);
					unset($isonline);
				?>
				</tbody>
				</table>
				</div>
				</div>
			<?
			}
			else
			{
				echo "<script language='javascript'>\n";
				echo "alert(\"Invaild data\");\n";  
				echo "history.go(-1);\n";
				echo "</script>";
			}
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
<script type="text/javascript">
function suredo(q){
	var ret;
	ret = confirm(q);
	return ret;
}
$(function(){
	if($("#left").height()<700)
		$("#left").css("height",700)
	if($("#left").height()>$("#right").height()){
		$("#right").css("height",$("#left").height())
	}
	else{
		$("#left").css("height",$("#right").height())
	}
});
</script>
<?
	require_once("./teacher-footer.php");
?>
