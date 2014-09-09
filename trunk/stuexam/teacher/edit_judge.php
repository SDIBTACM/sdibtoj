<?
	require_once("./teacher-header.php");
?>
<?
	function test_input($data){
		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		$data = mysql_real_escape_string($data);
  		return $data;
	}
	if(isset($_POST['judge_des']))
	{
		require_once("../../include/check_post_key.php");
		$judge_des=test_input($_POST['judge_des']);
		$answer=$_POST['answer'];
		$point=test_input($_POST['point']);
		$judgeid=intval($_POST['judgeid']);
		$easycount=intval($_POST['easycount']);
		$prisql="SELECT `creator` FROM `ex_judge` WHERE `judge_id`='$judgeid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$creator=mysql_result($priresult, 0);
		mysql_free_result($priresult);
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "<script language='javascript'>\n";
	    	echo "alert(\"You have no privilege to modify it!\");\n";  
        	echo "location='edit_judge.php?id=$judgeid'\n";
        	echo "</script>";
		}
		else
		{
			$sql="UPDATE `ex_judge` SET `question`='".$judge_des."',`answer`='$answer',`point`='".$point."',`easycount`='$easycount' WHERE `judge_id`='$judgeid'";
			mysql_query($sql) or die(mysql_error());
			echo "<script>alert(\"修改成功\");</script>";
			echo "<script>window.location.href=\"./admin_judge.php\";</script>";
		}
	}
	else
	{
		if(isset($_GET['id']))
		{
			$id=intval($_GET['id']);
			if(filter_var($id, FILTER_VALIDATE_INT))
			{
				$query="SELECT `question`,`answer`,`point`,`easycount` FROM `ex_judge` 
				WHERE `judge_id`='$id'";
				$result=mysql_query($query) or die(mysql_error());
				$row_cnt=mysql_num_rows($result);
				if($row_cnt)
				{
					$row=mysql_fetch_array($result);
					mysql_free_result($result);
					$question=$row['question'];
					$answer=$row['answer'];
					$point=$row['point'];
					$easycount=$row['easycount'];
				}
				else
				{
					mysql_free_result($result);
					echo "<script language='javascript'>\n";
	    			echo "alert(\"No such problem\");\n";  
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
		else
		{
			echo "<script language='javascript'>\n";
	    	echo "alert(\"Invaild path\");\n";  
        	echo "history.go(-1);\n";
        	echo "</script>";
		}
?>
<script language="javascript">
function chkinput(form){
	if(form.judge_des.value==""){
	alert("请输入题目描述");
	form.judge_des.focus();
	return(false); 
	}
	if(form.point.value==""){
	alert("请输入题目描述");
	form.point.focus();
	return(false); 
	}
	if(form.answer.value==""){
	alert("请输入答案!");
	return(false); 
	}
	return(true);
}
</script>
<div>
<div class="leftmenu pull-left" id="left">
<ul class="nav nav-pills bs-docs-sidenav">
	<li class=""><a href="./">考试管理</a></li>
	<li class=""><a href="admin_choose.php">选择题管理</a></li>
	<li class="active"><a href="admin_judge.php">判断题管理</a></li>
	<li class=""><a href="admin_fill.php">填空题管理</a></li>
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
	<h2 style="text-align:center">查看修改判断题</h2>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" onSubmit="return chkinput(this)">
		<div class="pull-left span8">
			<font color=red>*如果要输入'\',请写成'\\',或使用中文引号</font>
			<label>题目描述:</label>
			<textarea style="width:250px;height:150px;overflow-x:visible;overflow-y:visible;" 
			name="judge_des"><?=$question?></textarea>
		</div>
		<?require_once("../../include/set_post_key.php");?>
		<div class="span8">
			<strong>答案:</strong>
			对<input type="radio" name="answer" value="Y" <?=($answer=='Y')?"checked":""?> >&nbsp;
			错<input type="radio" name="answer" value="N" <?=($answer=='N')?"checked":""?> >&nbsp;
		</div>
		<div class="span8">
			<input type="hidden" value="<?=$id?>" name="judgeid">
			<label>知识点:</label>
			<select name="point" id="point">
			<?
				$sql="SELECT * FROM `ex_point`";
				$result=mysql_query($sql) or die(mysql_error());
				while($row=mysql_fetch_object($result))
				{
					if($row->point==$point)
						echo "<option value=\"$row->point\" selected>$row->point</option>";
					else
						echo "<option value=\"$row->point\">$row->point</option>";
				}
				mysql_free_result($result);
			?>
			</select>
			<label>难度系数:</label>
			<select name="easycount" id="easycount">
				<option value="10" <?echo $easycount==10?"selected":""?> >10</option>
				<option value="20" <?echo $easycount==20?"selected":""?> >20</option>
				<option value="30" <?echo $easycount==30?"selected":""?> >30</option>
				<option value="40" <?echo $easycount==40?"selected":""?> >40</option>
				<option value="50" <?echo $easycount==50?"selected":""?> >50</option>
				<option value="60" <?echo $easycount==60?"selected":""?> >60</option>
				<option value="70" <?echo $easycount==70?"selected":""?> >70</option>
				<option value="80" <?echo $easycount==80?"selected":""?> >80</option>
				<option value="90" <?echo $easycount==90?"selected":""?> >90</option>
			</select><br />
			<input type="submit" value="提交" class="mybutton">
			<input type="button" value="返回" onclick="javascript:history.go(-1);" class="mybutton">
		</div>
	</form>
</div>
</div>
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
}
	require_once("./teacher-footer.php");
?>