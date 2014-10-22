<?
	@session_start();
	if(!isset($_SESSION['user_id']))
	{
		echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
		exit(0);
	}
	if (!(isset($_SESSION['administrator'])
		||isset($_SESSION['contest_creator']))){
	echo "You have no privilege";
	echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
	exit(1);
	}
	require_once("../../include/db_info.inc.php");
?>
<?
	if(isset($_GET['eid'])&&isset($_GET['users']))
	{
		$eid=intval(trim($_GET['eid']));
		$users=trim($_GET['users']);
		$users=mysql_real_escape_string($users);
		$sql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
		$result=mysql_query($sql) or die(mysql_error());
		$cnt = mysql_num_rows($result);
		if($cnt==0)
		{
			echo "No Such Exam";
			exit(0);
		}
		$creator=mysql_result($result, 0);
		mysql_free_result($result);
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "<script language='javascript'>\n";
			echo "alert(\"You have no privilege to do it!\");\n";
			echo "history.go(-1);";
			echo "</script>";
		}
		else
		{
			$sql="DELETE FROM `ex_student` WHERE `user_id`='".$users."' AND `exam_id`='$eid'";
			mysql_query($sql) or die(mysql_error());
			echo "<script language='javascript'>\n";
        	echo "history.go(-1);\n";
        	echo "</script>";
		}
	}
	else
	{
		echo "<script language='javascript'>\n";
	    echo "alert(\"Invaild path\");\n";  
        echo "history.go(-1);\n";
        echo "</script>";
	}
?>