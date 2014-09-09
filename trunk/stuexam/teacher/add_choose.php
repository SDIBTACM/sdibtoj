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
		$easycount=intval($_POST['easycount']);
		$answer=$_POST['answer'];
		$creator=$_SESSION['user_id'];
		$sql="INSERT INTO `ex_choose` 
		(`question`,`ams`,`bms`,`cms`,`dms`,`answer`,`creator`,`addtime`,`point`,`easycount`)
		VALUES('".$choose_des."','".$ams."','".$bms."','".$cms."','".$dms."','$answer','".$creator."',NOW(),'".$point."','$easycount')";
		mysql_query($sql) or die(mysql_error());
		echo "<script>window.location.href=\"./admin_choose.php\";</script>";
	}
?>
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
	<h2 style="text-align:center">添加选择题</h2>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onSubmit="return chkinput(this)">
		<div class="pull-left span4">
			<font color=red>*如果要输入'\',请写成'\\',或使用中文引号</font>
			<label>题目描述:</label>
			<textarea style="width:250px;height:150px;overflow-x:visible;overflow-y:visible;" 
			name="choose_des"></textarea>
		</div>
		<?require_once("../../include/set_post_key.php");?>
		<div class="span4">
			<label>选项描述:</label>
			选项A: <input type="text" maxlength="80" name="ams"><br/>
			选项B: <input type="text" maxlength="80" name="bms"><br/>
			选项C: <input type="text" maxlength="80" name="cms"><br/>
			选项D: <input type="text" maxlength="80" name="dms"><br/>
			<strong>答案:</strong>
			A<input type="radio" name="answer" value="A">&nbsp;
			B<input type="radio" name="answer" value="B">&nbsp;
			C<input type="radio" name="answer" value="C">&nbsp;
			D<input type="radio" name="answer" value="D">&nbsp;
		</div>
		<div class="span8">
			<label>知识点:</label>
			<!-- <input type="text" name="point" maxlength="80"> -->
			<select name="point" id="point">
			<?
				$sql="SELECT * FROM `ex_point`";
				$result=mysql_query($sql) or die(mysql_error());
				while($row=mysql_fetch_object($result))
				{
					echo "<option value=\"$row->point\">$row->point</option>";
				}
				mysql_free_result($result);
			?>
			</select>
			<label>难度系数:</label>
			<select name="easycount" id="easycount">
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="40">40</option>
				<option value="50">50</option>
				<option value="60">60</option>
				<option value="70">70</option>
				<option value="80">80</option>
				<option value="90">90</option>
			</select><br />
			<input type="submit" value="提交" class="mybutton">
			<input type="reset" value="重置" class="mybutton">
		</div>
	</form>
</div>
</div>
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
	if(form.answer.value==""){
	alert("请输入答案!");
	return(false); 
	if(form.point.value==""){
		alert("请输入知识点");
		form.point.focus();
		return false;
	}
	}
	return(true);
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