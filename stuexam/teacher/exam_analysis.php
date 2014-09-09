<?
	require_once("./teacher-header.php");
?>
<?
	if(isset($_GET['student']))
	{
		$student=mysql_real_escape_string($_GET['student']);
		if($student!='')
			$sqladd=" AND `user_id` like '%$student%'";
		else
			$sqladd="";
	}
	else
	{
		$student="";
		$sqladd="";
	}
	if(isset($_GET['eid']))
	{
		$eid=intval($_GET['eid']);
		$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$creator=mysql_result($priresult, 0);
		mysql_free_result($priresult);
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
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
				$query="SELECT COUNT(*) FROM `ex_privilege` WHERE `rightstr`='e$eid'";
				$result=mysql_query($query) or die(mysql_error());
				$totalnum=mysql_result($result, 0);
				mysql_free_result($result);

				$query="SELECT COUNT(*) as `realnum`,MAX(`choosesum`) as `choosemax`,MAX(`judgesum`) as `judgemax`,MAX(`fillsum`) as `fillmax`,
				MAX(`programsum`) as `programmax`,MIN(`choosesum`) as `choosemin`,MIN(`judgesum`) as `judgemin`,MIN(`fillsum`) as `fillmin`,
				MIN(`programsum`) as `programmin`,MAX(`score`) as `scoremax`,MIN(`score`) as `scoremin`,AVG(`choosesum`) as `chooseavg`,
				AVG(`judgesum`) as `judgeavg`,AVG(`fillsum`) as `fillavg`,AVG(`programsum`) as `programavg`,
				AVG(`score`) as `scoreavg` FROM `ex_student` WHERE `exam_id`='$eid' $sqladd";
				$result=mysql_query($query) or die(mysql_error());
				$row=mysql_fetch_array($result);
				
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
				<h2 style="text-align:center">考试分析</h2>
				<div style="height:40px">
				<ul class="nav nav-pills pull-left">
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
				<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
				<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
				<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
				<li class="active"><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
				</ul>
				</div>
				<br />
				<p>考试编号:       	<?=$eid?></p>
				<p>应考人数:		  	<?=$totalnum?></p>
				<p>实考人数:			<?=$row['realnum']?></p>
				<h3 class="pull-left">各部分得分</h3>
				<form class="form-search pull-right">
  				<div class="input-append">
  				<input type="hidden" name="eid" value="<?=$eid?>" />
   	 			<input type="text" class="span3 search-query" name="student" value="<?=$student?>" placeholder="查询专业或学生关键词如jk">
    			<input type="submit" class="btn" value="Search">
    			</div>
				</form>
				<table class="table-bordered table table-hover table-bordered table-striped">
					<tr>
						<td>题型</td>
						<td>选择题</td>
						<td>判断题</td>
						<td>填空题</td>
						<td>程序设计题</td>
						<td>总分</td>
					</tr>
					<tr>
						<td>最高分</td>
						<td><?=$row['choosemax']?></td>
						<td><?=$row['judgemax']?></td>
						<td><?=$row['fillmax']?></td>
						<td><?=$row['programmax']?></td>
						<td><?=$row['scoremax']?></td>
					</tr>
					<tr>
						<td>最低分</td>
						<td><?=$row['choosemin']?></td>
						<td><?=$row['judgemin']?></td>
						<td><?=$row['fillmin']?></td>
						<td><?=$row['programmin']?></td>
						<td><?=$row['scoremin']?></td>
					</tr>
					<tr>
						<td>平均分</td>
						<td><?=$row['chooseavg']?></td>
						<td><?=$row['judgeavg']?></td>
						<td><?=$row['fillavg']?></td>
						<td><?=$row['programavg']?></td>
						<td><?=$row['scoreavg']?></td>
					</tr>
				</table>
				<?
					mysql_free_result($result);
					$sql="SELECT COUNT(*) FROM `ex_student` WHERE `score`<60 and `exam_id`='$eid'";
					$result=mysql_query($sql) or die(mysql_error());
					$fd1=mysql_result($result, 0);
					mysql_free_result($result);

					$sql="SELECT COUNT(*) FROM `ex_student` WHERE `score`>=60 and `score`<70 and `exam_id`='$eid'";
					$result=mysql_query($sql) or die(mysql_error());
					$fd2=mysql_result($result, 0);
					mysql_free_result($result);

					$sql="SELECT COUNT(*) FROM `ex_student` WHERE `score`>=70 and `score`<80 and `exam_id`='$eid'";
					$result=mysql_query($sql) or die(mysql_error());
					$fd3=mysql_result($result, 0);
					mysql_free_result($result);

					$sql="SELECT COUNT(*) FROM `ex_student` WHERE `score`>=80 and `score`<90 and `exam_id`='$eid'";
					$result=mysql_query($sql) or die(mysql_error());
					$fd4=mysql_result($result, 0);
					mysql_free_result($result);

					$sql="SELECT COUNT(*) FROM `ex_student` WHERE `score`>=90 and `exam_id`='$eid'";
					$result=mysql_query($sql) or die(mysql_error());
					$fd5=mysql_result($result, 0);
					mysql_free_result($result);
				?>
				<h3>各分数段人数</h3>
				<table class="table-bordered table table-hover table-bordered table-striped">
					<tr>
						<td>分数段</td>
						<td>60以下</td>
						<td>60~69</td>
						<td>70~79</td>
						<td>80~89</td>
						<td>90及以上</td>
					</tr>
					<tr>
						<td>人数</td>
						<td><?=$fd1?></td>
						<td><?=$fd2?></td>
						<td><?=$fd3?></td>
						<td><?=$fd4?></td>
						<td><?=$fd5?></td>
					</tr>
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
