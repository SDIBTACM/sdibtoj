<?php
		ob_start();
		header('Content-type: application/vnd.ms-excel');  
?>
<?
require_once("./include/db_info.inc.php");
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
if (!isset($_GET['cid'])) 
	die("No Such Contest!");
$cid=intval($_GET['cid']);
if(!(isset($_SESSION["m$cid"])||isset($_SESSION['administrator'])))
{
	echo "You don't have the privilage";
	exit();
}
$sql="SELECT `reg_start_time`,`reg_end_time`,`title` FROM `contest` WHERE `contest_id`='$cid'";
$result=mysql_query($sql) or die(mysql_error());
$rows_cnt=mysql_num_rows($result);
$reg_end_time=0;
if ($rows_cnt>0){
	$row=mysql_fetch_array($result);
	$reg_end_time=strtotime($row[1]);
	$reg_start_time=strtotime($row[0]);
	$title=$row[2];
	if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
		$title=iconv("utf8","gbk",$title);
	}
	header ( "content-disposition:   attachment;   filename=contest".$cid."_".$title."报名表.xls" );
}
else{
	echo "No Such Contest";
	exit(0);
}
mysql_free_result($result);

if ($reg_end_time<time()){
	echo "Registration is running!";
	exit(0);
}
else if($reg_start_time>time()){
	echo "Registration is pending!";
	exit(0);
}
/*echo "<center><h3>报名表 -- $title</h3></center>\n";
echo "<tr><td>姓名</td><td>性别</td><td>学号</td><td>电话</td><td>邮箱</td><td>学校</td><td>学院</td><td>专业</td><td>座位号</td></tr>\n";*/
echo "\t\t报名表 -- $title\n";
echo "姓名\t性别\t学号\t电话\t邮箱\t学校\t学院\t专业\t座位号\t\n";
$sql="SELECT * FROM `contestreg` WHERE `contest_id`='$cid' AND ispending='1'";
$result=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_object($result))
{
	$realname=$row->sturealname;
	$sex=$row->stusex;
	$stuid=$row->stuid;
	$stuphone=$row->stuphone;
	$stuemail=$row->stuemail;
	$stuschool=$row->stuschoolname;
	$studep=$row->studepartment;
	$stumajor=$row->stumajor;
	$seat=$row->seatnum;
	if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
		$realname=iconv("utf8","gbk",$realname);
		$stuschool=iconv("utf8","gbk",$stuschool);
		$studep=iconv("utf8","gbk",$studep);
		$stumajor=iconv("utf8","gbk",$stumajor);
	}
	/*echo "<tr>";
	echo "<td>$realname</td>";
	echo "<td>$sex</td>";
	echo "<td>$stuid</td>";
	echo "<td>$stuphone</td>";
	echo "<td>$stuemail</td>";
	echo "<td>$stuschool</td>";
	echo "<td>$studep</td>";
	echo "<td>$stumajor</td>";
	echo "<td>$seat</td>\n";
	echo "</tr>";
	*/
	echo "$realname\t";
	echo "$sex\t";
	echo "$stuid\t";
	echo "$stuphone\t";
	echo "$stuemail\t";
	echo "$stuschool\t";
	echo "$studep\t";
	echo "$stumajor\t";
	echo "$seat\t\n";
}
mysql_free_result($result);
?>
