<?php
	require_once("../include/db_info.inc.php");
	$cid=$_GET['cid'];
	if (!(isset($_SESSION['administrator'])||isset($_SESSION["m$cid"])))
	{
		echo "You have no privilege!\n";
		require_once("oj-footer.php");
		exit(0);
	}
	$sql="SELECT * FROM `contestreg` WHERE `contestreg`.`contest_id`='$cid' AND `contestreg`.`ispending`='1'";
	$result=mysql_query($sql) or die(mysql_error());
	$row_cnt=mysql_num_rows($result);
	if($row_cnt){
		$num=range(1,$row_cnt);
		$i=0;
		shuffle($num);
		$seatnum=array_slice($num,0,$row_cnt);
		while ($row=mysql_fetch_object($result)){
$sql_2="UPDATE `contestreg` SET `seatnum`='$seatnum[$i]' WHERE `contest_id`='$row->contest_id' AND `user_id`='".$row->user_id."'";
			mysql_query($sql_2);
			$i++;		
		}
	}
	mysql_free_result($result);
	header("Location: ../showallregister.php?cid=$cid");
?>
