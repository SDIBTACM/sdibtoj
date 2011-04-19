<?
require_once("oj-header.php");
require_once("./include/db_info.inc.php");
echo "<title>Welcome To Online Judge</title>";
$sql=	"SELECT * "
	."FROM `news` "
	."WHERE `defunct`!='Y'"
	."ORDER BY `importance` ASC,`time` DESC "
	."LIMIT 5";
$result=mysql_query($sql);//mysql_escape_string($sql));
if (!$result){
echo "<h3>No News Now!</h3>";
echo mysql_error();
}else{
	
	echo "<table width=96%>";
	echo "<tr> <td width=20%><td><font color=red><h3></h3></font></tr>";
       
	echo "<tr></tr>";
while ($row=mysql_fetch_object($result)){
        echo "<tr><td><td><font color=red><b>".$row->title."</font></tr>";
                        echo "<tr><td><td>".$row->content."</tr>";

                }
	
	echo "<tr></tr>";
	echo "<tr></tr>";
	echo "<tr><td><td><font color=red>Problem分类：</font></tr>";
	echo "<tr><td><td>初学者(1000-1012,1552,1563)  C语言(1500-1551) 二级C(1933-1949)　简单题(1800-1931) 《ACM程序设计》(1134-1192) 《挑战编程》(1013-1125)  算法(1288-1391) USACO Training(2300-2393)</tr>";
	echo "<tr><td><td><font color=red>算法分类：</font></tr>";
       echo "<tr><td><td>第一章(1288-1292) 第二章(1293-1306) 第三章(1307-1325) 第四章(1326-1347) 第五章(1348-1373) 第六章(1374-1397)	</tr>";	
	echo "<tr> <td><td><font color=red>首届ACM程序设计大赛　<a href=\"http://acm.sdibt.edu.cn/download/1ACM.zip\">解题报告</a></font></tr>";
    echo  " <tr><td><td><font color=red> OJ使用帮助&nbsp;&nbsp;<a href=\"http://acm.sdibt.edu.cn/download/SDIBTOJ_HELP.rar\">下载</a></font></tr>";

      echo "<tr><td><td><font color=red>如果忘记密码，请将注册的用户名发送到  sdibtacm@126.com 重置。 </font>  </tr>";
 
       echo "<tr><td> <td><font><h3>山商ACM QQ群76827278(已满)       125696898 </h3></font></tr>";        
                       
		echo "</table>";
	}
?>
<?php if(function_exists('apc_cache_info')): ?>
<?php $_apc_cache_info = apc_cache_info(); ?>
<div style="text-align:center">
<div style="margin: auto; width:400px; text-align:left">
<h4>Alternative PHP Cache:<strong>ACTIVE</strong></h4>
<strong>Performace Data<strong>
<ul id="apc">
	<li><span>Hits: </span><?=$_apc_cache_info['num_hits']?></li>
	<li><span>Misses: </span><?=$_apc_cache_info['num_misses']?></li>
	<li><span>Entries: </span><?=$_apc_cache_info['num_entries']?></li>
	<li><span>Inserts: </span><?=$_apc_cache_info['num_inserts']?></li>
	<li><span>Cached Files: </span><?=$_apc_cache_info['mem_size']/1024?>KB</li>
<li><span>Start_time: </span><?=gmdate("Y-m-d G:i:s",$_apc_cache_info['start_time']+28800)?></li>
</ul>
</div>
</div>
<?php endif;?>

<?php require('oj-footer.php');?>
