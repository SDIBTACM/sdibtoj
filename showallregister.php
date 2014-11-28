<?
require_once("./include/db_info.inc.php");
require_once("oj-header.php");
?>
<title>Registers</title>
<script src="mergely/changestatus.js"></script>
<?
	$admin_ok=0;
   $flag=0;
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
                $flag=1;
	}
	$sql="SELECT `private`,`title`,`end_time`,`reg_start_time`,`reg_end_time` FROM `contest` WHERE `contest`.`contest_id` ='$cid'";
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
	echo "<font style=\"COLOR:#1A5CC8\">比赛名称：$row->title</font>";
	echo "<br><font style=\"COLOR:#1A5CC8\">注册时间：    $row->reg_start_time 到     $row->reg_end_time    </font>";
   if($flag)
		echo "<span style=\"COLOR:red\">报名表下载:<a href=cstregister.xls.php?cid=$cid>Download</a></font>";
	if($admin_ok){
		echo "<form action=\"admin/setseatnum.php?cid=$cid\" method=\"post\">";
		echo "<input value=\"生成座位号\" id=\"seat\" type=\"Submit\"></form>";
	}
	echo "<span id=\"txtHint\"></span></center>";
	$sql="SELECT * FROM `contestreg` WHERE `contestreg`.`contest_id`='$cid' ORDER BY `registertime`";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result); 
	echo "<table width=98% align=center>
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
	<td align=center bgcolor=\"#1A5CC8\" width=5%><font color=white>座位号</font></td>
	</tr>";
	$RID=1;
	$color=1;
	while ($row=mysql_fetch_object($result)){
	if ($color) echo "<tr align=center class=oddrow>";
	else echo "<tr align=center class=evenrow>";
	echo "<td>$RID</td>";
	echo "<td><a href=./userinfo.php?user=$row->user_id>$row->user_id</a>";
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
		echo "<input value=\"X\" id=\"X$RID\" type=\"button\" style=\"color:gray\" title='拒绝'
		onclick=changestatu('S$RID','-1','$cid','$row->user_id')>";
		echo "<input value=\"?\" id=\"W$RID\" type=\"button\" style=\"color:green\" title='等待修改'
		onclick=changestatu('S$RID','2','$cid','$row->user_id')>";
			echo "<span id=\"S$RID\" style=\"color:red\">Accepted</span>";
			echo "<input value=\"√\" id=\"Y$RID\" type=\"button\" style=\"color:blue\" title='接受'
		onclick=changestatu('S$RID','1','$cid','$row->user_id')>";
		}
		else
			echo "<td style=\"color:red\">Accepted";
	}
	else if($row->ispending==0){
		if($admin_ok){
			echo "<td>";
		echo "<input value=\"X\" id=\"X$RID\" type=\"button\" style=\"color:gray\" title='拒绝'
		onclick=changestatu('S$RID','-1','$cid','$row->user_id')>";
		echo "<input value=\"?\" id=\"W$RID\" type=\"button\" style=\"color:green\" title='等待修改'
		onclick=changestatu('S$RID','2','$cid','$row->user_id')>";
			echo "<span id=\"S$RID\" style=\"color:green\">Pending</span>";
			echo "<input value=\"√\" id=\"Y$RID\" type=\"button\" style=\"color:blue\" title='接受'
		onclick=changestatu('S$RID','1','$cid','$row->user_id')>";
		}
		else
			echo "<td style=\"color:green\">Pending";
	}
	else if($row->ispending==-1){
		if($admin_ok){
			echo "<td>";
		echo "<input value=\"X\" id=\"X$RID\" type=\"button\" style=\"color:gray\" title='拒绝' 
		onclick=changestatu('S$RID','-1','$cid','$row->user_id')>";
		echo "<input value=\"?\" id=\"W$RID\" type=\"button\" style=\"color:green\" title='等待修改'
		onclick=changestatu('S$RID','2','$cid','$row->user_id')>";
			echo "<span id=\"S$RID\" style=\"color:#545454\">Rejected</span>";
			echo "<input value=\"√\" id=\"Y$RID\" type=\"button\" style=\"color:blue\" title='接受'
		onclick=changestatu('S$RID','1','$cid','$row->user_id')>";
		}
		else
			echo "<td style=\"color:#545454\">Rejected";
	}
	else if($row->ispending==2){
		if($admin_ok){
			echo "<td>";
		echo "<input value=\"X\" id=\"X$RID\" type=\"button\" style=\"color:gray\" title='拒绝'
		onclick=changestatu('S$RID','-1','$cid','$row->user_id')>";
		echo "<input value=\"?\" id=\"W$RID\" type=\"button\" style=\"color:green\" title='等待修改'
		onclick=changestatu('S$RID','2','$cid','$row->user_id')>";
			echo "<span id=\"S$RID\" style=\"color:green\">请修改信息</span>";
			echo "<input value=\"√\" id=\"Y$RID\" type=\"button\" style=\"color:blue\" title='接受'
		onclick=changestatu('S$RID','1','$cid','$row->user_id')>";
		}
		else
			echo "<td style=\"color:green\">请修改信息";	
	}
	if(($row->seatnum=='')||($row->ispending!=1))
		echo "<td>-------";
	else
		echo "<td>$row->seatnum";
	echo "</tr>";
	$color=!$color;
	$RID++;
	}
	echo "</table>";
	mysql_free_result($result);
	require_once('oj-footer.php');
?>
