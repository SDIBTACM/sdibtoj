<?php
	$debug = true;
	require_once('./oj-header.php');
	require_once('./include/db_info.inc.php');
	require_once('./include/iplocation.php');
      if($OJ_ONLINE){
        	$users = $on->getAll();
         	$ip = new IpLocation();
      }     
?>

<?php if($OJ_ONLINE) {?>
<h3>current online user: <?=$on->get_num()?></h3>
<table style="margin:auto;width:98%">
<thead>
<tr><th style="width: 50px">ip</th><th>uri</th><th>refer</th><th style="width:100px">stay time</th><th>user agent</th></tr>
</thead>
<tbody>
<?php foreach($users as $u):
 if(is_object($u)){
 ?>
<tr><td class="ip">
<?php $l = $ip->getlocation($u->ip);
   
	echo $u->ip.'<br />';
	if(strlen(trim($l['area']))==0)
		echo $l['country'];
	else
		echo $l['area'].'@'.$l['country'];
	?></td><td><?=$u->uri?></td><td><?=$u->refer?></td>
<td class="time"><?=sprintf("%dmin %dsec",($u->lastmove-$u->firsttime)/60,($u->lastmove-$u->firsttime) % 60)?></td><td><?=$u->ua?></td></tr>
<?php 
}
endforeach;?>
</tbody>
</table>
<?php }
?>
<?
if (isset($_SESSION['administrator'])){

echo "<center>";
echo "<td width='100%' colspan='5'><form>IP(支持模糊查找)<input type='text' name='search'><input type='submit' value='$MSG_SEARCH' ></form></td></tr>";
if(isset($_GET['search'])){

    $sql="SELECT * FROM `loginlog`";
    $search=trim(mysql_real_escape_string($_GET['search']));
    if ($search!='')
    	$sql=$sql." WHERE ip like '%$search%' ";
    // else
      //  $sql=$sql." where user_id<> 'xiao'";
    $sql=$sql."  order by `time` desc LIMIT 0,50";

$result=mysql_query($sql) or die(mysql_error());
echo "<table border=1>";
echo "<tr align=center><td>UserID<td>Password<td>IP<td>Time</tr>";
for (;$row=mysql_fetch_row($result);){
        echo "<tr align=center>";
        echo "<td><a href='userinfo.php?user=".$row[0]."'>".$row[0]."</a>";
        echo "<td>".$row[1];
        echo "<td>".$row[2];
        echo "<td>".$row[3];
        echo "</tr>";
}
echo "</table>";

echo "</center>";
mysql_free_result($result);
}

}
?>
<?
if (isset($_SESSION['administrator'])){

echo "<center>";
echo "<td width='100%' colspan='5'><form>UserName(支持模糊查找)<input type='text' name='diffip'><input type='submit' value='DiffIp' ></form></td></tr>";
if(isset($_GET['diffip'])){

    $sql="SELECT DISTINCT(user_id) FROM `loginlog`";
    $search1=trim(mysql_real_escape_string($_GET['diffip']));
    if ($search1!='')
    	$sql=$sql." WHERE user_id like '%$search1%' ";
    $sql=$sql."  order by `time` desc LIMIT 0,50";

$result=mysql_query($sql) or die(mysql_error());
echo "<table border=1>";
echo "<tr align=center><td>UserID<td>Password<td>IP<td>Time</tr>";
for (;$row=mysql_fetch_row($result);){
    
    $sql1="SELECT * FROM `loginlog` where user_id=\"".$row[0]."\"  order by `time` desc LIMIT 0,2";
  
  //  echo $sql1;echo "<br>";
    $result1=mysql_query($sql1) or die(mysql_error());
    $rowa=mysql_fetch_row($result1);
    $a=$rowa[2];
    $temp=$rowa[3];
    $data1=strtotime($rowa[3]);
    $rowa=mysql_fetch_row($result1);
    $b=$rowa[2];
     $data2=strtotime($rowa[3]);

   //echo date('Y-m-d',$data1);
  //  $rowa=mysql_fetch_row($result1);
   // $c=$rowa[2];

    if($a<>$b &&  date('Y-m-d',$data1)==date('Y-m-d',$data2) )
    {
        echo "<tr align=center>";
        echo "<td><a href='userinfo.php?user=".$rowa[0]."'>".$rowa[0]."</a>";
        echo "<td>".$rowa[1];
        echo "<td>".$a;
        echo "<td>".$temp;
        echo "</tr>";
    }
}
echo "</table>";

echo "</center>";
mysql_free_result($result);
}

}
?>


<?php require_once('oj-footer.php')?>
