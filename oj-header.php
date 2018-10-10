<?php 
	@session_start();
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel=stylesheet href='include/hoj.css' type='text/css'>
<?

	require_once('./include/db_info.inc.php');
        require_once('./include/my_func.inc.php');
	
//       if(noRefresh())
  //     {
    //            echo "刷新太快，休息休息。<br>";
      //          echo "给你讲个故事吧.<br>step 1.从前有座山，山里有个庙，庙里有个老和尚，老和尚正在给小和尚讲故事，讲什么故事呢？<br>step 2.重复1<br>step 3.看明白没，这是不是个递归？";
          //      sleep(10);
        //         exit (0);
        
      // }
	function checkcontest($MSG_CONTEST){
		require_once("include/db_info.inc.php");
		$sql="SELECT count(*) FROM `contest` WHERE `end_time`>NOW() AND `defunct`='N'";
		$result=mysql_query($sql);
		$row=mysql_fetch_row($result);
		if (intval($row[0])==0) $retmsg=$MSG_CONTEST;
		else
                   $retmsg=$row[0]."<font color=red>&nbsp;$MSG_CONTEST</font>";
         	mysql_free_result($result);
		return $retmsg;
	}
	function checkmail(){
		require_once("include/db_info.inc.php");
		$sql="SELECT count(1) FROM `mail` WHERE 
				new_mail=1 AND `to_user`='".$_SESSION['user_id']."'";
		$result=mysql_query($sql);
		if(!$result) return false;
		$row=mysql_fetch_row($result);
     
                  $sql1="SELECT count(1) FROM `mail` WHERE
                               `to_user`='".$_SESSION['user_id']."'";
                $result1=mysql_query($sql1);
                if(!$result1) return false;
                $row1=mysql_fetch_row($result1);
		if($row[0]==0)
	       		$retmsg="<font color=red>(".$row[0]."/".$row1[0].")</font>";
		else
			$retmsg="<font id=blink color=red>(".$row[0]."/".$row1[0].")</font>";
		mysql_free_result($result);mysql_free_result($result1);

		return $retmsg;
	}
	if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
		if(file_exists("./faqs.$OJ_LANG.php")){
			$OJ_FAQ_LINK="faqs.$OJ_LANG.php";
		}
	}
	

	if($OJ_ONLINE){
		require_once('./include/online.php');
		$on = new online();
	}
?>
</head>
<body>
<center>
<h2><img id=logo src=./image/logo.png><font color="red">Welcome To <?=$OJ_NAME?> ACM-ICPC Online Judge</font></h2>
<table width=96%> 
	<tr align="center" class='hd' valign="top">
                 <th><a href="../vjudge/"><font color=red>VIRTUAL JUDGE</font></a></th>
		<?if(isset($OJ_DICT)&&$OJ_DICT&&$OJ_LANG=="cn"){?>
      <div class=hd>
		      <span style="color:1a5cc8" id="dict_status"></span>
      </div>
      <script src="include/underlineTranslation.js" type="text/javascript"></script>
                <?}?>
                
                  <th><a href="./recent-contest.php">Recent Contest</a></th>



		<th><a href="<?=isset($OJ_FAQ_LINK)?$OJ_FAQ_LINK:"faqs.php"?>"><?=$MSG_FAQ?></a></th>
		<th><a href="../forum/"><?=$MSG_BBS?></a></th>
		<th><a href="../"><?=$MSG_HOME?></a></th>
		<th><a href="./problemset.php"><?=$MSG_PROBLEMS?></a></th>
		<th><a href="./status.php"><?=$MSG_STATUS?></a></th>
		<th><a href="./ranklist.php"><?=$MSG_RANKLIST?></a></th>
		<th><a href="./contest.php"><?=checkcontest($MSG_CONTEST)?></a></th>
		<?
		       $flag=0;	
			if (isset($_SESSION['user_id'])){
				$sid=$_SESSION['user_id'];
				print "<th><a href=./modifypage.php><b>$MSG_USERINFO</b></a><a href='userinfo.php?user=$sid'>
				<font color=red>$sid</font></a>";
				$mail=checkmail();
				if ($mail){
					
					if(strpos($mail,"blink") === false)//ä‰äªç‰äºŽå· 
						$flag=0;//ææœ‰blink
					else
						$flag=1;
					
					print "<a href=mail.php>$mail</a>";
				}
				print "</th><th><a href=logout.php>$MSG_LOGOUT</a></th>";
			}else{
				print "<th><a href=loginpage.php>$MSG_LOGIN</a></th>";
				print "<th><a href=registerpage.php>$MSG_REGISTER</a></th>";
			}
			if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||isset($_SESSION['problem_editor'])){
				print "<th><a href=admin/>$MSG_ADMIN</a></th>";
			
			}
		?>
           
		<th><a href="./stuexam/home/dispatcher/">Exam</a></th>
	</tr>
</table>
</center>
<?
$fp=fopen("admin/msg.txt","r");
$msg="";
while (!feof($fp)){
	$strtmp=fgets($fp);
	$msg=$msg.$strtmp;
}
if (strlen($msg)>5){
	echo "<marquee scrollamount=3 behavior=ALTERNATE scrolldelay=150>";
	echo "<font color=red>";
	echo $msg;
	echo "</font>";
	echo "</marquee>";
}
?>
<script src="include/underlineTranslation.js" type="text/javascript"></script> 
<script type="text/javascript">dictInit();</script> 
<script>
var mark=<?php echo $flag?>;
if(mark)
  setInterval(function(){blink.color=blink.color=='red'?'white':'red'},500)
</script>
