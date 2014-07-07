<?php
	$now = time ();
	$cid=intval($_GET['cid']);
	$file = "cache/contestrank$cid.html";
	if (file_exists ( $file ))
		$last = filemtime ( $file );
	else
		$last =0;
	if ($now - $last < 10) {
		//header ( "Location: $file" );
		include ($file);
		exit ();
	} else {
		ob_start ();
		
		?>
<?
require_once("./include/db_info.inc.php");

if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
}
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
class TM{
	var $solved=0;
	var $time=0;
	var $p_wa_num;
	var $p_ac_sec;
	var $user_id;
        var $nick;
	var $realname;
	function TM(){
		$this->solved=0;
		$this->time=0;
		$this->p_wa_num=array(0);
		$this->p_ac_sec=array(0);
	}
	function Add($pid,$sec,$res){
		//echo "Add $pid $sec $res<br>";
	if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0)
                return;
        if ($res!=4){
                if(isset($this->p_wa_num[$pid])){
                  	$this->p_wa_num[$pid]++; 
                 }else{
                        $this->p_wa_num[$pid]=1;
                  }    	
                  }else{
			$this->p_ac_sec[$pid]=$sec;
			$this->solved++;
			$this->time+=$sec+$this->p_wa_num[$pid]*1200;
//			echo "Time:".$this->time."<br>";
//			echo "Solved:".$this->solved."<br>";
		}
	}
}

function s_cmp($A,$B){
//	echo "Cmp....<br>";
	if ($A->solved!=$B->solved) return $A->solved<$B->solved;
	else return $A->time>$B->time;
}

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);
require_once("contest-header.php");
$sql="SELECT `start_time`,`title`,`end_time`,`private` FROM `contest` WHERE `contest_id`='$cid'";
$result=mysql_query($sql) or die(mysql_error());
if($result)
$rows_cnt=mysql_num_rows($result);
else
$rows_cnt=0;
$start_time=0;
$end_time=0;
if ($rows_cnt>0){
	$row=mysql_fetch_array($result);
	$start_time=strtotime($row[0]);
	$end_time=strtotime($row['end_time']);
	$title=$row[1];
	$isregister=intval($row['private']);
}
mysql_free_result($result);
if ($start_time==0){
	echo "No Such Contest";
	require_once("oj-footer.php");
	exit(0);
}

if ($start_time>time()){
	echo "Contest Not Started!";
	require_once("oj-footer.php");
	exit(0);
}
if(!isset($OJ_RANK_LOCK_PERCENT)) $OJ_RANK_LOCK_PERCENT=0;
$lock=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;
$lock_sql="";
$islock=0;//only to show the clock or not
if(time()>$lock&&time()<$end_time){
	if(!(isset($_SESSION["m$cid"])||isset($_SESSION['administrator'])))//show the rank or not
		$lock_sql="and in_date<'".date("Y-m-d H:i:s",$lock)."'";
	$islock=1;//to show the clock
	//echo $lock;
	//echo $lock_sql;
}
else
	$islock=0;
$sql="SELECT count(1) FROM `contest_problem` WHERE `contest_id`='$cid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$pid_cnt=intval($row[0]);
mysql_free_result($result);
//echo $start_time;

$start_timeC=strftime("%Y-%m-%d %X",($start_time));
if($isregister!=2)//only register' have realname
{
$sql="SELECT 
	users.user_id,users.nick,solution.result,solution.num,solution.in_date
		FROM 
			(select * from solution where solution.contest_id='$cid' and in_date>'$start_timeC' $lock_sql) solution 
		left join users 
		on users.user_id=solution.user_id 
	ORDER BY users.user_id,in_date";
}
else{
$sql="SELECT stuinfo.user_id,stuinfo.nick,stuinfo.result,stuinfo.num,stuinfo.in_date,contestreg.sturealname FROM
	( SELECT users.user_id,users.nick,solution.result,solution.num,solution.in_date from users,solution where solution.contest_id='$cid' and in_date>'$start_timeC' $lock_sql and solution.user_id=users.user_id )stuinfo 
	left join contestreg on contestreg.user_id=stuinfo.user_id and contestreg.contest_id=stuinfo.contest_id ORDER BY stuinfo.user_id,in_date";
}
//echo $sql;
$result=mysql_query($sql);
$user_cnt=0;
$user_name='';
$U=array();
while ($row=mysql_fetch_object($result)){
	$n_user=$row->user_id;
	if (strcmp($user_name,$n_user)){
		$user_cnt++;
		$U[$user_cnt]=new TM();
		$U[$user_cnt]->user_id=$row->user_id;
                $U[$user_cnt]->nick=$row->nick;
		if($isregister==2) $U[$user_cnt]->realname=$row->sturealname;
		$user_name=$n_user;
	}
	//if(time()<$end_time&&$lock<strtotime($row->in_date))
	//$U[$user_cnt]->Add($row->num,strtotime($row->in_date)-$start_time,0);
//	if(strtotime($row->in_date)-$start_time>=0)
	$U[$user_cnt]->Add($row->num,strtotime($row->in_date)-$start_time,intval($row->result));
}
mysql_free_result($result);
usort($U,"s_cmp");


////firstblood
$first_blood=array();
for($i=0;$i<$pid_cnt;$i++){
   $sql="select user_id from solution where contest_id=$cid and result=4 and num=$i and in_date>='$start_timeC' order by in_date limit 1";
   $result=mysql_query($sql);
   $row_cnt=mysql_num_rows($result);
   $row=mysql_fetch_array($result);
   if($row_cnt==1){
      $first_blood[$i]=$row['user_id'];
   }else{
      $first_blood[$i]="";
   }

}

