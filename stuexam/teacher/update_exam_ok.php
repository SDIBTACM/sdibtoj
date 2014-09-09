<?
	require_once("../../include/db_info.inc.php");
	require_once("../../include/check_post_key.php");
	if (!(isset($_SESSION['administrator'])
		||isset($_SESSION['contest_creator']))){
	echo "You have no privilege";
	echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
	exit(1);
	}
	if(isset($_POST['eid']))
	{
		$eid=intval($_POST['eid']);
		$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$creator=mysql_result($priresult, 0);
		mysql_free_result($priresult);
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "<script language='javascript'>\n";
	    	echo "alert(\"You have no privilege to modify it!\");\n";  
        	echo "location='add_exam_problem.php?eid=$eid&type=9'\n";
        	echo "</script>";
		}
		else
		{
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
			$query="UPDATE `exam` SET `title`='".$title."',`start_time`='$starttime',`end_time`='$endtime',
			`choosescore`='$xzfs',`judgescore`='$pdfs',`fillscore`='$tkfs',`prgans`='$yxjgfs',`prgfill`='$cxtkfs',`programscore`='$cxfs',`isvip`='$isvip' WHERE `exam_id`='$eid'";
			mysql_query($query) or die(mysql_error());
			echo "<script> window.location.href=\"./\";</script>";
		}
	}
	else
	{
		echo "<script language='javascript'>\n";
	    echo "alert(\"Invaild Path!\");\n";  
        echo "history.go(-1);\n";
        echo "</script>";
	}
?>