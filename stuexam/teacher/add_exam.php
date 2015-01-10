<?php
	require_once("./teacher-header.php");
?>
<div>
<div class="leftmenu pull-left" id="left">
<ul class="nav nav-pills bs-docs-sidenav">
	<li class="active"><a href="./">考试管理</a></li>
	<li class=""><a href="admin_choose.php">选择题管理</a></li>
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
	<h2 style="text-align:center">添加考试</h2>
	<form action="add_exam_ok.php" method="post" onSubmit="return chkinput(this)">
	考试名称:<input type="text" maxlength="100" name="examname" /><br/>
	考试开始时间:<p>
	<input type="text" name="syear"   value="<?php echo date('Y')?>" style="width:40px" />年-
	<input type="text" name="smonth"  value="<?php echo date('m')?>" style="width:40px" />月-
	<input type="text" name="sday"    value="<?php echo date('d')?>" style="width:40px" />日-
	<input type="text" name="shour"   value="<?php echo date('H')?>" style="width:40px" />时-
	<input type="text" name="sminute" value="00" style="width:40px" />分</p>
	考试结束时间:<p>
	<input type="text" name="eyear"   value="<?php echo date('Y')?>" style="width:40px" />年-
	<input type="text" name="emonth"  value="<?php echo date('m')?>" style="width:40px" style="width:40px" />月-
    <input type="text" name="eday"    value="<?php echo date('d')+(date('H')+2>23?1:0)?>" style="width:40px" />日-
    <input type="text" name="ehour"   value="<?php echo (date('H')+2)%24?>" style="width:40px" />时-
	<input type="text" name="eminute" value=00 style="width:40px">分</p>
	<font color=red>*以下数值只支持整数</font><br/>
	(1)选择题每题分值:<input type="text" name="xzfs" style="width:50px" />分;<br/>
	(2)判断题每题分值:<input type="text" name="pdfs" style="width:50px" />分;<br/>
	(3)基础填空题每空分值:<input type="text" name="tkfs" style="width:50px" />分;<br/>
	(4)写运行结果题每题分值:<input type="text" name="yxjgfs" style="width:50px" />分;<br/>
	(5)程序填空题每题分值:<input type="text" name="cxtkfs" style="width:50px" />分;<br/>
	(6)程序设计题每题分值:<input type="text" name="cxfs" style="width:50px" />分;<br/>
	是否限定一个账号只能在一台机器登陆:<select name="isvip" class="span1">
		<option value="Y" selected>Yes</option>
		<option value="N">No</option>
	</select><br/>
	<?require_once("../../include/set_post_key.php");?>
	<input type="submit" value="添加" class="mybutton">
	<input type="reset" value="重置" class="mybutton">
	</form>
</div>
</div>
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