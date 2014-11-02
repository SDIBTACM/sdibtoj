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
		$eid=intval($_POST['eid']);
	if($eid>0)
	{
		$sql="DELETE FROM `ex_privilege` WHERE `rightstr`='e$eid'";
		mysql_query($sql) or die(mysql_error());
		$pieces = explode("\n", trim($_POST['ulist']));
		if (count($pieces)>0 && strlen($pieces[0])>0)
		{
			for ($i=0;$i<count($pieces);$i++){
				$pieces[$i]=trim($pieces[$i]);
			}
		}
		$pieces = array_unique($pieces);
		if (count($pieces)>0 && strlen($pieces[0])>0)
		{
			$randnum=rand(1,39916800);
			$query="INSERT INTO `ex_privilege`(`user_id`,`rightstr`,`randnum`) VALUES('".trim($pieces[0])."','e$eid','$randnum')";
			for ($i=1;$i<count($pieces);$i++){
				$randnum=rand(1,39916800);
				$query=$query.",('".trim($pieces[$i])."','e$eid','$randnum')";
			}
			mysql_query($query) or die(mysql_error());
		}
		echo "<script> window.location.href=\"add_exam_user.php?eid=$eid\";</script>";
	}
	else
	{
		echo "<script language='javascript'>\n"; 
	    echo "history.go(-1);\n";
	    echo "</script>";
	}
?>