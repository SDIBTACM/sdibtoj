<?
	session_start();
if (!isset($_SESSION['user_id'])){
	require_once("oj-header.php");
	echo "<a href='loginpageD.php'>Please Login First!</a>";
	require_once("oj-footer.php");
	exit(0);
}
require_once("include/db_info.inc.php");
require_once("include/const.inc.php");
$user_id=$_SESSION['user_id'];

if (isset($_POST['cid'])){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);
	$sql="SELECT `problem_id` from `contest_problem` 
				where `num`='$pid' and contest_id=$cid";
}else{
	$id=intval($_POST['id']);
	$sql="SELECT `problem_id` from `problem` where `problem_id`='$id' and problem_id not in (select distinct problem_id from contest_problem where `contest_id` IN (
			SELECT `contest_id` FROM `contest` WHERE 
			(`end_time`>NOW())and `defunct`='N'
			))";
	if(!isset($_SESSION['administrator']))
		$sql.=" and (defunct='N'  or (`author`!='' and `author`='".$_SESSION['user_id']."'". "))";
}
//echo $sql;	

$res=mysql_query($sql);
if ($res&&mysql_num_rows($res)<1){
		mysql_free_result($res);
		require_once('oj-header.php');
		echo "Where do find this link? No such problem.<br>";
		require_once('oj-footer.php');
		exit(0);
}
mysql_free_result($res);



if (isset($_POST['id'])) {
	$id=intval($_POST['id']);
	
}else if (isset($_POST['pid']) && isset($_POST['cid'])){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);

	$row = mysql_fetch_object(mysql_query("SELECT allow_ips from contest where cid = $cid"));
	if (! ((isset($_SESSION['administrator']) || isset($_SESSION['contest_creator']))) ) {
		if (! isIpInSubnets($_SERVER['REMOTE_ADDR'], json_decode($row->allow_ips, true) )) {
			require_once("contest-header.php");
			echo "You are not invited!\n";
			require_once("oj-footer.php");
			exit(0);
		}
	}
	
	// check user if private
	$sql="SELECT `private` FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<=NOW() AND `end_time`>NOW()";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	if ($rows_cnt!=1){
		echo "You Can't Submit Now Because Your are not invited by the contest or the contest is not running!!";
		mysql_free_result($result);
		require_once("oj-footer.php");
		exit(0);
	}else{
		$row=mysql_fetch_array($result);
		$isprivate=intval($row[0]);
		mysql_free_result($result);
		if ($isprivate==1||$isprivate==2){
			$sql="SELECT count(*) FROM `privilege` WHERE `user_id`='$user_id' AND `rightstr`='c$cid'";
			$result=mysql_query($sql) or die (mysql_error()); 
			$row=mysql_fetch_array($result);
			$ccnt=intval($row[0]);
			mysql_free_result($result);
			if ($ccnt==0){
				require_once("contest-header.php");
				echo "You are not invited!\n";
				require_once("oj-footer.php");
				exit(0);
			}
		}
	}
	$sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`='$cid' AND `num`='$pid'";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	if ($rows_cnt!=1){
		require_once("contest-header.php");
		echo "<h2>No Such Problem!</h2>";
		require_once("oj-footer.php");
		mysql_free_result($result);
		exit(0);
	}else{
		$row=mysql_fetch_object($result);
		$id=intval($row->problem_id);
		mysql_free_result($result);
	}
}else{
	echo "<h2>No Such Problem!</h2>";
	exit(0);
}


$language=intval($_POST['language']);
if ($language>9 || $language<0) $language=0;
$language=strval($language);



$source=$_POST['source'];
if(get_magic_quotes_gpc())
	$source=stripslashes($source);
$source=mysql_real_escape_string($source);
//$source=trim($source);
$len=strlen($source);
//echo $source;
//use append Main code

$prefix_file="$OJ_DATA/$id/prefix.$language_ext[$language]";
$append_file="$OJ_DATA/$id/append.$language_ext[$language]";
echo $prefix_file;
if(isset($OJ_APPENDCODE)&&$OJ_APPENDCODE&&file_exists($prefix_file)){
     $source=mysql_real_escape_string(file_get_contents($prefix_file)."\n").$source;
}
if(isset($OJ_APPENDCODE)&&$OJ_APPENDCODE&&file_exists($append_file)){
     $source.=mysql_real_escape_string("\n".file_get_contents($append_file));
}
//end of append 


setcookie('lastlang',$language,time()+360000);

$ip=$_SERVER['REMOTE_ADDR'];

if ($len<2){
	require_once("oj-header.php");
	echo "Source Code Too Short!";
	require_once("oj-footer.php");
	exit(0);
}
if ($len>65536){
	require_once("oj-header.php");
	echo "Source Code Too Long!";
	require_once("oj-footer.php");
	exit(0);
}

// last submit

$sql="SELECT `in_date` from `solution` where `user_id`='$user_id' and in_date>now()-10 order by `in_date` desc limit 1";
$res=mysql_query($sql);
if (mysql_num_rows($res)==1){
	//$row=mysql_fetch_row($res);
	//$last=strtotime($row[0]);
	//$cur=time();
	//if ($cur-$last<10){
		require_once('oj-header.php');
		echo "You should not submit more than twice in 10 seconds.....<br>";
		require_once('oj-footer.php');
		exit(0);
	//}
}



if (!isset($pid)){
$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length)
	VALUES('$id','$user_id',NOW(),'$language','$ip','$len')";
}else{
$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,contest_id,num)
	VALUES('$id','$user_id',NOW(),'$language','$ip','$len','$cid','$pid')";
}
mysql_query($sql);
$insert_id=mysql_insert_id();

$sql="INSERT INTO `source_code`(`solution_id`,`source`)VALUES('$insert_id','$source')";
mysql_query($sql);
//echo $sql;


$sql="update `problem` set `in_date`=NOW() where `problem_id`=$id";
@mysql_query($sql) or die(mysql_error());
if (!isset($cid)) 
	header("Location: ./status.php?"."user_id=".$_SESSION['user_id']);
else 
	header("Location: ./status.php?cid=$cid"."&user_id=".$_SESSION['user_id']);
?>
