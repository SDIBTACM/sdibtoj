<?require_once("./include/db_info.inc.php");

         require_once("./lang/en.php");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel=stylesheet href='include/hoj.css' type='text/css'>
</head>
<?
if(isset($_GET['cid']))
	$cid=intval($_GET['cid']);
if (isset($_GET['pid']))
	$pid=intval($_GET['pid']);
?>
<table width=100% class=toprow><tr align=center>
	<td width=15%><a class=hd href='./'><?=$MSG_HOME?></a>
<!--	<td width=15%><a class=hd href='./discuss.php?cid=<?=$cid?>'><?=$MSG_BBS?></a>   -->
	<td width=15%><a class=hd href='./contest.php?cid=<?=$cid?>'><?=$MSG_PROBLEMS?></a>
	<td width=15%><a class=hd href='./contestrank.php?cid=<?=$cid?>'><?=$MSG_STANDING?></a>
	<td width=15%><a class=hd href='./status.php?cid=<?=$cid?>'><?=$MSG_STATUS?></a>
	<td width=15%><a class=hd href='./conteststatistics.php?cid=<?=$cid?>'><?=$MSG_STATISTICS?></a>
</tr></table>
<br>
<?php
$url = basename($_SERVER['REQUEST_URI']);
if(isset($OJ_NEED_LOGIN) && $OJ_NEED_LOGIN
        && ($url!='loginpage.php' && $url!='lostpassword.php'
            && $url!='lostpassword2.php' && $url!='registerpage.php')
        && !isset($_SESSION['user_id'])) {
    header("location:"."loginpage.php");
    exit();
}
?>
<?
$fp=fopen("admin/msg.txt","r");
$msg="";
while (!feof($fp)){
	$strtmp=fgets($fp);
	$msg=$msg.$strtmp;
}
if (strlen($msg)>5){
	echo "<marquee scrollamount=3 behavior=ALTERNATE scrolldelay=150>";
	echo "<font color=red>";
	echo $msg;
	echo "</font>";
	echo "</marquee>";
}

$contest_ok=true;
$str_private="SELECT count(*), langmask FROM `contest` WHERE `contest_id`='$cid' && (`private`='1' || `private`='2')";
$result=mysql_query($str_private);
$row=mysql_fetch_row($result);
mysql_free_result($result);
isset($row[1]) ? $langmask = $row[1] : null;
if ($row[0]=='1' && !isset($_SESSION['c'.$cid])) $contest_ok=false;
if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])) $contest_ok=true;
?>
