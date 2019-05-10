<?require_once("admin-header.php");
//require_once("../include/db_info.inc.php");
if (!(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator']))){
        echo "<a href='../loginpage.php'>Please Login First!</a>";
        exit(1);
}
?>
<?
if (isset($_POST['syear']))
{
	require_once("../include/db_info.inc.php");
        require_once("../include/check_post_key.php");
        $starttime=intval($_POST['syear'])."-".intval($_POST['smonth'])."-".intval($_POST['sday'])." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
         $endtime=intval($_POST['eyear'])."-".intval($_POST['emonth'])."-".intval($_POST['eday'])." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";

       //	echo $starttime;
	//	echo $endtime;
	$title=mysql_real_escape_string($_POST['title']);
	$private=mysql_real_escape_string($_POST['private']);
	$description=$_POST['description'];
	$regstarttime=$starttime;
	$regendtime=$endtime;
	if($private=='2'){
		$regstarttime=intval($_POST['rsyear'])."-".intval($_POST['rsmonth'])."-".intval($_POST['rsday'])." ".intval($_POST['rshour']).":".intval($_POST['rsminute']).":00";
         	$regendtime=intval($_POST['reyear'])."-".intval($_POST['remonth'])."-".intval($_POST['reday'])." ".intval($_POST['rehour']).":".intval($_POST['reminute']).":00";
		$a=strtotime($regendtime);
		$b=strtotime($regstarttime);
		if($a<$b)
		{
			print "<script language='javascript'>\n";
			print "alert('register time is wrong!\\n');\n";
			print "history.go(-1);\n</script>";
			exit(0);
		}
	}
        if (get_magic_quotes_gpc ()){
		$title = stripslashes ($title);
		$private = stripslashes ($private);
                $description = stripslashes ($description);
	}
	$title=mysql_real_escape_string($title);
	$private=mysql_real_escape_string($private);
        $description=mysql_real_escape_string($description);
        $lang=$_POST['lang'];
        $langmask=$OJ_LANGMASK;
    	foreach($lang as $t){
			$langmask+=1<<$t;
	}
	$langmask=15&(~$langmask);

	// 验证是否可以选择该题目
	$plist = trim($_POST['cproblem']);
	$_problemIds = empty($plist) ? array() : explode(',', $plist);
	$pieces = array();
	if (count($_problemIds) > 0) {
		$sql = "select defunct, author, problem_id from problem where problem_id in ($plist)";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_object($result)) {
			if ($row->defunct == 'N') {
				$pieces[] = $row->problem_id;
			} else {
				if (!strcmp($row->author, $_SESSION['user_id']) || isset($_SESSION['administrator'])) {
					$pieces[] = $row->problem_id;
				}
			}
		}
	}
	if (count($_problemIds) != count($pieces)) {
		print "<script language='javascript'>\n";
		print "alert('选择的题目列表中有隐藏题目, 不能保存!\\n');\n";
		print "history.go(-1);\n</script>";
		exit(0);
	}
	$ipList = json_encode(explode("\r\n", $_POST['ip_list']));

	//echo $langmask;
         $sql="INSERT INTO `contest`(`title`,`start_time`,`end_time`,`private`,`langmask`,`description`,`reg_start_time`,`reg_end_time`, `allow_ips`)
       VALUES('$title','$starttime','$endtime','$private',$langmask,'$description','$regstarttime','$regendtime', '$ipList')";
//	echo $sql;
	mysql_query($sql) or die(mysql_error());
	$cid=mysql_insert_id();
	echo "Add Contest ".$cid;
	$sql="DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
	if (count($pieces)>0 && strlen($pieces[0])>0){
		$sql_1="INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`)
			VALUES ('$cid','$pieces[0]',0)";
		for ($i=1;$i<count($pieces);$i++){
			$sql_1=$sql_1.",('$cid','$pieces[$i]',$i)";
		}
		//echo $sql_1;
		mysql_query($sql_1) or die(mysql_error());
		$sql="update `problem` set defunct='N' where `problem_id` in ($plist)";
		mysql_query($sql) or die(mysql_error());
	}
	$sql="DELETE FROM `privilege` WHERE `rightstr`='c$cid'";
	mysql_query($sql);
       $sql="insert into `privilege` (`user_id`,`rightstr`)  values('".$_SESSION['user_id']."','m$cid')";
       mysql_query($sql);
      $_SESSION["m$cid"]=true;

	$pieces = explode("\n", trim($_POST['ulist']));
	if (count($pieces)>0 && strlen($pieces[0])>0){
		$sql_1="INSERT INTO `privilege`(`user_id`,`rightstr`)
			VALUES ('".trim($pieces[0])."','c$cid')";
		for ($i=1;$i<count($pieces);$i++)
			$sql_1=$sql_1.",('".trim($pieces[$i])."','c$cid')";
		//echo $sql_1;
		mysql_query($sql_1) or die(mysql_error());
	}
   echo "<script>window.location.href=\"contest_list.php\";</script>";
}
else{
   		if(isset($_GET['cid'])){
		   $cid=intval($_GET['cid']);
                   $sql="select * from contest WHERE `contest_id`='$cid'";
		   $result=mysql_query($sql) or die(mysql_error());
		   $row=mysql_fetch_object($result);
		   $title=$row->title;
		   mysql_free_result($result);
			$plist="";
			$sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid ORDER BY `num`";
			$result=mysql_query($sql) or die(mysql_error());
			for ($i=mysql_num_rows($result);$i>0;$i--){
				$row=mysql_fetch_row($result);
				$plist=$plist.$row[0];
				if ($i>1) $plist=$plist.',';
			}
   }
else if(isset($_POST['problem2contest'])){
	   $plist="";
	   //echo $_POST['pid'];
	   sort($_POST['pid']);
	   foreach($_POST['pid'] as $i){
			if ($plist)
				$plist.=','.$i;
			else
				$plist=$i;
	   }
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="../ckeditor/ckeditor.js"></script>
    <title>Add Contest</title>
</head>
	<body onload="JudgeUp()">
	<form method=POST action='<?=$_SERVER['PHP_SELF']?>'>
	<p align=center><font size=4 color=#333399>Add a Contest</font></p>
	<p align=left>Title:<input type=text name=title size=71 value="<?=isset($title)?$title:""?>"></p>
	<p align=left>Start Time:<br>&nbsp;&nbsp;&nbsp;
	Year:<input type=text name=syear value=<?=date('Y')?> size=7 >
	Month:<input type=text name=smonth value=<?=date('m')?> size=7 >
	Day:<input type=text name=sday size=7 value=<?=date('d')?> >&nbsp;
	Hour:<input type=text name=shour size=7 value=<?=date('H')?>>&nbsp;
	Minute:<input type=text name=sminute value=00 size=7 ></p>
	<p align=left>End Time:<br>&nbsp;&nbsp;&nbsp;
	Year:<input type=text name=eyear value=<?=date('Y')?> size=7 >
	Month:<input type=text name=emonth value=<?=date('m')?> size=7 >
        Day:<input type=text name=eday size=7 value=<?=date('d')+(date('H')+4>23?1:0)?>>&nbsp;
        Hour:<input type=text name=ehour size=7 value=<?=(date('H')+4)%24?>>&nbsp;
	Minute:<input type=text name=eminute value=00 size=7 ></p>
	Public:<select name=private id=private onchange="JudgePrivate(this.value)">
		<option value=0>Public</option>
		<option value=1>Private</option>
		<option value=2>Register</option></select>
	Language:<select name="lang[]" multiple>
		<option value=0 selected>C</option>
		<option value=1 selected>C++</option>
		<option value=2 selected>Pascal</option>
		<option value=3 selected>Java</option>
		</select>
	<div id="registertime" style="display:none">
	<p align=left>Register Start:<br>&nbsp;&nbsp;&nbsp;
	Year:<input type=text name=rsyear value=<?=date('Y')?> size=7 >
	Month:<input type=text name=rsmonth value=<?=date('m')?> size=7 >
	Day:<input type=text name=rsday size=7 value=<?=date('d')?> >&nbsp;
	Hour:<input type=text name=rshour size=7 value=<?=date('H')?>>&nbsp;
	Minute:<input type=text name=rsminute value=00 size=7 ></p>
	<p align=left>Register End:<br>&nbsp;&nbsp;&nbsp;
	Year:<input type=text name=reyear value=<?=date('Y')?> size=7 >
	Month:<input type=text name=remonth value=<?=date('m')?> size=7 >
        Day:<input type=text name=reday size=7 value=<?=date('d')+(date('H')+4>23?1:0)?>>&nbsp;
        Hour:<input type=text name=rehour size=7 value=<?=(date('H')+4)%24?>>&nbsp;
	Minute:<input type=text name=reminute value=00 size=7 ></p>
	</div>
        <?require_once("../include/set_post_key.php");?>
	<br>Problems:<input type=text size=60 name=cproblem value="<?=isset($plist)?$plist:""?>">
	<br>*题号与题号之间用英文逗号分开。例:1500,1501,1502
        <br>
        <p align=left>Description:<br><!--<textarea rows=13 name=description cols=80></textarea>-->
            <textarea name="description">
                <?php echo $description;?>
            </textarea>
        </p>
    <br>
	Users:<textarea name="ulist" rows="10" cols="20"></textarea>
	Allow Login IP:<textarea name="ip_list" rows="10" cols="20"></textarea>
	<br />
    <p> * 可以将学生学号从Excel整列复制过来，然后要求他们用学号做UserID注册,就能进入Private的比赛作为作业和测验。</p>
    <p>* Allow login ip 使用CIDR模式记录。</p>
	<p><input type=submit value=Submit name=submit"><input type=reset value=Reset name=reset></p>

</form>

<script language='javascript'>
    function JudgePrivate(temp)
    {
        var divn=document.getElementById('registertime');
        if(temp==2)
            divn.style.display='';
        else
            divn.style.display='none';
    }
    function JudgeUp()
    {
        var ispublic=document.getElementById('private');
        var divn=document.getElementById('registertime');
        var value=ispublic.value;
        if(value==2)
            divn.style.display='';
        else
            divn.style.display='none';
    }
    CKEDITOR.replace('description');
</script>

</body>
<?
}
require_once("../oj-footer.php");

?>

