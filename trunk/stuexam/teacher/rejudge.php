<?
	require_once("./teacher-header.php");
?>
<?
	if(isset($_GET['eid']))
	{
		$eid=intval($_GET['eid']);
		if(!isset($_SESSION['administrator']))
		{
			echo "<script language='javascript'>\n";
		    echo "alert(\"You have no privilege of rejudging\");\n";  
			echo "location='./'";
		    echo "</script>";
		}
		else
		{
			if(filter_var($eid, FILTER_VALIDATE_INT)&&$eid>0)
			{
				
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
				<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
				<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
				<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
				<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li class=\"active\"><a href=\"rejudge.php?eid=$eid\">Rejudge</a></li>";
					}
				?>
				</ul>
				</div>
				<br />
				
				<ol>
					<li>学生账号:
					<form action='rejudge.php' method=post>
						<input type=text name='rjuserid' style="margin-bottom:0px">	
						<input type='hidden' name='do' value='do'>
						<input type='hidden' name='eid' value='<?=$eid?>'>
						<input type=submit value=submit class="btn btn-success">
      					<?require_once("../../include/set_post_key.php");?>
      				</form>
					</li>
					<li>对该场考试全部重判,请谨慎选择！
						<form action='rejudge.php' method=post>
						<input type='hidden' name='do' value='do'>
						<input type='hidden' name='rjall' value='1'>
						<input type='hidden' name='eid' value='<?=$eid?>'>
           				<input type=hidden name="postkey" value="<?=$_SESSION['postkey']?>">
						<input type=submit value=submit class="btn btn-warning" onclick="return suredo('确定全部重判?')">
						</form>
					</li>
				</ol>
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
	else if(isset($_POST['do']))
	{
    	require_once("../../include/check_post_key.php");
		if (isset($_POST['rjuserid'])&&isset($_POST['eid']))
		{
			$rjuserid=trim($_POST['rjuserid']);
			$rjuserid=mysql_real_escape_string($rjuserid);
			$eid=intval($_POST['eid']);
			$sql="SELECT COUNT(*) FROM `ex_privilege` WHERE `user_id`='".$rjuserid."' AND `rightstr`='e$eid'";
			$result=mysql_query($sql) or die(mysql_error());
			$cnt1=mysql_result($result, 0);
			mysql_free_result($result);
			if($cnt1==0)
			{
				echo "<script language='javascript'>\n";
				echo "alert(\"Student ID is wrong!\");\n";
				echo "location.href='./rejudge.php?eid=$eid'";
				echo "</script>";
			}
			else
			{
				echo "<h3>...正在重判 $rjuserid 的试卷...</h3>";
				$url="./submit_after_exam.php?eid=$eid&users=$rjuserid&rjd=1";
				echo "<script>location.href='$url';</script>";
			}
		}
		else if(isset($_POST['rjall']))
		{
			require_once("../func.php");
			$rjall=intval($_POST['rjall']);
			$eid=intval($_POST['eid']);

			$prisql="SELECT `start_time`,`end_time` FROM `exam` WHERE `exam_id`='$eid'";
			$priresult=mysql_query($prisql) or die(mysql_error());
			$prirow=mysql_fetch_array($priresult);
			$start_time=$prirow['start_time'];
			$end_time=$prirow['end_time'];
			$start_timeC=strtotime($start_time);
			$end_timeC=strtotime($end_time);
			mysql_free_result($priresult);

			$userlist=array();
			$numuser=0;
			$sql="SELECT `user_id` FROM `ex_student` WHERE `exam_id`='$eid'";
			$result=mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_object($result))
			{
				$userlist[$numuser]=$row->user_id;
				$numuser++;
			}
			for($i=0;$i<$numuser;$i++)
			{
				rejudgepaper($userlist[$i],$eid,$start_timeC,$end_timeC,1);
				echo "<h4>...正在重判 $userlist[$i] 的试卷...</h4>";
			}
			echo "<script language='javascript'>\n";
        	echo "location.href='./exam_user_score.php?eid=$eid'";
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