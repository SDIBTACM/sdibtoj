<?
require_once("./include/db_info.inc.php");
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");

require_once("contest-header.php");
// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);

$sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<NOW()";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if ($num==0){
	echo "Not Started!";
	require_once("oj-footer.php");
	exit();
}
mysql_free_result($result);

echo "<title>Contest Statistics</title>";

$sql="SELECT count(`num`) FROM `contest_problem` WHERE `contest_id`='$cid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$pid_cnt=intval($row[0]);
mysql_free_result($result);

$sql="SELECT `result`,`num`,`language` FROM `solution` WHERE `contest_id`='$cid'"; 
$result=mysql_query($sql);
$R=array();
while ($row=mysql_fetch_object($result)){
	$res=intval($row->result)-4;
	if ($res<0) $res=8;
	$num=intval($row->num);
	$lag=intval($row->language);
	$R[$num][$res]++;
	$R[$num][$lag+10]++;
	$R[$pid_cnt][$res]++;
	$R[$pid_cnt][$lag+10]++;
	$R[$num][8]++;
	$R[$pid_cnt][8]++;
}
mysql_free_result($result);
echo "<center><h3>Contest Statistics</h3><table width=60%>";
echo "<tr align=center class=toprow><td><td>AC<td>PE<td>WA<td>TLE<td>MLE<td>OLE<td>RE<td>CE<td>Total<td><td>C<td>C++<td>Pascal<td>Java</tr>";
for ($i=0;$i<$pid_cnt;$i++){
	if(!isset($PID[$i])) $PID[$i]="";
	
	if ($i&1) 
		echo "<tr align=center class=oddrow><td>";
	else 
		echo "<tr align=center class=evenrow><td>";
	echo "<a href='problem.php?cid=$cid&pid=$i'>$PID[$i]</a>";
	for ($j=0;$j<15;$j++) {
		if(!isset($R[$i][$j])) $R[$i][$j]="";
		echo "<td>".$R[$i][$j];
	}
	echo "</tr>";
}
echo "<tr align=center class=evenrow><td>Total";	
for ($j=0;$j<15;$j++) {
	if(!isset($R[$i][$j])) $R[$i][$j]="";
	echo "<td>".$R[$i][$j];
}
echo "</tr>";
echo "<table></center>";
?>
<?require_once("oj-footer.php")?>
