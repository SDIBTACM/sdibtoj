<?
	require_once("./teacher-header.php");
?>
<?
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
				$ulist="";
				$sql="SELECT `user_id` FROM `ex_privilege` WHERE `rightstr`='e$eid' ORDER BY `user_id`";
				$result=mysql_query($sql) or die(mysql_error());
				for($i=mysql_num_rows($result);$i>0;$i--){
					$row=mysql_fetch_row($result);
					$ulist=$ulist.$row[0];
					if($i>1) $ulist=$ulist."\n";
				}
				mysql_free_result($result);
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
				<h2 style="text-align:center">添加考生</h2>
				<div style="height:40px">
				<ul class="nav nav-pills pull-left">
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
				<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
				<li class="active"><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
				<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
				<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
				<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li><a href=\"rejudge.php?eid=$eid\">Rejudge</a></li>";
					}
				?>
				</ul>
				</div>
				<br />
				<p><strong>考生账号:</strong></p>
				<form action="add_exam_user_ok.php" method="post">
				<textarea name="ulist" rows="19" cols="20"><?php if(isset($ulist)){ echo $ulist; }?></textarea>
				<br />
				*可以将学生学号从Excel整列复制过来，然后要求他们用学号做UserID注册,就能进入该场考试
				<input type="hidden" value="<?=$eid?>" name="eid">
				<?require_once("../../include/set_post_key.php");?>
				<p><input type="submit" value="提交" class="mybutton">
				<input type="reset" value="重置" class="mybutton"></p>
				</form>
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