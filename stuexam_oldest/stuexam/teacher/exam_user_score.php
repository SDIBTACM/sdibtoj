<?php
	require_once("./teacher-header.php");
?>
<?php
	if(isset($_GET['eid']))
	{
		//var_dump(SortStuScore('stu'));
		$sqladd=SortStuScore('stu');
		$eid=intval($_GET['eid']);
		$prisql="SELECT `creator`,`start_time`,`end_time` FROM `exam` WHERE `exam_id`='$eid'";
		$prirow=fetchOne($prisql);
		$creator=$prirow['creator'];
		$start_time=$prirow['start_time'];
		$end_time=$prirow['end_time'];
		$start_timeC=strtotime($start_time);
		$end_timeC=strtotime($end_time);
		$now=time();
		$priflag=0;
		if($now>$end_timeC&&isset($_SESSION['contest_creator']))
			$priflag=1;
		if(checkAdmin(5,$creator,$priflag))
		{
			alertmsg("You have no privilege of this exam","./",0);
		}
		else
		{
			if(filter_var($eid, FILTER_VALIDATE_INT)&&$eid>0)
			{
				$query="SELECT `stu`.`user_id`,`stu`.`nick`,`choosesum`,`judgesum`,`fillsum`,`programsum`,`score` 
				FROM (SELECT `users`.`user_id`,`users`.`nick` FROM `ex_privilege`,`users` WHERE `ex_privilege`.`user_id`=`users`.`user_id` AND
				 `ex_privilege`.`rightstr`=\"e$eid\" )stu left join `ex_student` on `stu`.`user_id`=`ex_student`.`user_id` AND 
				`ex_student`.`exam_id`='$eid' $sqladd";
				$result=mysql_query($query) or die(mysql_error());
		?>
				<div>
				<div class="leftmenu pull-left" id="left">
				<ul class="nav nav-pills bs-docs-sidenav">
				<li class="active"><a href="./">考试管理</a></li>
				<li><a href="admin_choose.php">选择题管理</a></li>
				<li><a href="admin_judge.php">判断题管理</a></li>
				<li><a href="admin_fill.php">填空题管理</a></li>
				<?php
					if(checkAdmin(1)){
						echo "<li><a href=\"admin_point.php\">知识点管理</a></li>";
					}
				?>
				<li><a href="../">退出管理页面</a></li>
				</ul>
				</div>
				<div class="container" style="margin-left:23%" id="right">
				<h2 style="text-align:center">成绩单</h2>
				<div style="height:40px">
				<ul class="nav nav-pills pull-left">
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
				<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
				<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
				<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
				<li class="active"><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
				<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
				<?php
					if(checkAdmin(1)){
						echo "<li><a href=\"rejudge.php?eid=$eid\">Rejudge</a></li>";
					}
				?>
				</ul>
				</div>
				
  				<input type="hidden" name="eid" id="eid" value="<?=$eid?>" />
   	 			
				<table class="table table-hover table-bordered table-striped">
				<thead>
				<tr>
				<th width=5%>Rank</th>
				<th width=7%>账号</th>
				<th width=7%>姓名</th>
				<th width=7%>选择题成绩</th>
				<th width=7%>判断题成绩</th>
				<th width=7%>填空题成绩</th>
				<th width=7%>程序题成绩</th>
				<th width=7%>总成绩</th>
				<th width=7%>试卷</th>
				<th width=7%>操作</th>
				</tr>
				</thead>
				<tbody>
					<tr class='first-tr'>
						<td></td>
						<td><input type="text" id="xs_userid" name="xs_userid" placeholder="查询账号"/></td>
						<td><input type="text" id="xs_name" name="xs_name" placeholder="查询姓名"/></td>
						<td><select  name = "xs_choose" id="xs_choose">
							<option value=0 >请选择排序方式</option>
							<option value=1 >升序排序</option>
							<option value=2 >降序排序</option>
        				</select></td>
						<td><select  name = "xs_judge" id="xs_judge">
							<option value=0 >请选择排序方式</option>
							<option value=1 >升序排序</option>
							<option value=2 >降序排序</option>
        				</select></td>
						<td><select  name = "xs_fill" id="xs_fill">
							<option value=0 >请选择排序方式</option>
							<option value=1 >升序排序</option>
							<option value=2 >降序排序</option>
        				</select></td>
						<td><select  name = "xs_program" id="xs_program">
							<option value=0 >请选择排序方式</option>
							<option value=1 >升序排序</option>
							<option value=2 >降序排序</option>
        				</select></td>
						<td><select  name = "xs_score" id="xs_score">
							<option value=0 >请选择排序方式</option>
							<option value=1 >升序排序</option>
							<option value=2 >降序排序</option>
        				</select></td>
						<td colspan='2'><a href="javascript:void(0);" onclick="xs_search()">
						Search</a></td>
					</tr>	
				<?
					$KUserId=array();
					$KNick=array();
					$KChoosesum=array();
					$KJudgesum=array();
					$KFillsum=array();
					$KProgramsum=array();
					$KScore=array();
					$rank=0;
					while($row=mysql_fetch_object($result)){
						$KUserId[$rank]=$row->user_id;
						$KNick[$rank]=$row->nick;
						$KChoosesum[$rank]=$row->choosesum;
						$KJudgesum[$rank]=$row->judgesum;
						$KFillsum[$rank]=$row->fillsum;
						$KProgramsum[$rank]=$row->programsum;
						$KScore[$rank]=$row->score;
						$rank++;
					}
					mysql_free_result($result);

					$isonline=array();
					$sql = "SELECT distinct `user_id` FROM `ex_stuanswer` WHERE `exam_id`='$eid'";
					$onlineresult=mysql_query($sql) or mysql_error();
					while($row=mysql_fetch_object($onlineresult))
					{
						$isonline[$row->user_id]=1;
					}
					mysql_free_result($onlineresult);

					for($i=0;$i<$rank;$i++)
					{
						$t=$i+1;
						echo "<tr>";
						echo "<td>$t</td>";
						echo "<td>$KUserId[$i]</td>";
						echo "<td>$KNick[$i]</td>";
						echo "<td>$KChoosesum[$i]</td>";
						echo "<td>$KJudgesum[$i]</td>";
						echo "<td>$KFillsum[$i]</td>";
						echo "<td>$KProgramsum[$i]</td>";
						echo "<td>$KScore[$i]</td>";

						if($KScore[$i]=="")
						{
							if(!isset($isonline[$KUserId[$i]]))
								echo "<td><font color=green>[未参加]</font></td>";
							else
							{
								if($now>$end_timeC)
									echo "<td><font color=blue>[未交卷]</font></td>";
								else
									echo "<td><font color=red>[正在考试]</font></td>";
							}
						}
						else
							echo "<td><a href=\"showtestpaper.php?users=$KUserId[$i]&eid=$eid\">[查看试卷]</a></td>";
						if($KScore[$i]=="")
						{
							
							if(isset($isonline[$KUserId[$i]])&&$now>$end_timeC)
								echo "<td><a href=\"submit_after_exam.php?users=$KUserId[$i]&eid=$eid\">[提交试卷]</a></td>";
							else
								echo "<td>无</td>";
						}
						else
							echo "<td><a href=\"del_user_score.php?users=$KUserId[$i]&eid=$eid\" onclick=\"return suredo('是否要删除该考生成绩,让考生重新参加考试？')\">[删除分数]</a></td>";
						echo"</tr>";
					}
					unset($KUserId);
					unset($KNick);
					unset($KChoosesum);
					unset($KJudgesum);
					unset($KFillsum);
					unset($KProgramsum);
					unset($KScore);
					unset($isonline);
				?>
				</tbody>
				</table>
				</div>
				</div>
			<?php
			}
			else{
				alertmsg("Invaild data");
			}
		}
	}
	else{
		alertmsg("Invaild path");
	}
?>
<script type="text/javascript">

function xs_search(){
	var xs_userid = $("#xs_userid").val();
	var xs_name = $("#xs_name").val();
	var xs_choose = $("#xs_choose option:selected").val();
	var xs_judge = $("#xs_judge option:selected").val();
	var xs_fill = $("#xs_fill option:selected").val();
	var xs_program = $("#xs_program option:selected").val();
	var xs_score = $("#xs_score option:selected").val();
	var eid = $("#eid").val();
	var url = "<?php echo $_SERVER['PHP_SELF'];?>";
	url = url+"?eid="+eid;
	if(xs_userid!=""){
		url = url+"&xsid="+encodeURIComponent(xs_userid);
	}
	if(xs_name!=""){
		url = url+"&xsname="+encodeURIComponent(xs_name);
	}
	//1 1 1 1 1 = 16 + 8 + 4 + 2 + 1 = 31
	var sortAnum = 0,sortDnum=0;
	if(xs_choose==1) sortAnum|=1;
	else if(xs_choose==2) sortDnum|=1;
	if(xs_judge==1) sortAnum|=2;
	else if(xs_judge==2) sortDnum|=2;
	if(xs_fill==1) sortAnum|=4;
	else if(xs_fill==2) sortDnum|=4;
	if(xs_program==1) sortAnum|=8;
	else if(xs_program==2) sortDnum|=8;
	if(xs_score==1) sortAnum|=16;
	else if(xs_score==2) sortDnum|=16;

	url = url+"&sortanum="+sortAnum+"&sortdnum="+sortDnum;
	window.location.href = url;
}

function suredo(q){
	var ret;
	ret = confirm(q);
	return ret;
}
$(function(){
	if($("#left").height()<700)
		$("#left").css("height",700)
	if($("#left").height()>$("#right").height()){
		$("#right").css("height",$("#left").height())
	}
	else{
		$("#left").css("height",$("#right").height())
	}
});
</script>
<?php
	require_once("./teacher-footer.php");
?>
