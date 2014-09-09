<?
	require_once("../../include/db_info.inc.php");
	require_once("../../include/check_post_key.php");
	if (!(isset($_SESSION['administrator'])
		||isset($_SESSION['contest_creator']))){
	echo "You have no privilege";
	echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
	exit(1);
	}
	$starttime=intval($_POST['syear'])."-".intval($_POST['smonth'])."-".intval($_POST['sday'])." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
	$endtime=intval($_POST['eyear'])."-".intval($_POST['emonth'])."-".intval($_POST['eday'])." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";
	$title=$_POST['examname'];
	$xzfs=intval($_POST['xzfs']);
	$pdfs=intval($_POST['pdfs']);
	$tkfs=intval($_POST['tkfs']);
	$yxjgfs=intval($_POST['yxjgfs']);
	$cxtkfs=intval($_POST['cxtkfs']);
	$cxfs=intval($_POST['cxfs']);
	$isvip=mysql_real_escape_string($_POST['isvip']);
	if(get_magic_quotes_gpc()){
		$title = stripslashes($title);
	}
	$title=mysql_real_escape_string($title);
	$creator=$_SESSION['user_id'];
	$creator=mysql_real_escape_string($creator);
	$query="INSERT INTO `exam` 
	(`title`,`start_time`,`end_time`,`creator`,`choosescore`,`judgescore`,`fillscore`,`prgans`,`prgfill`,`programscore`,`isvip`) 
	VALUES('".$title."','$starttime','$endtime','".$creator."','$xzfs','$pdfs','$tkfs','$yxjgfs','$cxtkfs','$cxfs','$isvip')";
	mysql_query($query) or die(mysql_error());
	$eid=mysql_insert_id();
	echo "<script> window.location.href=\"add_exam_problem.php?eid=$eid&type=5\";</script>";
?>