<?php
	require_once("myinc.inc.php");
	require_once("../../include/check_post_key.php");
	checkAdmin(2);
	$arr['start_time']=intval($_POST['syear'])."-".intval($_POST['smonth'])."-".intval($_POST['sday'])." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
	$arr['end_time']=intval($_POST['eyear'])."-".intval($_POST['emonth'])."-".intval($_POST['eday'])." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";
	$creator=$_SESSION['user_id'];
	$arr['creator']=mysql_real_escape_string($creator);

	$arr['choosescore']=intval($_POST['xzfs']);
	$arr['judgescore']=intval($_POST['pdfs']);
	$arr['fillscore']=intval($_POST['tkfs']);
	$arr['prgans']=intval($_POST['yxjgfs']);
	$arr['prgfill']=intval($_POST['cxtkfs']);
	$arr['programscore']=intval($_POST['cxfs']);
	$arr['isvip']=mysql_real_escape_string($_POST['isvip']);

	$title=$_POST['examname'];
	if(get_magic_quotes_gpc()){
		$title = stripslashes($title);
	}
	$arr['title']=mysql_real_escape_string($title);

	$eid=Insert('exam',$arr);
	unset($arr);
	echo "<script> window.location.href=\"add_exam_problem.php?eid=$eid&type=5\";</script>";
?>