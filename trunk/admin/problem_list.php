<?
require("admin-header.php");
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}

$sql="SELECT max(`problem_id`) as upid FROM `problem`";
$page_cnt=100;
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_object($result);
$cnt=intval($row->upid)-1000;
$cnt=intval($cnt/$page_cnt);
if (isset($_GET['page'])){
	$page=intval($_GET['page']);
}else $page=$cnt+1;
$pstart=900+$page_cnt*intval($page);
$pend=$pstart+$page_cnt-1;

echo "<title>Problem List</title>";
echo "<center><h2>Problem List</h2>";

for ($i=1;$i<=$cnt+1;$i++){
	if ($i>1) echo '&nbsp;';
	if ($i==$page) echo "<span class=red>$i</span>";
	else echo "<a href='problem_list.php?page=".$i."'>".$i."</a>";
}
echo "</center>";
if(isset($_GET['search'])){
  $sql="select  `problem_id`,`title`,`in_date`,`defunct` FROM `problem` where `defunct`='Y'  order by `problem_id` desc  ";
}
else
{
  $sql="select `problem_id`,`title`,`in_date`,`defunct` FROM `problem` where problem_id>=$pstart and problem_id<=$pend order by `problem_id` desc";
 }
//echo $sql;
$result=mysql_query($sql) or die(mysql_error());
echo "<center><table width=90% border=1>";
echo "<form method=post action=contest_add.php>";
echo "<tr><td colspan=5><input type=submit name='problem2contest' value='CheckToNewContest'>";
echo "<tr><td>PID<td>Title<td>Date";
if(isset($_SESSION['administrator'])){
    echo "<td>Defunct<td>Edit</tr>";
}
for (;$row=mysql_fetch_object($result);){
	echo "<tr>";
	echo "<td>".$row->problem_id;
	echo "<input type=checkbox name='pid[]' value='$row->problem_id'>";
	echo "<td><a href='../problem.php?id=$row->problem_id'>".$row->title."</a>";
	echo "<td>".$row->in_date;
	if(isset($_SESSION['administrator'])){
		echo "<td><a href=problem_df_change.php?id=$row->problem_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"Delete":"Resume")."</a>";
		echo "<td><a href=problem_edit.php?id=$row->problem_id&getkey=".$_SESSION['getkey'].">Edit</a>";
		//echo "<td><input type=submit name='problem2contest' value='ToNewContest'>";
	}
	echo "</tr>";
}
echo "<tr><td colspan=5><input type=submit name='problem2contest' value='CheckToNewContest'></tr></form>";
echo "<tr><td width='90%' colspan='4'><form>$MSG_SEARCH<input type='text' name='search'><input type='submit' value='$MSG_SEARCH' ></form></td></tr>";
echo "</table></center>";
require("../oj-footer.php");
?>
