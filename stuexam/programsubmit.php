<?
	@session_start();
	if(!isset($_SESSION['user_id']))
	{
		echo "<a href='../loginpage.php'>Please Login First!</a>";
		exit(0);
	}
	if(!isset($_POST['eid']))
	{
		echo $_POST['eid'];
		echo "No Such Exam";
		exit(0);
	}
	if(!isset($_POST['id']))
	{
		echo "No Such Problem";
		exit(0);
	}
	require_once("../include/db_info.inc.php");
	if(isset($OJ_LANG))
		require_once("../lang/$OJ_LANG.php");
	else
		require_once("../lang/en.php");
	require_once("../include/const.inc.php");
	$eid=intval($_POST['eid']);
	$id=intval($_POST['id']);
	$user_id=$_SESSION['user_id'];
	$language=intval($_POST['language']);
	if ($language>9 || $language<0) $language=0;
	$language=strval($language);

	$sql ="SELECT `start_time`,`end_time` FROM `exam` WHERE `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$cnt = mysql_num_rows($result);
	if($cnt==0)
	{
		echo "No Such Exam";
		exit(0);
	}
	$row=mysql_fetch_array($result);
	$start_time=$row[0];
	$starttimeC=strtotime($start_time);
	$end_time=$row[1];
	$endtimeC=strtotime($end_time);
	$now=time();
	mysql_free_result($result);
	if($now<$starttimeC){
		echo "<h1>Exam Not Start</h1>";
		exit(0);
	}
	if($now>$endtimeC){
		echo "<h1>Exam Over</h1>";
		exit(0);
	}
	$prisql="SELECT COUNT(*) FROM `ex_privilege` WHERE `user_id`='".$user_id."' AND `rightstr`='e$eid'";
	$priresult=mysql_query($prisql) or die(mysql_error());
	$num=mysql_result($priresult, 0);
	mysql_free_result($priresult);
	if(!(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||$num))
	{
	    echo "You have no privilege";  
        exit(0);
	}
	$sql="SELECT `user_id` FROM `ex_student` WHERE `user_id`='".$user_id."' AND `exam_id`='$eid'";
	$result=mysql_query($sql) or die(mysql_error());
	$row_cnt=mysql_num_rows($result);
	mysql_free_result($result);
	if($row_cnt)
	{
	    echo "You have taken part in it"; 
	    exit(0);
	}

	$source=$_POST['source'];
	if(get_magic_quotes_gpc())
		$source=stripslashes($source);
	$source=mysql_real_escape_string($source);
	$len=strlen($source);
	$append_file="$OJ_DATA/$id/append.$language_ext[$language]";
	if(isset($OJ_APPENDCODE)&&$OJ_APPENDCODE&&file_exists($append_file)){
     	$source.=mysql_real_escape_string("\n".file_get_contents($append_file));
	}
	setcookie('lastlang',$language,time()+360000);

	$ip=$_SERVER['REMOTE_ADDR'];

	if ($len<=2){
		echo "Source Code Too Short!";
		exit(0);
	}
	if ($len>65536){
		echo "Source Code Too Long!";
		exit(0);
	}

	$sql="SELECT `in_date` FROM `solution` WHERE `user_id`='".$user_id."' AND `in_date`>NOW()-10 ORDER BY `in_date` DESC LIMIT 1";
	$res=mysql_query($sql);
	if (mysql_num_rows($res)==1){
		echo "You should not submit more than twice in 10 seconds.....<br>";
		exit(0);
	}

	$sql="INSERT INTO `solution`(`problem_id`,`user_id`,`in_date`,`language`,`ip`,`code_length`)
		VALUES('$id','$user_id',NOW(),'$language','$ip','$len')";
	mysql_query($sql);
	$insert_id=mysql_insert_id();

	$sql="INSERT INTO `source_code`(`solution_id`,`source`) VALUES('$insert_id','$source')";
	mysql_query($sql);

	$sql="UPDATE `problem` SET `in_date`=NOW() WHERE `problem_id`=$id";
	mysql_query($sql) or die(mysql_error());

	echo "<font color=$judge_color[0] size=5px>$judge_result[0]</font>";
?>