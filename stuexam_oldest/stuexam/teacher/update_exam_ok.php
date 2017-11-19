<?php
	require_once("myinc.inc.php");
	require_once("../../include/check_post_key.php");
	checkAdmin(2);
	if(isset($_POST['eid']))
	{
		$eid=intval($_POST['eid']);
		$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
		$row=fetchOne($prisql);
		$creator=$row['creator'];
		if(checkAdmin(4,$creator))
		{
			alertmsg("You have no privilege to modify it!","add_exam_problem.php?eid={$eid}&type=9",0);
		}
		else
		{
			$arr['start_time']=intval($_POST['syear'])."-".intval($_POST['smonth'])."-".intval($_POST['sday'])." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
			$arr['end_time']=intval($_POST['eyear'])."-".intval($_POST['emonth'])."-".intval($_POST['eday'])." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";
			$title=$_POST['examname'];
			if(get_magic_quotes_gpc()){
				$title = stripslashes($title);
			}
			$arr['title']=mysql_real_escape_string($title);
			$arr['choosescore']=intval($_POST['xzfs']);
			$arr['judgescore']=intval($_POST['pdfs']);
			$arr['fillscore']=intval($_POST['tkfs']);
			$arr['prgans']=intval($_POST['yxjgfs']);
			$arr['prgfill']=intval($_POST['cxtkfs']);
			$arr['programscore']=intval($_POST['cxfs']);
			$arr['isvip']=mysql_real_escape_string($_POST['isvip']);
			Update('exam',$arr,"exam_id={$eid}");
			echo "<script> window.location.href=\"./\";</script>";
		}
	}
	else{
		alertmsg("Invaild path");
	}
?>