$rank=1;
echo "<style> td{font-size:14} </style>";
echo "<title>Contest RankList -- $title</title>";
if($islock){
	$locktime=date("Y-m-d H:i:s",$lock);
	echo "<h1 align=center>已封榜:rank最后更新于$locktime</h1>";
	echo "<link rel=\"stylesheet\" href=\"mergely/jquery.countdown.css\" />";
	echo "<script src=\"mergely/jquery-1.4.1.js\"></script>";
	echo "<script src=\"mergely/jquery.countdown.js\"></script>";
	echo "<div id=\"countdown\"></div>";
}
echo "<center><h3>Contest RankList -- $title</h3>";
if(time()>$end_time) 
	echo "<a href=contestrank.xls.php?cid=$cid>Download</a></center>";
echo "<table id=rank><tr class=toprow align=center><td width=8%><td width=3%>Rank<td width=10%>User<td width=10%>Nick<td width=3%>Solved<td width=5%>Penalty";
for ($i=0;$i<$pid_cnt;$i++)
	echo "<td><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a>";
echo "</tr>\n";
for ($i=0;$i<$user_cnt;$i++){
	if ($i&1) echo "<tr class=oddrow align=center style=height:38px>\n";
	else echo "<tr class=evenrow align=center style=height:38px>\n";
	echo "<td><td>";
         $uuid=$U[$i]->user_id;
         if($uuid[0]!="*")
              echo $rank++;
         else
             echo "*";
       

 
	$usolved=$U[$i]->solved;
	echo "<td><a href=userinfo.php?user=$uuid>$uuid</a>";
	if($isregister==2)
		echo "<td><a href=userinfo.php?user=$uuid>".$U[$i]->realname."</a>";
	else
		echo "<td><a href=userinfo.php?user=$uuid>".$U[$i]->nick."</a>";
	echo "<td><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";
	echo "<td>".sec2str($U[$i]->time);
	for ($j=0;$j<$pid_cnt;$j++){
		//echo "<td> ";
 	 if(isset($U[$i])){
        	if (isset($U[$i]->p_ac_sec[$j])&& $U[$i]->p_ac_sec[$j]>0)
                       {
                          /////////////first blood color blue  
                       if($uuid==$first_blood[$j])
                               echo "<td  bgcolor='#008800'><font color='#EEEEEE'>";
                        else
			      echo "<td  bgcolor='a9f5af'><font color='#0d0d0d'>";
                        echo sec2str($U[$i]->p_ac_sec[$j]);
                        if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0)
                          {
                           echo "<br>";
                           echo "(-".$U[$i]->p_wa_num[$j].")";
                         }
                        echo "</font></td>";
                       }
                 else  if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0)
                        {
			echo "<td  bgcolor='#D01F3C'><font color='#EEEEEE'>";
			echo "(-".$U[$i]->p_wa_num[$j].")";
                        echo "</font></td>";
                        }
               else 
                       echo "<td>";
              }
                 
	}
	echo "</tr>\n";
}
echo "</table>";

?>
<script>
  function getTotal(rows){
    var total=0;
    for(var i=0;i<rows.length&&total==0;i++){
      try{
         total=parseInt(rows[rows.length-i].cells[1].innerHTML);
          if(isNaN(total)) total=0;
      }catch(e){
     
      }
    }
    return total;
 
  }
function metal(){
  var tb=window.document.getElementById('rank');
  var rows=tb.rows;
  try{
  var total=getTotal(rows);
  //alert(total);
          for(var i=1;i<rows.length;i++){
                var cell=rows[i].cells[1];
                var cell1=rows[i].cells[0];
      var acc=rows[i].cells[4];
      var ac=parseInt(acc.innerText);
      if (isNaN(ac)) ac=parseInt(acc.textContent); 
                if(cell.innerHTML!="*"&&ac>0){ 
                     var r=parseInt(cell.innerHTML);
                      cell1.style.backgroundRepeat = "no-repeat";
                      cell1.style.backgroundPosition = "center";
                     if(r==1){
                    //   cell.innerHTML="Winner";
                       cell.style.cssText="background-color:gold;";
                       cell1.style.backgroundImage="url(./image/cup.gif)";
                     }
                     if(r>1&&r<=total*.1+1){
                     //  if(r>1&&r<=total*.1+1)
                          cell.style.cssText="background-color:gold";
                          cell1.style.backgroundImage="url(./image/gold.gif)";
                      }     
                     if(r>total*.1+1&&r<=total*.3+1){
                     // if(r>total*.1+1&&r<=total*.3+1)
                        cell.style.cssText="background-color:silver";
                        cell1.style.backgroundImage="url(./image/silver.gif)";

                     }
                     if(r>total*.3+1&&r<=total*.6+1){
                    // if(r>total*.3+1&&r<=total*.6+1)
                        cell.style.cssText="background-color:saddlebrown;color:white";
                        cell1.style.backgroundImage="url(./image/bronze.gif)";

                     }
                     if(r>total*.6&&ac>0){
                     // if(r>total*.6&&ac>0)
                        cell.style.cssText="background-color:steelblue;color:white";
                         cell1.style.backgroundImage="url(./image/yousheng.gif)";
                    }
                }
          }
  }catch(e){
     //alert(e);
  }
}
metal();

function showlocktime()
{
	var endtime=<?echo $end_time?>;
	var islock=<?echo $islock?>;
	endtime=endtime*1000;
	if(islock==1){	
		$('#countdown').countdown({
			timestamp:endtime,
		});
	}
}
showlocktime();

</script>


<?require_once("oj-footer.php")?>
<?php
		@mkdir("cache");
		$conntent = ob_get_contents ();
		$fp = fopen ( $file, "w" );
		fputs ( $fp, $conntent );
		fclose ( $fp );
	}
	
	?>