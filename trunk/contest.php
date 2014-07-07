<?require_once("./include/db_info.inc.php");
	if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
	}
?>

<?require_once("./include/my_func.inc.php")?>
<?require_once("./include/const.inc.php")?>
<?
if (isset($_GET['cid'])){
	$cid=intval($_GET['cid']);
//	print $cid;
	require_once("contest-header.php");
	// check contest valid
	$sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' AND `defunct`='N' ";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	echo "<center>";
	if ($rows_cnt==0){
		mysql_free_result($result);
		echo "<h2>No Such Contest!</h2>";
		require_once("oj-footer.php");
		exit(0);
	}else{
		$row=mysql_fetch_object($result);
		$now=time();
		$start_time=strtotime($row->start_time);
		$end_time=strtotime($row->end_time);
                $description=$row->description;
		echo "<title>Contest - $row->title</title>";
		echo "<h3>Contest - $row->title</h3>
               <p>$description</p>
                Start Time: <font color=#993399>$row->start_time</font> ";
		echo "End Time: <font color=#993399>$row->end_time</font><br>";
		echo "Current Time: <font color=#993399><span id=nowdate >".date("Y-m-d H:i:s")."</span></font> Status:";
		if ($now>$end_time) echo "<font color=red>Ended</font>";
		else if ($now<$start_time) echo "<font color=red>Not Started</font>";
		else echo "<font color=red>Running</font>";
		if ($row->private=='0') echo "&nbsp;&nbsp;<font color=blue>Public</font>";
		else if($row->private=='1') echo "&nbsp;&nbsp;<font color=red>Private</font>";
		else echo "&nbsp;&nbsp;<font color=purple>Register Private</font>";
		
               $sql1="select user_id from privilege where rightstr='m$cid'";
               $result1=mysql_query($sql1) or die(mysql_error());
               $row1=mysql_fetch_object($result1);
               echo "    Manager: $row1->user_id";



                if (!isset($_SESSION['administrator']) && $now<$start_time){
			echo "</center>";
			require_once("oj-footer.php");
			exit(0);
		}
	}
	if (!$contest_ok){
		echo "<br><h1>Not Invited!</h1>";
		require_once("oj-footer.php");
		exit(1);
	}

             

//$sql="SELECT count(`num`) FROM `contest_problem` WHERE `contest_id`='$cid'";
//$result=mysql_query($sql);
//$row=mysql_fetch_array($result);
$sql="SELECT count(`num`) FROM `contest_problem` WHERE `contest_id`='$cid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$pid_cnt=intval($row[0]);
mysql_free_result($result);

$start_timeC=strftime("%Y-%m-%d %X",($start_time));

$sql="SELECT `result`,`num`,`language`  FROM `solution` WHERE `contest_id`='$cid' and num>=0 and in_date>'$start_timeC'"; 
$result=mysql_query($sql);
$R=array();
while ($row=mysql_fetch_object($result)){
        $res=intval($row->result)-4;
        if ($res<0) $res=8;
        $num=intval($row->num);
   $lag=intval($row->language);
        if(!isset($R[$num][$res]))
                $R[$num][$res]=1;
        else
                $R[$num][$res]++;
        if(!isset($R[$num][$lag+10]))
                $R[$num][$lag+10]=1;
        else
                $R[$num][$lag+10]++;
        if(!isset($R[$pid_cnt][$res]))
                $R[$pid_cnt][$res]=1;
        else
                $R[$pid_cnt][$res]++;
        if(!isset($R[$pid_cnt][$lag+10]))
                $R[$pid_cnt][$lag+10]=1;
        else
                $R[$pid_cnt][$lag+10]++;
        if(!isset($R[$num][8]))
                $R[$num][8]=1;
        else
                $R[$num][8]++;
        if(!isset($R[$pid_cnt][8]))
                $R[$pid_cnt][8]=1;
        else
                $R[$pid_cnt][8]++;
}
mysql_free_result($result);

         if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])){

           $sql="SELECT `problem`.`title` as `title`,`problem`.`problem_id` as `pid`
                FROM `contest_problem`,`problem`
                WHERE `contest_problem`.`problem_id`=`problem`.`problem_id`
                AND `contest_problem`.`contest_id`='$cid' ORDER BY `contest_problem`.`num`";

         }
        else
        {
	$sql="SELECT `problem`.`title` as `title`,`problem`.`problem_id` as `pid` FROM `contest_problem`,`problem`
		WHERE `contest_problem`.`problem_id`=`problem`.`problem_id` AND `problem`.`defunct`='N'
		AND `contest_problem`.`contest_id`='$cid' ORDER BY `contest_problem`.`num`";

        }
