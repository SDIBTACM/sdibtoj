<?
	require_once("./teacher-header.php");
?>
<?
	if(isset($_GET['search']))
	{
		$search=mysql_real_escape_string($_GET['search']);
		if(get_magic_quotes_gpc())
			$search=stripslashes($search);
		if($search!='')
			$searchsql=" WHERE `visible`='Y' AND `creator` like '%$search%' or `title` like '%$search%'";
		else
			$searchsql=" WHERE `visible`='Y'";
	}
	else
	{
		$search="";
		$searchsql=" WHERE `visible`='Y'";
	}
	if(isset($_GET['page']))
	{
		if(!is_numeric($_GET['page']))
			$page=1;
		$page=intval($_GET['page']);
	}
	else
		$page=1;
	$each_page=15;// each page data num
	$pagenum=10;//the max of page num

	$sql="SELECT COUNT(*) FROM `exam` $searchsql";
	$result=mysql_query($sql) or die(mysql_error());
	$total=mysql_result($result, 0);
	mysql_free_result($result);

	$totalpage=ceil($total/$each_page);
	if($totalpage==0)	$totalpage=1;
	$page=$page<1?1:$page;
	$page=$page>$totalpage?$totalpage:$page;

	$offset=($page-1)*$each_page;
	$sqladd=" limit $offset,$each_page";

	$lastpage=$totalpage;
	$prepage=$page-1;
	$nextpage=$page+1;

	$startpage=$page-4;
	$startpage=$startpage<1?1:$startpage;
	$endpage=$startpage+$pagenum-1;
	$endpage=$endpage>$totalpage?$totalpage:$endpage;
?>
<div>
<div class="leftmenu pull-left" id="left">
<ul class="nav nav-pills bs-docs-sidenav">
	<li class="active"><a href="./">考试管理</a></li>
	<li class=""><a href="admin_choose.php">选择题管理</a></li>
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
		<?
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
	<?
	echo "<div class=\"pagination\" style=\"text-align:center\">";
	echo "<ul>";
	echo "<li><a href=\"./?page=1&search=$search\">First</a></li>";
	if($page==1)
		echo "<li class=\"disabled\"><a href=\"\">Previous</a></li>";
	else
		echo "<li><a href=\"./?page=$prepage&search=$search\">Previous</a></li>";
	for($i=$startpage;$i<=$endpage;$i++)
	{
		if($i==$page)
			echo "<li class=\"active\"><a href=\"./?page=$i&search=$search\">$i</a></li>";
		else
	  		echo "<li><a href=\"./?page=$i&search=$search\">$i</a></li>";
	}
	if($page==$lastpage)
		echo "<li class=\"disabled\"><a href=\"\">Next</a></li>";
	else
		echo "<li><a href=\"./?page=$nextpage&search=$search\">Next</a></li>";
	echo "<li><a href=\"./?page=$lastpage&search=$search\">Last</a></li>";
	echo "</ul>";
	echo "</div>";
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
<?
	require_once("./teacher-footer.php");
?>