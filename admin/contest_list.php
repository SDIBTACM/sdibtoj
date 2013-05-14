<?php require("admin-header.php");
if (!(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator']))){
        echo "<a href='../loginpage.php'>Please Login First!</a>";
        exit(1);
}

//page
       $sz=30;
       if (isset($_GET['page']))
             $page=strval(intval($_GET['page']));
        else $page=0;
             $start=$page*$sz;




echo "<title>Contest List</title>";
echo "<center><h2>Contest List</h2></center>";
require_once("../include/set_get_key.php");
$sql="select `contest_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `contest` order by `contest_id` desc limit $start,$sz";
$result=mysql_query($sql) or die(mysql_error());
$rows_cnt = mysql_num_rows($result);
echo "<center><table width=90% border=1>";
echo "<tr><td>ContestID<td>Title<td>StartTime<td>EndTime<td>Private<td>Status<td>Edit<td>Copy<td>Manager<td>Export<td>logs"; 
echo "</tr>";
for (;$row=mysql_fetch_object($result);){
	echo "<tr>";
	echo "<td>".$row->contest_id;
	echo "<td><a href='../contest.php?cid=$row->contest_id'>".$row->title."</a>";
	echo "<td>".$row->start_time;
	echo "<td>".$row->end_time;
	$cid=$row->contest_id;
	if(isset($_SESSION['administrator'])||isset($_SESSION["m$cid"])){
		echo "<td><a href=contest_pr_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->private=="0"?"Public->Private":"Private->Public")."</a>";
		echo "<td><a href=contest_df_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"<span class=green>Available</span>":"<span class=red>Reserved</span>")."</a>";
		echo "<td><a href=contest_edit.php?cid=$row->contest_id>Edit</a>";
		echo "<td><a href=contest_add.php?cid=$row->contest_id>Copy</a>";
                
               $sql1="select user_id from privilege where rightstr='m$row->contest_id'";
               $result1=mysql_query($sql1) or die(mysql_error());
               $row1=mysql_fetch_object($result1);
               echo "<td><a href=../userinfo.php?user=$row1->user_id>$row1->user_id";


		if(isset($_SESSION['administrator'])){
			echo "<td><a href=\"problem_export_xml.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">Export</a>";
		}else{
		echo "<td>";
		}
	  echo "<td> <a href=\"../export_contest_code.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">Logs</a>";
	}else{
	//	echo "<td colspan=4 align=right><a href=contest_add.php?cid=$row->contest_id>Copy</a><td>";
		
	}
		
	echo "</tr>";
}
echo "</table></center>";

//top,next,previous
echo "<center><a href='contest_list.php'>[Top]</a>";
if($page>0){
        $page--;
        echo "&nbsp;&nbsp;<a href='contest_list.php?page=$page'>[Previous]</a>";
        $page++;
}
else
{
echo "&nbsp;&nbsp;<a href='contest_list.php'>[Previous]</a>";

}
if($rows_cnt==$sz){
        $page++;
        echo "&nbsp;&nbsp;<a href='contest_list.php?page=$page'>[Next]</a>";
        $page--;
}
else
    echo "&nbsp;&nbsp;<a href='contest_list.php?page=$page'>[Next]</a>";
echo "</center>";





require("../oj-footer.php");
?>

