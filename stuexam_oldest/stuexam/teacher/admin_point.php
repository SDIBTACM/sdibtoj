<?php
	require_once("./teacher-header.php");
	if(!checkAdmin(1)){
		echo "You have no privilege";
		echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
		exit(1);
	}
?>
<?php
	if(isset($_POST['point'])&&isset($_POST['add']))
	{
		$point=trim($_POST['point']);
		if(get_magic_quotes_gpc())
			$post=stripslashes($point);
		$point=mysql_real_escape_string($point);
		Insert('ex_point',array('point'=>"'{$point}'"));
	}
	else if(isset($_POST['point'])&&isset($_POST['del']))
	{
		$point=$_POST['point'];
		if(get_magic_quotes_gpc())
			$post=stripslashes($point);
		$point=mysql_real_escape_string($point);
		Delete('ex_point',"point='{$point}'");
	}
	else
	{
		$query="SELECT * FROM `ex_point`";
		$result=mysql_query($query) or die(mysql_error());
?>
<div>
<div class="leftmenu pull-left" id="left">
<ul class="nav nav-pills bs-docs-sidenav">
	<li class=""><a href="./">考试管理</a></li>
	<li class=""><a href="admin_choose.php">选择题管理</a></li>
	<li class=""><a href="admin_judge.php">判断题管理</a></li>
	<li class=""><a href="admin_fill.php">填空题管理</a></li>
	<?php
		if(checkAdmin(1)){
			echo "<li class=\"active\"><a href=\"admin_point.php\">知识点管理</a></li>";
		}
	?>
	<li><a href="../">退出管理页面</a></li>
</ul>
</div>
<div class="container" style="margin-left:23%" id="right">
	<h2 style="text-align:center">知识点管理</h2>
	<div id="mypoint" class="span10">
	<?php
		$num=0;
		while($row=mysql_fetch_object($result))
		{
			echo "<pre class=\"span2\" id=\"point$num\">$row->point<a href=\"javascript:void(0);\" onclick=delpoint('point$num')>[del]</a></pre>";
			$num++;
		}
		mysql_free_result($result);
	?>
	</div>
	<div align=center>
	<input type="text" style="margin:1px" id="pointtext">
	<input type="button" class="btn btn-success" onclick="addpoint()" value="添加知识点">
	</div>
</div>
</div>
<script language="javascript">
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
<script>
var numC=<?=$num?>;
function addpoint()
{
	var tmp=$('#pointtext').val();
	if(tmp!=""){
		$('#mypoint').append('<pre class=span2 id='+numC+'>'+tmp+'<a href=javascript:void(0); onclick=delpoint('+numC+')>[del]</a></pre>');
		numC++;
		$('#pointtext').val("");
		$.ajax({
			url:'admin_point.php',
			data:'point='+tmp+'&add=1',
			type:'POST',
			error:function(){
				alert('sorry,something error');
			}
		});
	}
}
function delpoint(preid)
{
	var textv=$('#'+preid).text();
	var tmp=textv.substr(0,textv.length-5);
	$('#'+preid).remove();
	$.ajax({
		url:'admin_point.php',
		data:'point='+tmp+'&del=1',
		type:'POST',
		error:function(){
			alert('sorry,something error');
		}
	});
}
</script>
<?php
}
	require_once("./teacher-footer.php");
?>