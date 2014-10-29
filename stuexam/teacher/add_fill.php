<?
	require_once("./teacher-header.php");
?>
<?
	function test_input($data){
		$data = trim($data);
  		//$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		$data = mysql_real_escape_string($data);
  		return $data;
	}
	if(isset($_POST['fill_des']))
	{
		require_once("../../include/check_post_key.php");
		$question=test_input($_POST['fill_des']);
		$point=test_input($_POST['point']);
		$easycount=intval($_POST['easycount']);
		$answernum=intval($_POST['numanswer']);
		$kind=intval($_POST['kind']);
		$isprivate=intval($_POST['isprivate']);
		//$answernum=count($_POST)-5;
		//important the first is the postkey the second is the fill_des
		//the third is point the forth is the easycount the fifth is kind
		$creator=$_SESSION['user_id'];
		$query="INSERT INTO `ex_fill` 
		(`question`,`answernum`,`addtime`,`creator`,`point`,`easycount`,`kind`,`isprivate`)
		VALUES('".$question."','$answernum',NOW(),'".$creator."','".$point."','$easycount','$kind','$isprivate')";
		mysql_query($query) or die(mysql_error());
		$fillid=mysql_insert_id();
		for($i=1;$i<=$answernum;$i++)
		{
			$answer=test_input($_POST["answer$i"]);
			$query="INSERT INTO `fill_answer` 
			(`fill_id`,`answer_id`,`answer`) 
			VALUES('$fillid','$i','".$answer."')";
			mysql_query($query) or die(mysql_error());
		}
		echo "<script>window.location.href=\"./admin_fill.php\";</script>";
	}
	else{
?>
<div>
<div class="leftmenu pull-left" id="left">
<ul class="nav nav-pills bs-docs-sidenav">
	<li class=""><a href="./">考试管理</a></li>
	<li class=""><a href="admin_choose.php">选择题管理</a></li>
	<li class=""><a href="admin_judge.php">判断题管理</a></li>
	<li class="active"><a href="admin_fill.php">填空题管理</a></li>
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
	<h2 style="text-align:center">添加填空题</h2>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onSubmit="return chkinput(this)">
		<div class="pull-left span8">
			<label>题目描述:</label>
			<textarea style="width:490px;height:160px;overflow-x:visible;overflow-y:visible;" 
			name="fill_des"></textarea>
		</div>
		<?require_once("../../include/set_post_key.php");?>
		<label><font color=red>请按如下格式编写题目中需要填写的部分:</font></label>
		<textarea style="width:100pxx;height:100px;overflow-x:visible;overflow-y:visible;" 
		disabled>若x和n均是int型变量,且x和n的初值均为5,则计算表达式x+=n++后x的值为__(1)__,n的值为__(2)__
		</textarea>
		<div class="span8">
			<label for="kind">填空题类型:</label>
			<select name="kind" id="kind"  onchange="showspan()">
				<option value="1">基础填空题</option>
				<option value="2">写运行结果</option>
				<option value="3">程序填空</option>
			</select>
			<label for="point">知识点:</label>
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
			<strong>答案:<font color=red id="warnmsg">(*请确保下面文本框个数与题目中的填空数相同)</font></strong>
			<input class="btn" type="button" value="添加答案空" onclick="addinput()" />
			<input class="btn" type="button" value="删除答案空" onclick="delinput()" />
		</div>
		<input type="hidden" name="numanswer" id="numanswer" value="0">
		<div class="span4" id="Content">
		</div>
		<div class="span8">
			<input type="submit" value="提交" class="mybutton">
			<input type="reset" value="重置" class="mybutton">
		</div>
	</form>
</div>
</div>
<script type="text/javascript">
var cont=document.getElementById("Content");
var num_answer=0;
function addinput()
{
	if(num_answer<8)
	{
		num_answer++;
		var Newinput=document.createElement("input");
		Newinput.setAttribute("type","text");
		Newinput.setAttribute("maxlength","100");
		Newinput.setAttribute("name","answer"+num_answer);
		Newinput.setAttribute("id","answer"+num_answer);
		cont.appendChild(Newinput);
		$("#numanswer").val(num_answer);
	}
	else
		alert("空数已达到上限");
}
function delinput()
{
	if(num_answer>0)
	{
		var index=cont.getElementsByTagName("input").length;
		var node = document.getElementById("answer"+index);
		node.parentNode.removeChild(node);
		num_answer--;
		$('#numanswer').val(num_answer);
	}
	else
	{
		alert("Nothing to be deleted");
	}
}
function chkinput(form)
{
	if(form.fill_des.value=="")
	{
		alert("请输入题目描述");
		form.fill_des.focus();
		return(false); 
	}
	for(var i=1;i<form.length;i++)
	{
		if(form.elements[i].value=="")
		{
			alert(form.elements[i].name+"不能为空");
			form.elements[i].focus();
			return false;
		}
	}
	return(true);
}
function showspan()
{
	if($('#kind').val()==1)
		$('#warnmsg').html('(*请确保下面文本框个数与题目中的填空数相同)');
	else if($('#kind').val()==2)
		$('#warnmsg').html('(*请确保空数个数与程序在编译器端运行结果的行数一致)');
	else if($('#kind').val()==3)
		$('#warnmsg').html('(*请确保下面文本框个数与题目中的填空数相同)');
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
	if($('#kind').val()==1)
		$('#warnmsg').html('(*请确保下面文本框个数与题目中的填空数相同)');
	else if($('#kind').val()==2)
		$('#warnmsg').html('(*请确保空数个数与程序在编译器端运行结果的行数一致)');
	else if($('#kind').val()==3)
		$('#warnmsg').html('(*请确保下面文本框个数与题目中的填空数相同)');
});
</script>
<?
}
	require_once("./teacher-footer.php");
?>