<?php
	require_once("./teacher-header.php");
?>
<?php
	if(isset($_POST['choose_des']))
	{
		require_once("../../include/check_post_key.php");
		$arr['question']=test_input($_POST['choose_des']);
		$arr['ams']=test_input($_POST['ams']);
		$arr['bms']=test_input($_POST['bms']);
		$arr['cms']=test_input($_POST['cms']);
		$arr['dms']=test_input($_POST['dms']);
		$arr['answer']=$_POST['answer'];
		$arr['creator']=$_SESSION['user_id'];
		$arr['point']=test_input($_POST['point']);
		$arr['addtime']=date('Y-m-d H:m:i');
		$arr['easycount']=intval($_POST['easycount']);
		$arr['isprivate']=intval($_POST['isprivate']);
		$ans = Insert('ex_choose',$arr);
		unset($arr);
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
	<?php
		if(checkAdmin(1)){
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
			<label>题目描述:</label>
			<textarea style="width:250px;height:295px;overflow-x:visible;overflow-y:visible;" 
			name="choose_des"></textarea>
		</div>
		<?require_once("../../include/set_post_key.php");?>
		<div class="span4">
			<label>选项描述:</label>
			选项A: <textarea name="ams" style="width:319px;height:40px"></textarea></br>
			选项B: <textarea name="bms" style="width:319px;height:40px"></textarea></br>
			选项C: <textarea name="cms" style="width:319px;height:40px"></textarea></br>
			选项D: <textarea name="dms" style="width:319px;height:40px"></textarea></br>
			<strong>答案:</strong>
			A<input type="radio" name="answer" value="A">&nbsp;
			B<input type="radio" name="answer" value="B">&nbsp;
			C<input type="radio" name="answer" value="C">&nbsp;
			D<input type="radio" name="answer" value="D">&nbsp;
		</div>
		<div class="span8">
			<label for="point">知识点:</label>
			<select name="point" id="point">
			<?php
				$sql="SELECT * FROM `ex_point`";
				$result=mysql_query($sql) or die(mysql_error());
				while($row=mysql_fetch_object($result))
				{
					echo "<option value=\"$row->point\">$row->point</option>";
				}
				mysql_free_result($result);
			?>
			</select>
			<label for="easycount">难度系数:</label>
			<select name="easycount" id="easycount">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
			<label for="isprivate">请选题库类型:</label>
			<select name="isprivate" id="isprivate" onchange="showmsg()">
				<option value="0">公共题库</option>
				<option value="1">私人题库</option>
				<option value="2">系统隐藏</option>
			</select><strong><font color=red id="msg"></font></strong><br/>
			<input type="submit" value="提交" class="mybutton">
			<input type="reset" value="重置" class="mybutton">
		</div>
	</form>
</div>
</div>
<script language="javascript">
function chkinput(form){
	for(var i=0,len=form.length;i<len;i++){
		if(form[i].value=="")
		{
			alert("请输入完整");
			form[i].focus();
			return false;
		}
	}
	var d = document.getElementsByName('answer'),x=0;
	for(var i=0,len=d.length;i<len;i++){
		if(d[i].checked)
			x++;
	}
	if(x==0){
		alert("请输入完整");
		return false;
	}
	return true;
}
function showmsg()
{
	if($('#isprivate').val()==0)
		$('#msg').html('(*公共题库所有人都可见)');
	else if($('#isprivate').val()==1)
		$('#msg').html('(*私人题库仅限本人和最高管理员可见)');
	else if($('#isprivate').val()==2)
		$('#msg').html('(*系统隐藏选择确认后,仅限最高管理员可以查看和修改，请谨慎选择和查看)');
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
<?php
	require_once("./teacher-footer.php");
?>