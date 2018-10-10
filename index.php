<?
require_once("oj-header.php");
require_once("./include/db_info.inc.php");
echo "<title>山东工商学院ACM</title>";
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
        mysql_free_result($result);
	echo "<tr></tr>";
	echo "<tr></tr>";
	echo "<tr><td><td><font color=red>Problem分类：</font></tr>";
	echo "<tr><td><td>初学者(1000-1012)  C语言(1500-1551)简单题(1800-1931)  数据结构(2600-2755) 算法(1288-1391) 《ACM程序设计》(1134-1192) 《挑战编程》(1013-1125)  USACO Training(2300-2393)</tr>";
         echo  "<tr><td><td>第一届山东省赛题目(2400-2409),第三届山东省赛题目(3240-3249),第四届山东省赛题目(3230-3239)，第八届山东省赛题目(3263-3273)</tr>";


//	echo "<tr><td><td><font color=red>算法分类：</font></tr>";
//       echo "<tr><td><td>第一章(1288-1292) 第二章(1293-1306) 第三章(1307-1325) 第四章(1326-1347) 第五章(1348-1373) 第六章(1374-1397)	</tr>";	
//	echo "<tr> <td><td><font color=red>首届ACM程序设计大赛　<a href=\"http://acm.sdibt.edu.cn/download/1ACM.zip\">解题报告</a></font></tr>";
///    echo  " <tr><td><td><font color=red> OJ使用帮助&nbsp;&nbsp;<a href=\"../download/SDIBTOJ_HELP.rar\">下载</a></font></tr>";

      echo "<tr><td><td>如果忘记密码，<a href=lostpassword.php>点击</a>重置。  </tr>";
 
    //   echo "<tr><td> <td><font><h3>山商ACM QQ群76827278(已满)       125696898 </h3></font></tr>";        
                       
		echo "</table>";
	}
?>

<?php require('oj-footer.php');?>
