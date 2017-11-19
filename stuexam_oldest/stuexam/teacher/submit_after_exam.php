<?php
	@session_start();
	require_once("myinc.inc.php");
	checkuserid();
	checkAdmin(2);
?>
<?php
	if(isset($_GET['eid'])&&isset($_GET['users']))
	{
		if(isset($_GET['rjd']))
			$rejudge=1;
		else
			$rejudge=0;
		$eid=intval(trim($_GET['eid']));
		$users=trim($_GET['users']);
		$users=mysql_real_escape_string($users);
		$sql ="SELECT `start_time`,`end_time`,`creator` FROM `exam` WHERE `exam_id`='$eid'";
		$row=fetchOne($sql);
		if(!$row)
		{
			echo "No Such Exam";
			exit(0);
		}
		$start_time=$row['start_time'];
		$starttimeC=strtotime($start_time);
		$end_time=$row['end_time'];
		$endtimeC=strtotime($end_time);
		$creator = $row['creator'];
		$start_timeC=strftime("%Y-%m-%d %X",($starttimeC));
		$end_timeC=strftime("%Y-%m-%d %X",($endtimeC));
		$now=time();

		if($rejudge==0)//if not rejudge
		{
			$sql="SELECT COUNT(*) as `numc` FROM `ex_privilege` WHERE `user_id`='".$users."' AND `rightstr`='e$eid'";
			$numr=fetchOne($sql);
			$cnt1=$numr['numc'];
			if($cnt1==0){
				alertmsg("Student ID is wrong!","exam_user_score.php?eid={$eid}",0);
			}
		}
		if($now<$starttimeC)
		{
			echo "<h1>Exam Not Start</h1>";
			exit(0);
		}

		$sql = "SELECT COUNT(*) as `numc` FROM `ex_student` WHERE `exam_id`='$eid' AND `user_id`='".$users."'";
		$numr=fetchOne($sql);
		$mark=$numr['numc'];

		if(checkAdmin(4,$creator))
		{
			alertmsg("You have no privilege to do it!","exam_user_score.php?eid={$eid}",0);
		}
		else
		{
			//test paper
			require_once("../func.php");
			rejudgepaper($users,$eid,$start_timeC,$end_timeC,$mark);
			//
			echo "<script language='javascript'>\n";
        	echo "location.href='./exam_user_score.php?eid=$eid'";
        	echo "</script>";
		}
	}
	else{
		alertmsg("Invaild path");
	}
?>