//	echo $cid;
//	echo "<br>";
//	echo $sql;
//	echo "<br>";
        
       
	$result=mysql_query($sql);
        $end_flag=false;
        $sql1="SELECT `private` FROM `contest` WHERE `contest_id`='$cid' AND  `end_time`<NOW()";
        $result1=mysql_query($sql1);
        $rows_cnt1=mysql_num_rows($result1);
        if ($rows_cnt1==1){
          $end_flag=true;
         }
	$cnt=0;

	echo "<table width=60% ><tr class=toprow><td width=5><td width=30%><b>Problem ID</b><td width=35%><b>Title</b><td width=30%><center><b>Ratio(AC/Submit)</b></center></tr>";
	while ($row=mysql_fetch_object($result)){
		if ($cnt&1) echo "<tr class=oddrow>";
		else echo "<tr class=evenrow>";
		echo "<td>";
		if (isset($_SESSION['user_id'])) echo check_ac($cid,$cnt);
	        if(!$end_flag){
        	echo "<td>Problem $PID[$cnt]
			<td><a href='problem.php?cid=$cid&pid=$cnt'>$row->title</a>
			";}
                else
                {
                     echo "<td>Problem $PID[$cnt]
                         <td><a href='problem.php?id=$row->pid'>$row->title</a>
                        ";}
	        echo "<td><center>";
                 if($R[$cnt][8]=="")
                      echo "0.00%";
                 else
                      echo sprintf( "%.02lf%%",100*$R[$cnt][0]/$R[$cnt][8]);
                 echo "(";
                if($R[$cnt][0]=="")
                    echo "0";
                else
                    echo $R[$cnt][0];
                echo "/";
               
                 if($R[$cnt][8]=="")
                    echo "0";
                else
                    echo $R[$cnt][8];
                 echo ")";
               $cnt++;
                
	}
	echo "</tr></table><br>";
	mysql_free_result($result);
	echo "[<a href='status.php?cid=$cid'>Status</a>]";
	echo "[<a href='contestrank.php?cid=$cid'>Standing</a>]";
	echo "[<a href='conteststatistics.php?cid=$cid'>Statistics</a>]";
	echo "</center>";
}else{
require_once("oj-header.php");
?>
<title>Contest List</title>
<?
 $sz=30;
 $sql="SELECT * FROM `contest` WHERE `defunct`='N'";
       if (isset($_GET['page']))
             $page=strval(intval($_GET['page']));
        else $page=0;
            $start=$page*$sz;
       if(isset($_GET['search'])){
            $search=trim(mysql_real_escape_string($_GET['search']));
            if($search!='')
               $sql=$sql." and title like '%$search%' ORDER BY `contest_id` DESC  ";
        }else
               $sql=$sql." ORDER BY `contest_id` DESC limit $start,$sz";
$result=mysql_query($sql);
$rows_cnt = mysql_num_rows($result);
$color=false;
echo "<center><h2>Contest List</h2><form>ServerTime:<span id=nowdate></span> <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='search'><input type='submit' value='$MSG_SEARCH'</td></form> ";
echo "<table width=85%><tr class=toprow align=center>
<td width=5%>ID
<td width=55%>Contest Name
<td width=18%>Start time
<td width=8%>Status
<td width=30%>Type
</tr>";
while ($row=mysql_fetch_object($result)){
	if ($color) echo "<tr align=center class=oddrow>";
	else echo "<tr align=center class=evenrow>";
	echo "<td>$row->contest_id</td>";
	echo "<td><div style=\"float:left;\"><a href='contest.php?cid=$row->contest_id'>$row->title</a></div>";

	$start_time=strtotime($row->start_time);
	$end_time=strtotime($row->end_time);
	$reg_start_time=strtotime($row->reg_start_time);
	$reg_end_time=strtotime($row->reg_end_time);
	$now=time();
	//0 public 1 private 2 register
	if($row->private=='2'){
		if (($now>=$reg_start_time)&& ($now<=$reg_end_time))
		{
			$query_cnt=0;
			if(isset($_SESSION['user_id']))
			{
				$user_id=$_SESSION['user_id'];
				$sql2="SELECT * FROM `contestreg` WHERE `contestreg`.`user_id`='".$user_id."' AND `contestreg`.`contest_id`='$row->contest_id'";
				$result2=mysql_query($sql2);
				if($result2)
					$query_cnt=mysql_num_rows($result2);
				else 
					$query_cnt=0;
				if($query_cnt>0)
					$row2=mysql_fetch_array($result2);
				mysql_free_result($result2);
			}
			//do not register
			if((!isset($_SESSION['user_id']))||($query_cnt==0)){
			echo "<div style=\"float:right;text-align:right;font-size:90%\">
			<a href='cstregisterpage.php?cid=$row->contest_id' title='Register\nStart time:$row->reg_start_time\nEnd   time:$row->reg_end_time'>
			<font color=white style='background-color:rgb(15,209,22)'><u><strong>$MSG_CSTREGISTER >></strong></u></font></a>&nbsp;&nbsp;</div>";
			}
			else{
			if($row2['ispending']!=1)
			echo "<div style=\"float:right;text-align:right;font-size:90%\">
			<a href='updateregisterpage.php?cid=$row->contest_id' title='Register\nStart time:$row->reg_start_time\nEnd   time:$row->reg_end_time'>
			<font color=white style='background-color:rgb(15,209,22)'><u><strong>$MSG_MODIFYREGISTER</strong></u></font></a>&nbsp;&nbsp;</div>";
			else
				echo "<div style=\"float:right;text-align:right;font-size:90%\">
				<a href='contestrank.php?cid=$row->contest_id'>
				<span style='color:blue'><u>Standings</u></span></a>&nbsp;&nbsp;<div>";
			}
			
		}
		else{
		echo "<div style=\"float:right;text-align:right;font-size:90%\">
		<a href='contestrank.php?cid=$row->contest_id'>
		<span style='color:blue'><u>Standings</u></span></a>&nbsp;&nbsp;<div>";
		}
	}
	else{
	echo "<div style=\"float:right;text-align:right;font-size:90%\">
	<a href='contestrank.php?cid=$row->contest_id'>
	<span style='color:blue'><u>Standings</u></span></a>&nbsp;&nbsp;<div>";
	}
        echo "<td>$row->start_time";
	// past
	if ($now>$end_time) echo "<td><font color=green>Ended</font>";
	// pending
	else if ($now<$start_time) echo "<td><font color=blue>Pending</font>";
	// running
	else echo "<td><font color=red> Running </font>";
	$private=intval($row->private);
	if ($private==0) echo "<td><font color=blue>Public</font>";
	else if($private==1) echo "<td><font color=red>Private</font>";
	else {
		if ($now>$reg_end_time) echo "<td><font color=gray title='Registration\nStart time:$row->reg_start_time\nEnd   time:$row->reg_end_time'>Registration Ended</font>";
		else if ($now<$reg_start_time) echo "<td><font color=blue title='Registration\nStart time:$row->reg_start_time\nEnd   time:$row->reg_end_time'>Registration Pending</font>";
		else echo "<td><font color=red title='Registration\nStart time:$row->reg_start_time\nEnd   time:$row->reg_end_time'>Registering</font>";
		//echo "<br><font size=1px>Start:$row->reg_start_time";
		//echo "<br>End:$row->reg_end_time";
		$tmpsql="SELECT count(`user_id`) FROM `contestreg` WHERE `contestreg`.`contest_id`='$row->contest_id'";
		$tmpresult=mysql_query($tmpsql);
		$tmprow=mysql_fetch_array($tmpresult);
		$cntpeople=intval($tmprow[0]);
		mysql_free_result($tmpresult);
		echo "<td><a href='showallregister.php?cid=$row->contest_id'><img src='./image/user.png'>x$cntpeople";
	}
	echo "</tr>";
	$color=!$color;
}
echo "</table></center>";
mysql_free_result($result);
if (!isset($_GET['search'])){

echo "<center><a href='contest.php'>[Top]</a>";
if($page>0){
        $page--;
        echo "&nbsp;&nbsp;<a href='contest.php?page=$page'>[Previous]</a>";
        $page++;
}
else
{
echo "&nbsp;&nbsp;<a href='contest.php'>[Previous]</a>";

}
if($rows_cnt==$sz){
        $page++;
        echo "&nbsp;&nbsp;<a href='contest.php?page=$page'>[Next]</a>";
        $page--;
}
else
       echo "&nbsp;&nbsp;<a href='contest.php?page=$page'>[Next]</a>";
echo "</center>";

}
?>

<?
}
require_once("oj-footer.php");
?>
<script>
var diff=new Date("<?=date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
//alert(diff);
function clock()
    {
      var x,h,m,s,n,xingqi,y,mon,d;
      var x = new Date(new Date().getTime()+diff);
      y = x.getYear()+1900;
       if (y>3000) y-=1900;
      mon = x.getMonth()+1;
      d = x.getDate();
      xingqi = x.getDay();
      h=x.getHours();
      m=x.getMinutes();
      s=x.getSeconds();
  
      n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
      //alert(n);
      document.getElementById('nowdate').innerHTML=n;
      setTimeout("clock()",1000);
    } 
    clock();
</script>