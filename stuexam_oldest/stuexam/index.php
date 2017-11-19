<?
	require_once("../include/db_info.inc.php");
	require_once("exam-header.php");
?>
<?
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

	$sql="SELECT COUNT(*) FROM `exam` WHERE `visible`='Y'";
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
<div class="mytable">
	<table class="table table-hover table-bordered table-striped">
		<caption><h1>Exam List</h1></caption>
		<thead>
			<tr>
				<th width=5%>ID</th>
				<th width=30%>Exam Title</th>
				<th width=15%>Start Time</th>
				<th width=15%>End Time</th>
				<th width=10%>Status</th>
			</tr>
		</thead>
		<tbody>
		<?
			$sql="SELECT `exam_id`,`title`,`start_time`,`end_time`,`creator` from `exam` WHERE `visible`='Y' ORDER BY `exam_id` DESC $sqladd";
			$result = mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_object($result)){
				$starttime=strtotime($row->start_time);
				$endtime=strtotime($row->end_time);
				$now=time();
				echo "<tr>";
				echo "<td>$row->exam_id</td>";
				echo "<td><a href=\"./aboutexam.php?eid=$row->exam_id\">$row->title</a></td>";
				echo "<td>$row->start_time</td>";
				echo "<td>$row->end_time</td>";
				if ($now>$endtime) 
					echo "<td><font color=green>Ended</font></td>";
				else if($now<$starttime) 
					echo "<td><font color=blue>Pending</font></td>";
				else 
					echo "<td><font color=red>Running</font></td>";
			}
			mysql_free_result($result);
		?>
		</tbody>
	</table>
	<?
	echo "<div class=\"pagination\" style=\"text-align:center\">";
	echo "<ul>";
	echo "<li><a href=\"./?page=1\">First</a></li>";
	if($page==1)
		echo "<li class=\"disabled\"><a href=\"\">Previous</a></li>";
	else
		echo "<li><a href=\"./?page=$prepage\">Previous</a></li>";
	for($i=$startpage;$i<=$endpage;$i++)
	{
		if($i==$page)
			echo "<li class=\"active\"><a href=\"./?page=$i\">$i</a></li>";
		else
	  		echo "<li><a href=\"./?page=$i\">$i</a></li>";
	}
	if($page==$lastpage)
		echo "<li class=\"disabled\"><a href=\"\">Next</a></li>";
	else
		echo "<li><a href=\"./?page=$nextpage\">Next</a></li>";
	echo "<li><a href=\"./?page=$lastpage\">Last</a></li>";
	echo "</ul>";
	echo "</div>";
	?>
</div>

<?
require_once("exam-footer.php")
?>
