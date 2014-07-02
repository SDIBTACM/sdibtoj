<?
require_once("./include/db_info.inc.php");
require_once("oj-header.php");
?>
<title>Registers</title>
<script src="mergely/changestatus.js"></script>
<?
	$admin_ok=0;
	if(!isset($_GET['cid']))
	{
		echo "No such contest!\n";
		require_once("oj-footer.php");
		exit(0);
	}
	$cid=intval($_GET['cid']);
  if (isset($_SESSION['administrator'])||isset($_SESSION["m$cid"]))
	{
		$admin_ok=1;
	}
	$sql="SELECT `private`,`title`,`end_time` FROM `contest` WHERE `contest`.`contest_id` ='$cid'";
	$result=mysql_query($sql);
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if('2'!=$row->private)
	{
		print "<script language='javascript'>\n";
		print "alert('This contest is not register!\\n');\n";
		print "history.go(-1);\n</script>";
		exit(0);
	}
	$endtime=strtotime($row->end_time);
	$nowtime=time();
	if($endtime<=$nowtime)
		$admin_ok=0;
	echo "<center><h1 style=\"COLOR:#1A5CC8\">报名列表</h1>";
	echo "<font style=\"COLOR:#1A5CC8\">比赛名称：$row->title</font></center>";
	$sql="SELECT * FROM `contestreg` WHERE `contestreg`.`contest_id`='$cid' ORDER BY `registertime`";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	echo "<table width=93% align=center>
	<tr>    	
	<td align=center bgcolor=\"#1A5CC8\" width=5%><font color=white>序号</font></td>
	<td align=center bgcolor=\"#1A5CC8\" width=10%><font color=white>用户名</font></td>
	<td align=center bgcolor=\"#1A5CC8\" width=12%><font color=white>姓名</font></td>
	<td align=center bgcolor=\"#1A5CC8\" width=5%><font color=white>性别</font> </td>
	<td align=center bgcolor=\"#1A5CC8\" width=12%><font color=white>学号 </font></td>
	<td align=center bgcolor=\"#1A5CC8\" width=15%><font color=white>学校</font></td>
	<td align=center bgcolor=\"#1A5CC8\" width=12%><font color=white>学院</font></td>
	<td align=center bgcolor=\"#1A5CC8\" width=12%><font color=white>专业</font></td>
	<td align=center bgcolor=\"#1A5CC8\" width=10%><font color=white>状态 </font></td>
	</tr>";
	$RID=1;
	$color=1;
	while ($row=mysql_fetch_object($result)){
	if ($color) echo "<tr align=center class=oddrow>";
	else echo "<tr align=center class=evenrow>";
	echo "<td>$RID</td>";
	echo "<td>$row->user_id";
        echo "<td>$row->sturealname";
	if($row->stusex=='M')
		echo "<td>男";
	else
		echo "<td>女";
	if($row->stuid=='')
		echo "<td>-------";
	else
		echo "<td>$row->stuid";
	if($row->stuschoolname=='')
		echo "<td>-------";
	else
		echo "<td>$row->stuschoolname";
	if($row->studepartment=='')
		echo "<td>-------";
	else
		echo "<td>$row->studepartment";
	if($row->stumajor=='')
		echo "<td>-------";
	else
		echo "<td>$row->stumajor";
	if($row->ispending==1){
		if($admin_ok){
			echo "<td>";
		echo "<input value=\"X\" id=\"X$RID\" type=\"button\" style=\"color:gray\" 
		onclick=changestatu('S$RID','-1','$cid','$row->user_id')>";
			echo "<span id=\"S$RID\" style=\"color:red\">Accepted</span>";
			echo "<input value=\"√\" id=\"Y$RID\" type=\"button\" style=\"color:blue\" 
		onclick=changestatu('S$RID','1','$cid','$row->user_id')>";
		}
		else
			echo "<td style=\"color:red\">Accepted";
	}
	else if($row->ispending==0){
		if($admin_ok){
			echo "<td>";
		echo "<input value=\"X\" id=\"X$RID\" type=\"button\" style=\"color:gray\" 
		onclick=changestatu('S$RID','-1','$cid','$row->user_id')>";
			echo "<span id=\"S$RID\" style=\"color:green\">Pending</span>";
			echo "<input value=\"√\" id=\"Y$RID\" type=\"button\" style=\"color:blue\" 
		onclick=changestatu('S$RID','1','$cid','$row->user_id')>";
		}
		else
			echo "<td style=\"color:green\">Pending";
	}
	else{
		if($admin_ok){
			echo "<td>";
		echo "<input value=\"X\" id=\"X$RID\" type=\"button\" style=\"color:gray\" 
		onclick=changestatu('S$RID','-1','$cid','$row->user_id')>";
			echo "<span id=\"S$RID\" style=\"color:#545454\">Rejected</span>";
			echo "<input value=\"√\" id=\"Y$RID\" type=\"button\" style=\"color:blue\" 
		onclick=changestatu('S$RID','1','$cid','$row->user_id')>";
		}
		else
			echo "<td style=\"color:#545454\">Rejected";
	}
	echo "</tr>";
	$color=!$color;
	$RID++;
	}
	echo "</table>";
	require_once('oj-footer.php');
?>
