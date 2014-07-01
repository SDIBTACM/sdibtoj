<?php
	//-1 Rejected 0 pending 1 accept
	require_once("./include/db_info.inc.php");
	require_once("./include/my_func.inc.php");
	$user_id=mysql_real_escape_string($_SESSION['user_id']);
	$cid=intval($_GET['cid']);
	$sturealname=trim($_POST['sturealname']);//must
	$stuid=trim($_POST['stuid']);
	$stusex=trim($_POST['stusex']);
	$stuphone=trim($_POST['stuphone']);//must
	$stuemail=trim($_POST['stuemail']);//must
	$stuschoolname=trim($_POST['stuschoolname']);
	$studepartment=trim($_POST['studepartment']);
	$stumajor=trim($_POST['stumajor']);
	//echo "<td>$user_id.aaa.$sturealname";
	$err_str="";
	$err_cnt=0;
	$len=strlen($sturealname);
	if($len<6){
		$err_str=$err_str."Real Name Too Short!\\n";
		$err_cnt++;
	}
	else if($len>50){
		$err_str=$err_str."Real Name Too Long!\\n";
		$err_cnt++;
	}
	$len=strlen($stuphone);
	if($len<6){
		$err_str=$err_str."Phone Num Too Short!\\n";
		$err_cnt++;
	}
	else if($len>15){
		$err_str=$err_str."Phone Num Too Long!\\n";
		$err_cnt++;
	}
	$len=strlen($stuemail);
	if($len<7){
		$err_str=$err_str."Email Too Short!\\n";
		$err_cnt++;
	}
	else if($len>50){
		$err_str=$err_str."Email Too Long!\\n";
		$err_cnt++;
	}
	$len=strlen($stuid);
	if($len>20){
		$err_str=$err_str."Student No Too Long!\\n";
		$err_cnt++;
	}
	$len=strlen($stuschoolname);
	if($len>100){
		$err_str=$err_str."School name Too Long!\\n";
		$err_cnt++;
	}
	else if($len<4){
		$err_str=$err_str."School name too short!\\n";
		$err_cnt++;
	}
	$len=strlen($studepartment);
	if($len>100){
		$err_str=$err_str."Department name Too Long!\\n";
		$err_cnt++;
	}
	$len=strlen($stumajor);
	if($len>100){
		$err_str=$err_str."Major name Too Long!\\n";
		$err_cnt++;
	}
	if ($err_cnt>0){
	print "<script language='javascript'>\n";
	print "alert('";
	print $err_str;
	print "');\n history.go(-1);\n</script>";
	exit(0);
	}
	/*$sql="SELECT `private` FROM `contest` WHERE `contest`.`contest_id` ='$cid'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	if('2'!=$row[0]){
	print "<script language='javascript'>\n";
	print "alert('This contest is not register!\\n');\n";
	print "history.go(-1);\n</script>";
	exit(0);
	}
	*/
	//echo "<td>$stusex</td>";
	$sturealname=mysql_real_escape_string(htmlspecialchars ($sturealname));
	$stuid=mysql_real_escape_string(htmlspecialchars ($stuid));
	$stusex=mysql_real_escape_string(htmlspecialchars ($stusex));
	$stuphone=mysql_real_escape_string(htmlspecialchars ($stuphone));//must
	$stuemail=mysql_real_escape_string(htmlspecialchars ($stuemail));//must
	$stuschoolname=mysql_real_escape_string(htmlspecialchars ($stuschoolname));
	$studepartment=mysql_real_escape_string(htmlspecialchars ($studepartment));
	$stumajor=mysql_real_escape_string(htmlspecialchars ($stumajor));

$sql="UPDATE `contestreg` SET"
."`sturealname`='".($sturealname)."',"
."`stuid`='".($stuid)."',"
."`stusex`='".($stusex)."',"
."`stuphone`='".($stuphone)."',"
."`stuemail`='".($stuemail)."',"
."`stuschoolname`='".($stuschoolname)."',"
."`studepartment`='".($studepartment)."',"
."`stumajor`='".($stumajor)."' "
."WHERE `contestreg`.`user_id`='".$user_id."' AND `contestreg`.`contest_id`='$cid'";
	mysql_query($sql) or die(mysql_error());
	header("Location: ./contest.php");
?>

