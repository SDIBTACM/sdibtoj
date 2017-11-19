<?
	require_once("../include/db_info.inc.php");
	require_once("exam-header.php");

	$user_id=mysql_real_escape_string($_SESSION['user_id']);
	$sql="SELECT `nick`,`email`,`reg_time` FROM `users` WHERE `user_id`='".$user_id."'";
	$result=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($result);
	$nick=$row[0];
	$email=$row[1];
	$regtime=$row[2];
	mysql_free_result($result);
?>
<div class="container" style="padding-bottom:50px">
	<table id="userinfo" class="table table-hover table-striped">
		<tbody>
			<tr>
				<td rowspan="5" style="background:url(./image/pic_bg.png) no-repeat;width:152px;
				height:140px;" align=center>
					<img src="./image/person.gif" height="120px" width="135px" border="0px">
				</td>
			</tr>
			<tr>
				<td>账号:</td>
				<td><?echo $user_id?></td>
			</tr>
			<tr>
				<td>昵称:</td>
				<td><?echo $nick?></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><?if($email=="") echo "未填";else echo $email?></td>
			</tr>
			<tr>
				<td>注册时间:</td>
				<td><?echo $regtime?></td>
			</tr>
		</tbody>
	</table>
	<table class="table-hover table-striped" width=100%>
		<thread>
			<th colspan="7" style="background:url(./image/titlebar.png)" width="100%" height="36px">
			<span style="COLOR:#CCC;font-weight:bold;">您参加的考试</span>
			</th>
		</thread>
		<?
			$query="SELECT `title`,`exam`.`exam_id`,`score`,`choosesum`,`judgesum`,`fillsum`,`programsum` FROM `exam`,`ex_student` WHERE `ex_student`.`user_id`='".$user_id."'
			 AND `exam`.`visible`='Y' AND `ex_student`.`exam_id`=`exam`.`exam_id` ORDER BY `exam`.`exam_id` DESC";
			 $numexam=1;
			 $result=mysql_query($query) or die(mysql_error());
			 $row_cnt=mysql_num_rows($result);
			 if($row_cnt==0)
			 {
			 	echo "<tr class=\"table\">";
			 	echo "<td>您未参加过任何考试</td>";
			 	echo "</tr>";
			 }
			 else
			 {
			 	echo "<tr class='table'><td>序号</td><td>考试名称</td><td>选择题得分</td><td>判断题得分</td>
			 	<td>填空题得分</td><td>程序设计题得分</td><td>总分</td></tr>";
			 	while($row=mysql_fetch_object($result)){
			 		echo "<tr class=\"table\">";
			 		echo "<td>$numexam</td><td>$row->title</td><td>$row->choosesum</td><td>$row->judgesum</td>";
			 		echo "<td>$row->fillsum</td><td>$row->programsum</td><td>$row->score</td>";
			 		echo "</tr>";
			 		$numexam++;
			 	}
			 }
			 mysql_free_result($result);
		?>
	</table>
</div>
<?
	require_once("exam-footer.php");
?>