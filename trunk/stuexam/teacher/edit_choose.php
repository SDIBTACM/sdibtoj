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
	if(isset($_POST['choose_des']))
	{
		require_once("../../include/check_post_key.php");
		$choose_des=test_input($_POST['choose_des']);
		$ams=test_input($_POST['ams']);
		$bms=test_input($_POST['bms']);
		$cms=test_input($_POST['cms']);
		$dms=test_input($_POST['dms']);
		$point=test_input($_POST['point']);
		$answer=$_POST['answer'];
		$easycount=intval($_POST['easycount']);
		$chooseid=intval($_POST['chooseid']);
		$prisql="SELECT `creator` FROM `ex_choose` WHERE `choose_id`='$chooseid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$creator=mysql_result($priresult, 0);
		mysql_free_result($priresult);
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "<script language='javascript'>\n";
	    	echo "alert(\"You have no privilege to modify it!\");\n";  
        	echo "location='edit_choose.php?id=$chooseid'\n";
        	echo "</script>";
		}
		else
		{
			$sql="UPDATE `ex_choose` SET `question`='".$choose_des."',`ams`='".$ams."',
			`bms`='".$bms."',`cms`='".$cms."',`dms`='".$dms."',`answer`='$answer',`point`='".$point."',`easycount`='$easycount' WHERE 
			`choose_id`='$chooseid'";
			mysql_query($sql) or die(mysql_error());
			echo "<script>alert(\"修改成功\");</script>";
			echo "<script>window.location.href=\"./admin_choose.php\";</script>";
		}
	}
	else
	{
		if(isset($_GET['id']))
		{
			$id=intval($_GET['id']);
			if(filter_var($id, FILTER_VALIDATE_INT))
			{
				$query="SELECT `question`,`ams`,`bms`,`cms`,`dms`,`answer`,`point`,`easycount` FROM `ex_choose` 
				WHERE `choose_id`='$id'";
				$result=mysql_query($query) or die(mysql_error());
				$row_cnt=mysql_num_rows($result);
				if($row_cnt)
				{
					$row=mysql_fetch_array($result);
					mysql_free_result($result);
					$question=$row['question'];
					$ams=$row['ams'];
					$bms=$row['bms'];
					$cms=$row['cms'];
					$dms=$row['dms'];
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
	}
?>
<script language="javascript">
function chkinput(form){
	if(form.choose_des.value==""){
	alert("请输入题目描述");
	form.choose_des.focus();
	return(false); 
	}
	if(form.ams.value==""){
	alert("请输入A选项!");
	form.ams.focus();
	return(false); 
	}
	if(form.bms.value==""){
	alert("请输入B选项!");
	form.bms.focus();
	return(false); 
	}
	if(form.cms.value==""){
	alert("请输入C选项!");
	form.cms.focus();
	return(false); 
	}
	if(form.dms.value==""){
	alert("请输入D选项!");
	form.dms.focus();
	return(false); 
	}
	if(form.point.value==""){
	alert("请输入知识点!");
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
	<li class="active"><a href="admin_choose.php">选择题管理</a></li>
	<li class=""><a href="admin_judge.php">判断题管理</a></li>
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
	<h2 style="text-align:center">查看修改选择题</h2>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" onSubmit="return chkinput(this)">
		<div class="pull-left span4">
			<font color=red>*如果要输入'\',请写成'\\',或使用中文引号</font>
			<label>题目描述:</label>
			<textarea style="width:250px;height:150px;overflow-x:visible;overflow-y:visible;" 
			name="choose_des"><?=$question?></textarea>
		</div>
		<?require_once("../../include/set_post_key.php");?>
		<div class="span4">
			<label>选项描述:</label>
			选项A: <input type="text" maxlength="80" name="ams" value="<?=$ams?>" ><br/>
			选项B: <input type="text" maxlength="80" name="bms" value="<?=$bms?>" ><br/>
			选项C: <input type="text" maxlength="80" name="cms" value="<?=$cms?>" ><br/>
			选项D: <input type="text" maxlength="80" name="dms" value="<?=$dms?>" ><br/>
			<strong>答案:</strong>
			A<input type="radio" name="answer" value="A" <?=($answer=='A')?"checked":""?> >&nbsp;
			B<input type="radio" name="answer" value="B" <?=($answer=='B')?"checked":""?> >&nbsp;
			C<input type="radio" name="answer" value="C" <?=($answer=='C')?"checked":""?> >&nbsp;
			D<input type="radio" name="answer" value="D" <?=($answer=='D')?"checked":""?> >&nbsp;
		</div>
		<div class="span8">
			<input type="hidden" value="<?=$id?>" name="chooseid">
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
			<!-- <input type="text" name="point" maxlength="80" value=<?=$point?> > -->
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
	require_once("./teacher-footer.php");
?>