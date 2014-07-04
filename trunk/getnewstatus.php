<?
	require_once("./include/db_info.inc.php");
	$to=$_GET['to'];
	$cid=intval($_GET['cid']);
	$user_id=mysql_real_escape_string($_GET['user']);
	$pending=intval($to);
	if($pending==1)
		$response="<font color=red>Accepted";
	else if($pending==-1)
		$response="<font color=gray>Rejected";
	else if($pending==2)
		$response="<font color=green>请修改信息";
$sql="UPDATE `contestreg` SET `ispending`='$pending' WHERE `contestreg`.`user_id`='".$user_id."' AND `contestreg`.`contest_id`='$cid'";
	mysql_query($sql) or die(mysql_error());
$sql_1="SELECT count(*) FROM `privilege` WHERE `privilege`.`user_id`='".$user_id."' AND `privilege`.`rightstr`='c$cid'";
	$result=mysql_query($sql_1);
	$row=mysql_fetch_array($result);
	$pid_cnt=intval($row[0]);
	mysql_free_result($result);
	if($pid_cnt)
	{
		if($pending==1)
			;
		else if($pending==-1||$pending==2)
		{
			$sql="DELETE FROM `privilege` WHERE `privilege`.`user_id`='".$user_id."' AND `privilege`.`rightstr`='c$cid'";
			mysql_query($sql) or die(mysql_error());
		}
	}
	else
	{
		if($pending==1)
		{
			$sql="INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES ('".$user_id."','c$cid')";
			mysql_query($sql) or die(mysql_error());
		}
		else if($pending==-1||$pending==2)
			;
	}
	echo	$response;
?>