<?php
	require_once("./teacher-header.php");
?>
<?php
	if(isset($_GET['search']))
	{
		$search=mysql_real_escape_string($_GET['search']);
		if($search!='')
			$searchsql=" WHERE (`creator` like '%$search%' or `point` like '%$search%')";
		else
			$searchsql="";
	}
	else
	{
		$search="";
		$searchsql="";
	}
	if(isset($_GET['problem']))
	{
		$problem=intval($_GET['problem']);
		$prosql = problemshow($problem,$searchsql);
	}
	else
	{
		$problem=0;
		if($searchsql=="")
			$prosql=" WHERE `isprivate`='$problem'";
		else
			$prosql=" AND `isprivate`='$problem'";
	}
	$pageinfo = splitpage('ex_choose',$searchsql,$prosql);
	$page = $pageinfo['page'];
	$prepage=$pageinfo['prepage'];
	$startpage=$pageinfo['startpage'];
	$endpage=$pageinfo['endpage'];
	$nextpage=$pageinfo['nextpage'];
	$lastpage=$pageinfo['lastpage'];
	$eachpage=$pageinfo['eachpage'];
	$sqladd=$pageinfo['sqladd'];
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
	<h2 style="text-align:center">选择题库总览</h2>
	<form action="add_choose.php" method="get" class="pull-left">
		<input type="submit" value="添加选择题" class="mybutton">
		<input type="button" value="查看公共题库" class="mybutton" onclick="window.location.href='?problem=0'">
		<input type="button" value="查看私人题库" class="mybutton" onclick="window.location.href='?problem=1'">
		<?php
			if(checkAdmin(1)){
				echo "<input type=\"button\" value=\"查看隐藏题库\" class=\"mybutton\" onclick=\"window.location.href='?problem=2'\">";
			}
		?>
	</form>
	<form class="form-search pull-right">
  		<div class="input-append">
  		<input type="hidden" name="page" value="<?=$page?>" />
  		<input type="hidden" name="problem" value="<?=$problem?>" />
   	 	<input type="text" class="span3 search-query" value="<?=$search?>" name="search" placeholder="查询创建者或知识点">
    	<input type="submit" class="btn" value="Search">
    	</div>
	</form>
	<table class="table table-hover table-bordered table-striped table-condensed jiadian">
		<thread>
			<th width=4%>ID</th>
			<th width=30%>题目描述</th>
			<th width=10%>创建时间</th>
			<th width=8%>创建者</th>
			<th width=8%>知识点</th>
			<th width=4%>难度</th>
			<th width=8% colspan="2">操作</th>
		</thread>
		<tbody>
		<?
			require_once("../../include/set_get_key.php");
			$key=$_SESSION['getkey'];
			$numofchoose=1+($page-1)*$eachpage;
			$sql="SELECT `choose_id`,`question`,`addtime`,`creator`,`point`,`easycount` FROM `ex_choose` $searchsql $prosql ORDER BY `choose_id` ASC $sqladd";
			$result = mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_object($result)){
				echo "<tr>";
				echo "<td>$numofchoose</td>";
				$question=$row->question;
				echo "<td>$question</td>";
				echo "<td style=\"font-size:9px\">$row->addtime</td>";
				echo "<td>$row->creator</td>";
				echo "<td>$row->point</td>";
				echo "<td>$row->easycount</td>";
				echo "<td><a href='javascript:suredo(\"delquestion.php?type=1&id=$row->choose_id&getkey=".$key."\",\"确定删除?\")'>删除</a></td>";
				echo "<td><a href='./edit_choose.php?id=$row->choose_id'>编辑</a></td>";
				echo "</tr>";
				$numofchoose++;
			}
			mysql_free_result($result);
		?>
		</tbody>
	</table>
	<?php
		$url = "admin_choose.php?pbmtype=choose";
		$urllast = "&search={$search}&problem={$problem}";
		showpagelast($url,$pageinfo,$urllast);
	?>
</div>
</div>
<script language="javascript">
function suredo(src,q){
	var ret;
	ret = confirm(q);
	if(ret!=false)
		window.location.href=src;
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