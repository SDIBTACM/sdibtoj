<?
	require_once("./teacher-header.php");
?>
<?
	function test_input($data){
		$data = trim($data);
  		$data = stripslashes($data);
  		$data = mysql_real_escape_string($data);
  		return $data;
	}
	if(isset($_POST['eid']))
	{
		require_once("../../include/check_post_key.php");
		$questionnum=count($_POST)-2;
		$eid=intval($_POST['eid']);
		//the first is the postkey the second is the hidden eid
		
		//$sql="DELETE FROM `exp_program` WHERE `exam_id`='$eid'";
		$sql="DELETE FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='4'";
		mysql_query($sql) or die(mysql_error());
		for($i=1;$i<=$questionnum;$i++)
		{
			$programid=test_input($_POST["question$i"]);
			if(!is_numeric($programid))
			{
				echo "<script>alert(\"题号有错误\");";
				echo "history.go(-1);";
				echo "</script>";
			}
			else
			{
				$programid=intval($programid);
				//$query="INSERT INTO `exp_program` VALUES('$eid','$programid')";
				$query="INSERT INTO `exp_question` VALUES('4','$eid','$programid')";
				mysql_query($query) or die(mysql_error());
				$query="UPDATE `problem` SET `defunct`='Y' WHERE `problem_id`='$programid'";
				mysql_query($query) or die(mysql_error());
			}
		}
		echo "<script>window.location.href=\"add_program.php?eid=$eid&type=4\";</script>";
	}
	else
	{
		if(isset($_GET['type'])&&isset($_GET['eid']))
		{
			$type=intval($_GET['type']);
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
				if(filter_var($type, FILTER_VALIDATE_INT)&&filter_var($eid, FILTER_VALIDATE_INT)&&$eid>0)
				{
					if($type==4)//add program
					{
						//$sql="SELECT COUNT(*) FROM `exp_program` WHERE `exam_id`='$eid'";
						$sql="SELECT COUNT(*) FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='4'";
						$result=mysql_query($sql) or die(mysql_error());
						$answernumC=mysql_result($result, 0);
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
						<h2 style="text-align:center">添加程序题</h2>
						<div style="height:40px">
						<ul class="nav nav-pills pull-left">
						<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
						<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
				  		<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
						<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
						<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
						<li class="active"><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
						<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
						<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
						<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
						</ul>
						</div>
						<br>
						<font color=red>*这里的程序题号与oj中的problem相连,请正确填写您要添加的题目所对应的Problem ID</font>
						<div class="span8">
						<input class="btn btn-success" type="button" value="添加" onclick="addinput()" />
						<input class="btn btn-warning" type="button" value="删除" onclick="delinput()" />
						</div>
						<div class="span8">
						<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" onSubmit="return chkinput(this)">
							<p><strong>题号:</strong></p>
							<div class="span4" id="Content">
							<?
								if($answernumC)
								{
									//$query="SELECT `program_id` FROM `exp_program` WHERE `exam_id`='$eid' ORDER BY `program_id`";
									$query="SELECT `question_id` as `program_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='4' ORDER BY `question_id`";
									$result=mysql_query($query) or die(mysql_error());
									$cnt=0;
									while($myrow=mysql_fetch_object($result))
									{
										$cnt++;
										echo "<input type=\"text\" maxlength=\"100\" name=\"question$cnt\" id=\"question$cnt\" value=\"$myrow->program_id\">";
									}
									mysql_free_result($result);
								}
							?>
							</div>
							<div class="span8">
							<?require_once("../../include/set_post_key.php");?>
							<input type="hidden" value="<?=$eid?>" name="eid">
							<input type="submit" value="提交" class="mybutton">
							<input type="reset" value="重置" class="mybutton">
							</div>
						</form>
						</div>
						</div>
						<?
					}
					else
					{
						echo "<script language='javascript'>\n";
			    		echo "alert(\"Invaild type\");\n";  
		        		echo "history.go(-1);\n";
		        		echo "</script>";
					}
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
	}
?>
<script type="text/javascript">
function chkinput(form)
{
	for(var i=0;i<form.length;i++)
	{
		if(form.elements[i].value=="")
		{
			alert("信息不能为空");
			form.elements[i].focus();
			return false;
		}
	}
	return(true);
}

var cont=document.getElementById("Content");
var num_question=<?=$answernumC?>;
function addinput()
{
	if(num_question<8)
	{
		num_question++;
		var Newinput=document.createElement("input");
		Newinput.setAttribute("type","text");
		Newinput.setAttribute("maxlength","100");
		Newinput.setAttribute("name","question"+num_question);
		Newinput.setAttribute("id","question"+num_question);
		cont.appendChild(Newinput);
	}
	else
		alert("空数已达到上限");
}
function delinput()
{
	if(num_question>0)
	{
		var index=cont.getElementsByTagName("input").length;
		var node = document.getElementById("question"+index);
		node.parentNode.removeChild(node);
		num_question--;
	}
	else
	{
		alert("Nothing to be deleted");
	}
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