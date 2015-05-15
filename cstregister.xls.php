<?php
		ob_start();
		header('Content-type: application/excel');  
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
	header ( "content-disposition:   attachment;   filename=contest".$cid."_".$title."Registration.xls" );
}
else{
	echo "No Such Contest";
	exit(0);
}
mysql_free_result($result);

//if ($reg_end_time>time()){
//	echo "Registration is running!";
//	exit(0);
//}
//else if($reg_start_time>time()){
//	echo "Registration is pending!";
//	exit(0);
//}
echo "<center><h3>Registration -- $title</h3></center>\n";
echo "<table border=1><tr><td>ID<td>Name</td><td>Sex</td><td>School_id</td><td>Phone</td><td>Email</td><td>School</td><td>Department</td><td>Major</td><td>Seatnum</td></tr>\n";
//$sql="SELECT * FROM `contestreg` WHERE `contest_id`='$cid' AND ispending='1'";
$sql="SELECT * FROM `contestreg` WHERE `contest_id`='$cid'";
$result=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_object($result))
{       
        $regid=$row->user_id;
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
	echo "<tr>";
	echo "<td>$regid</td>";
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
}
mysql_free_result($result);
echo "</table>";
?>
