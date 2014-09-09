<?
	require_once("./teacher-header.php");
?>
<?
	if(isset($_GET['search']))
	{
		$search=mysql_real_escape_string($_GET['search']);
		if($search!='')
			$searchsql=" WHERE `creator` like '%$search%' or `point` like '%$search%'";
		else
			$searchsql="";
	}
	else
	{
		$search="";
		$searchsql="";
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

	$sql="SELECT COUNT(*) FROM `ex_choose` $searchsql";
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
	<h2 style="text-align:center">选择题库总览</h2>
	<form action="add_choose.php" method="get" class="pull-left">
		<input type="submit" value="添加选择题" class="mybutton">
	</form>
	<form class="form-search pull-right">
  		<div class="input-append">
  		<input type="hidden" name="page" value="<?=$page?>" />
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
			$sql="SELECT `choose_id`,`question`,`addtime`,`creator`,`point`,`easycount` FROM `ex_choose` $searchsql ORDER BY `choose_id` DESC $sqladd";
			$result = mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_object($result)){
				echo "<tr>";
				echo "<td>$row->choose_id</td>";
				$question=$row->question;
				echo "<td>$question</td>";
				echo "<td style=\"font-size:9px\">$row->addtime</td>";
				echo "<td>$row->creator</td>";
				echo "<td>$row->point</td>";
				echo "<td>$row->easycount</td>";
				echo "<td><a href='javascript:suredo(\"delquestion.php?type=1&id=$row->choose_id&getkey=".$key."\",\"确定删除?\")'>删除</a></td>";
				echo "<td><a href='./edit_choose.php?id=$row->choose_id'>编辑</a></td>";
				echo "</tr>";
			}
			mysql_free_result($result);
		?>
		</tbody>
	</table>
	<?
	echo "<div class=\"pagination\" style=\"text-align:center\">";
	echo "<ul>";
	echo "<li><a href=\"admin_choose.php?page=1&search=$search\">First</a></li>";
	if($page==1)
		echo "<li class=\"disabled\"><a href=\"\">Previous</a></li>";
	else
		echo "<li><a href=\"admin_choose.php?page=$prepage&search=$search\">Previous</a></li>";
	for($i=$startpage;$i<=$endpage;$i++)
	{
		if($i==$page)
			echo "<li class=\"active\"><a href=\"admin_choose.php?page=$i&search=$search\">$i</a></li>";
		else
	  		echo "<li><a href=\"admin_choose.php?page=$i&search=$search\">$i</a></li>";
	}
	if($page==$lastpage)
		echo "<li class=\"disabled\"><a href=\"\">Next</a></li>";
	else
		echo "<li><a href=\"admin_choose.php?page=$nextpage&search=$search\">Next</a></li>";
	echo "<li><a href=\"admin_choose.php?page=$lastpage&search=$search\">Last</a></li>";
	echo "</ul>";
	echo "</div>";
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
<?
	require_once("./teacher-footer.php");
?>