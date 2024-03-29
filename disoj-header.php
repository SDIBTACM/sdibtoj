<?
	require("./include/db_info.inc.php");
	if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
		if(file_exists("./faqs.$OJ_LANG.php")){
			$OJ_FAQ_LINK="faqs.$OJ_LANG.php";
		}
	}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel=stylesheet href='./include/hoj.css' type='text/css'>
<?
	
	function checkcontest($MSG){
		require_once("./include/db_info.inc.php");
		$sql="SELECT count(*) FROM `contest` WHERE `end_time`>NOW() AND `defunct`='N'";
		$result=mysql_query($sql);
		$row=mysql_fetch_row($result);
		if (intval($row[0])==0) $retmsg=$MSG;
		else $retmsg=$row[0]."<font color=red>&nbsp;$MSG</font>";
		mysql_free_result($result);
		return $retmsg;
	}
	
	

	if($OJ_ONLINE){
		require_once('./include/online.php');
		$on = new online();
	}
?>
</head>
<body>
<center>
<h2><img id=logo src=./image/logo.png><font color="red">Welcome To <?=$OJ_NAME?> ACM-ICPC Online Judge</font></h2>
<table width=96%>
	<tr align="center" class='hd' valign="top">
	        <th><a href="http://acm.sdibt.edu.cn:8080/vjudge/"><font color=red>VIRTUAL JUDGE</font></a></th>
        	<th><span style="color:1a5cc8" id="dict_status"></span></th>
		<th><a href="./<?=isset($OJ_FAQ_LINK)?$OJ_FAQ_LINK:"faqs.php"?>"><?=$MSG_FAQ?></a></th>
		<th><a href="./discuss.php"><?=$MSG_BBS?></a></th>
		<th><a href="<?=$OJ_HOME?>"><?=$MSG_HOME?></a></th>
		<th><a href="./problemset.php"><?=$MSG_PROBLEMS?></a></th>
		<th><a href="./status.php"><?=$MSG_STATUS?></a></th>
		<th><a href="./ranklist.php"><?=$MSG_RANKLIST?></a></th>
		<th><a href="./contest.php"><?=checkcontest($MSG_CONTEST)?></a></th>
		<?
			
			if (isset($_SESSION['user_id'])){
				$sid=$_SESSION['user_id'];
				print "<th><a href=./modifypage.php><b>$MSG_USERINFO</b></a>&nbsp;&nbsp;<a href='userinfo.php?user=$sid'><font color=red>$sid</font></a></th>";
				print "<th><a href=./logout.php>$MSG_LOGOUT</a></th>";
			}else{
				print "<th><a href=./loginpage.php>$MSG_LOGIN</a></th>";
				print "<th><a href=./registerpage.php>$MSG_REGISTER</a></th>";
			}
			if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])){
				print "<th><a href=./admin>$MSG_ADMIN</a></th>";
			
			}
		?>
	</tr>
</table>
</center>
<?
$fp=fopen("./admin/msg.txt","r");
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
?>
<hr>
<script src="./include/underlineTranslation.js" type="text/javascript"></script> 
<script type="text/javascript">dictInit();</script> 
