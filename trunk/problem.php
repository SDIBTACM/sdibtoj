<?
    require_once("./include/db_info.inc.php");

	if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
	}
?>

<?
$pr_flag=false;
$co_flag=false;
if (isset($_GET['id'])){
	// practice
	$id=intval($_GET['id']);
	require_once("oj-header.php");
	if (!isset($_SESSION['administrator'])){
            if(isset($_SESSION['user_id']))
		$sql="SELECT * FROM `problem` WHERE `problem_id`=$id AND (`defunct`='N' or (`author`='".$_SESSION['user_id']."'". ")) AND `problem_id` NOT IN (
				SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
						SELECT `contest_id` FROM `contest` WHERE `end_time`>NOW()))
                                ";
            else
                 $sql="SELECT * FROM `problem` WHERE `problem_id`=$id AND `defunct`='N'  AND `problem_id` NOT IN (
                                SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
                                                SELECT `contest_id` FROM `contest` WHERE `end_time`>NOW()))
                                ";

          }
	else
		$sql="SELECT * FROM `problem` WHERE `problem_id`=$id";

	$pr_flag=true;
}else if (isset($_GET['cid']) && isset($_GET['pid'])){
	// contest
	$cid=intval($_GET['cid']);
	$pid=intval($_GET['pid']);

	if (!isset($_SESSION['administrator']))
		$sql="SELECT langmask FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid AND `start_time`<NOW()";
	else
		$sql="SELECT langmask FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
        $ok_cnt=$rows_cnt==1;		
	$row=mysql_fetch_row($result);
	$langmask=$row[0];
	mysql_free_result($result);
	if ($ok_cnt!=1){
		// not started
		echo "No such Contest or Contest not Start!";
		require_once("oj-footer.php");
		exit(0);
	}else{
		// started
		$sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
			SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid AND `num`=$pid
			)";
	}
	// public
	require_once("contest-header.php");
	if (!$contest_ok){
		echo "<center><h1>Not Invited!</h1></center>";
		require_once("oj-footer.php");
		exit(1);
	}
	$co_flag=true;
}else{
	require_once("oj-header.php");
	echo "<title>$MSG_NO_SUCH_PROBLEM</title><h2>$MSG_NO_SUCH_PROBLEM</h2>";
	require_once("oj-footer.php");
	exit(0);
}
$result=mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($result)!=1){
   if(isset($_GET['id'])){
      $id=intval($_GET['id']);
	   mysql_free_result($result);
	   $sql="SELECT  contest.`contest_id` , contest.`title`,contest_problem.num FROM `contest_problem`,`contest` WHERE contest.contest_id=contest_problem.contest_id and `problem_id`=$id and defunct='N' and NOW()< contest.end_time ORDER BY `num`";
	   //echo $sql;
           $result=mysql_query($sql);
	   if($i=mysql_num_rows($result)){
	      echo "This problem is in Contest(s) below:<br>";
		   for (;$i>0;$i--){
				$row=mysql_fetch_row($result);
				echo "<a href=problem.php?cid=$row[0]&pid=$row[2]>Contest $row[0]:$row[1]</a><br>";
		//	echo "<title>$MSG_NO_SUCH_PROBLEM!</title>";
                  //      echo "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
	
			}
		}else{
			echo "<title>$MSG_NO_SUCH_PROBLEM!</title>";
			echo "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
		}
   }else{
		echo "<title>$MSG_NO_SUCH_PROBLEM!</title>";
		echo "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
	}
}else{
	$row=mysql_fetch_object($result);
	if ($pr_flag){
		echo "<title>$MSG_PROBLEM $row->problem_id. -- $row->title</title>";
		echo "<center><h2>$row->title</h2>";
	}else{
		$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		echo "<title>$MSG_PROBLEM $PID[$pid]: $row->title </title>";
		echo "<center><h2>$MSG_PROBLEM $PID[$pid]: $row->title</h2>";
	}
	echo "<span class=green>$MSG_Time_Limit: </span>$row->time_limit Sec&nbsp;&nbsp;";
	echo "<span class=green>$MSG_Memory_Limit: </span>".$row->memory_limit." MB";
	if ($row->spj) echo "&nbsp;&nbsp;<span class=red>Special Judge</span>";
	echo "<br><span class=green>$MSG_SUBMIT: </span>".$row->submit."&nbsp;&nbsp;";
	echo "<span class=green>$MSG_SOVLED: </span>".$row->accepted."<br>"; 
	
	if ($pr_flag){
		echo "[<a href='submitpage.php?id=$id'>$MSG_SUBMIT</a>]";
	}else{
		echo "[<a href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>$MSG_SUBMIT</a>]";
	}
	if($pr_flag){
                 echo "[<a href='problemstatus.php?id=".$row->problem_id."'>$MSG_STATUS</a>]";
        }else{
                 echo  "[<a href='status.php?cid=$cid&problem_id=$PID[$pid]'>$MSG_STATUS</a>]";
        }

       if($pr_flag){	
             echo "[<a href='./discuss.php?pid=".$row->problem_id."'>$MSG_BBS</a>]";
	}else{
             echo "[<a href='./discuss.php?pid=".$row->problem_id."&cid=$cid'>$MSG_BBS</a>]";
           }
	echo "</center>";
	
	echo "<h2>$MSG_Description</h2><p>".$row->description."</p>";
	echo "<h2>$MSG_Input</h2><p>".$row->input."</p>";
	echo "<h2>$MSG_Output</h2><p>".$row->output."</p>";
	echo "<h2>$MSG_Sample_Input</h2><pre>".htmlspecialchars($row->sample_input)."</pre>";
	echo "<h2>$MSG_Sample_Output</h2><pre>".htmlspecialchars($row->sample_output)."</pre>";
	if ($pr_flag||true) echo "<h2>$MSG_HINT</h2><p>".nl2br($row->hint)."</p>";
	if ($pr_flag) echo "<h2>$MSG_Source</h2><p><a href='problemset.php?search=$row->source'>".nl2br($row->source)."</a></p>";
	echo "<center>";
	if ($pr_flag){
		echo "[<a href='submitpage.php?id=$id'>$MSG_SUBMIT</a>]";
	}else{
		echo "[<a href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>$MSG_SUBMIT</a>]";
	}
	echo "[<a href='problemstatus.php?id=".$row->problem_id."'>$MSG_STATUS</a>]";

        if($pr_flag){
             echo "[<a href='./discuss.php?pid=".$row->problem_id."'>$MSG_BBS</a>]";
        }else{
             echo "[<a href='./discuss.php?pid=".$row->problem_id."&cid=$cid'>$MSG_BBS</a>]";
           }

	echo "</center>";
}
mysql_free_result($result);
?>
<?require_once("oj-footer.php")?>
