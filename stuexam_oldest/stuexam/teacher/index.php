<?php
	require_once("./teacher-header.php");
?>
<?php
	if(isset($_GET['search']))
	{
		$search=mysql_real_escape_string($_GET['search']);
		if(get_magic_quotes_gpc())
			$search=stripslashes($search);
		if($search!='')
			$searchsql=" WHERE `visible`='Y' AND (`creator` like '%$search%' or `title` like '%$search%')";
		else
			$searchsql=" WHERE `visible`='Y'";
	}
	else
	{
		$search="";
		$searchsql=" WHERE `visible`='Y'";
	}
	$pageinfo = splitpage('exam',$searchsql);
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
	<h2 style="text-align:center">考试总览</h2>
	<form action="add_exam.php" method="get" class="pull-left">
		<input type="submit" value="添加考试" class="mybutton">
	</form>
	<form class="form-search pull-right">
  		<div class="input-append">
  		<input type="hidden" name="page" value="<?=$page?>" />
   	 	<input type="text" class="span3 search-query" value="<?=$search?>" name="search" placeholder="查询创建者或考试名称">
    	<input type="submit" class="btn" value="Search">
    	</div>
	</form>
	<table class="table table-hover table-bordered table-striped table-condensed">
		<thread>
			<th width=5%>考试ID</th>
			<th width=30%>考试名称</th>
			<th width=12%>开始时间</th>
			<th width=12%>结束时间</th>
			<th width=8%>状态</th>
			<th width=8%>创建者</th>
			<th width=10% colspan="2">操作</th>
		</thread>
		<tbody>
		<?php
			require_once("../../include/set_get_key.php");
			$key=$_SESSION['getkey'];
			$sql="SELECT `exam_id`,`title`,`start_time`,`end_time`,`creator` FROM `exam` $searchsql ORDER BY `exam_id` DESC $sqladd";
			$result = mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_object($result)){
				$starttime=strtotime($row->start_time);
				$endtime=strtotime($row->end_time);
				$now=time();
				echo "<tr>";
				echo "<td>$row->exam_id</td>";
				echo "<td><a href=\"exam_user_score.php?eid=$row->exam_id\">$row->title</a></td>";
				echo "<td style=\"font-size:9px\">$row->start_time</td>";
				echo "<td style=\"font-size:9px\">$row->end_time</td>";
				if ($now>$endtime)
					echo "<td><font color=green>Ended</font></td>";
				else if($now<$starttime)
					echo "<td><font color=blue>Pending</font></td>";
				else 
					echo "<td><font color=red>Running</font></td>";
				echo "<td>$row->creator</td>";
				echo "<td><a href='javascript:suredo(\"delquestion.php?type=9&id=$row->exam_id&getkey=".$key."\",\"确定删除?\")'>删除</a></td>";
				echo "<td><a href=\"add_exam_problem.php?eid=$row->exam_id&type=9\">编辑</a></td>";
				echo "</tr>";
			}
			mysql_free_result($result);
		?>
		</tbody>
	</table>
	<?php
		$url = "./?pbmtype=exam";
		$urllast = "&search={$search}";
		showpagelast($url,$pageinfo,$urllast);
	?>
</div>
</div>
<script type="text/javascript">
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