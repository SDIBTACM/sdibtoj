<?php
	@session_start();
	require_once("myinc.inc.php");
	checkuserid();
	checkAdmin(2);
?>
<?php
	if(isset($_GET['eid'])&&isset($_GET['users']))
	{
		$eid=intval(trim($_GET['eid']));
		$users=trim($_GET['users']);
		$users=mysql_real_escape_string($users);
		$sql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
		$row = fetchOne($sql);
		if(!$row)
		{
			echo "No Such Exam";
			exit(0);
		}
		$creator=$row['creator'];
		if(checkAdmin(4,$creator))
		{
			alertmsg("You have no privilege to do it!");
		}
		else
		{
			Delete('ex_student',"user_id='{$users}' and exam_id='{$eid}'");
			echo "<script language='javascript'>\n";
			echo "history.go(-1);\n";
			echo "</script>";
		}
	}
	else{
		alertmsg("Invaild path");
	}
?>