<?
	require_once("./teacher-header.php");
	if(isset($_GET['type'])&&isset($_GET['eid']))
	{
		$type=intval($_GET['type']);
		$eid=intval($_GET['eid']);
		$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$eid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$creator=mysql_result($priresult, 0);
		mysql_free_result($priresult);
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "<script language='javascript'>\n";
		    echo "alert(\"You have no privilege of this exam\");\n";  
	        echo "location='./'";
	        echo "</script>";
		}
		else
		{
			if(isset($_GET['search']))
			{
				$search=mysql_real_escape_string($_GET['search']);
				if($search!='')
					$searchsql=" WHERE `creator` like '%$search%' or `point` like '%$search%'";
				else
					$searchsql="";
			}
			else
			{
				$search="";
				$searchsql="";
			}
			if(filter_var($type, FILTER_VALIDATE_INT)&&filter_var($eid, FILTER_VALIDATE_INT)&&$eid>0)
			{
				if($type==1||$type==2||$type==3)
				{
					if(isset($_GET['page']))
					{
						if(!is_numeric($_GET['page']))
							$page=1;
						else
							$page=intval($_GET['page']);
					}
					else
						$page=1;
					$each_page=15;// each page data num
					$pagenum=10;//the max of page num
					if($type==1)
						$sql="SELECT COUNT(*) FROM `ex_choose` $searchsql";
					else if($type==2)
						$sql="SELECT COUNT(*) FROM `ex_judge` $searchsql";
					else if($type==3)
						$sql="SELECT COUNT(*) FROM `ex_fill` $searchsql";
					$result=mysql_query($sql) or die(mysql_error());
					$total=mysql_result($result, 0);
					mysql_free_result($result);

					$totalpage=ceil($total/$each_page);
					if($totalpage==0)	$totalpage=1;
					$page=$page>$totalpage?$totalpage:$page;
					$page=$page<1?1:$page;

					$offset=($page-1)*$each_page;
					$sqladd=" limit $offset,$each_page";

					$lastpage=$totalpage;
					$prepage=$page-1;
					$nextpage=$page+1;

					$startpage=$page-4;
					$startpage=$startpage<1?1:$startpage;
					$endpage=$startpage+$pagenum-1;
					$endpage=$endpage>$totalpage?$totalpage:$endpage;
				}
				if($type==9)// main about the exam
				{
					?>
					<div>
					<div class="pull-left leftmenu" id="left">
					<ul class="nav nav-pills bs-docs-sidenav">
					<li class="active"><a href="./">考试管理</a></li>
					<li><a href="admin_choose.php">选择题管理</a></li>
					<li><a href="admin_judge.php">判断题管理</a></li>
					<li><a href="admin_fill.php">填空题管理</a></li>
					<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li><a href=\"admin_point.php\">知识点管理</a></li>";
					}
					?>
					<li><a href="../">退出管理页面</a></li>
					</ul>
					</div>
					<div class="container" style="margin-left:23%" id="right">
					<h2 style="text-align:center">编辑考试</h2>
					<div style="height:40px">
					<ul class="nav nav-pills pull-left">
			  <li class="active"><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
					<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
					<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
					<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
					<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
					</ul>
					</div>
					<?
					$query="SELECT * FROM `exam` WHERE `exam_id`='$eid'";
					$result=mysql_query($query) or die(mysql_error());
					$row_cnt=mysql_num_rows($result);
					if($row_cnt){
						$row=mysql_fetch_assoc($result);
						$title=htmlspecialchars($row['title']);
						$starttime=$row['start_time'];
						$endtime=$row['end_time'];
						$xzfs=$row['choosescore'];
						$pdfs=$row['judgescore'];
						$tkfs=$row['fillscore'];
						$yxjgfs=$row['prgans'];
						$cxtkfs=$row['prgfill'];
						$cxfs=$row['programscore'];
						$isvip=$row['isvip'];
						mysql_free_result($result);
					?>
						<div style="margin-top:10px">
						<form action="update_exam_ok.php" method="post" onSubmit="return chkinput(this)">
						<br/>
						考试名称:<input type="text" maxlength="100" name="examname" value="<?php echo $title;?>" /><br/>
						考试开始时间:<p></p>
						<input type="text" name="syear"   value="<?php echo substr($starttime,0,4)?>" style="width:40px" />年-
						<input type="text" name="smonth"  value="<?php echo substr($starttime,5,2)?>" style="width:40px" />月-
						<input type="text" name="sday"    value="<?php echo substr($starttime,8,2)?>" style="width:40px" />日-
						<input type="text" name="shour"   value="<?php echo substr($starttime,11,2)?>" style="width:40px" />时-
						<input type="text" name="sminute" value="<?php echo substr($starttime,14,2)?>" style="width:40px" />分</p>
						考试结束时间:<p></p>
						<input type="text" name="eyear"   value="<?php echo substr($endtime,0,4)?>" style="width:40px" />年-
						<input type="text" name="emonth"  value="<?php echo substr($endtime,5,2)?>" style="width:40px" style="width:40px" />月-
					    <input type="text" name="eday"    value="<?php echo substr($endtime,8,2)?>" style="width:40px" />日-
					    <input type="text" name="ehour"   value="<?php echo substr($endtime,11,2)?>" style="width:40px" />时-
						<input type="text" name="eminute" value="<?php echo substr($endtime,14,2)?>" style="width:40px">分</p>
						<font color=red>*以下数值只支持整数</font><br/>
						(1)选择题每题分值:<input type="text" name="xzfs" style="width:50px" value="<?php echo $xzfs;?>" />分;<br/>
						(2)判断题每题分值:<input type="text" name="pdfs" style="width:50px" value="<?php echo $pdfs;?>" />分;<br/>
						(3)基础填空题每空分值:<input type="text" name="tkfs" style="width:50px" value="<?php echo $tkfs;?>" />分;<br/>
						(4)写运行结果题每题分值:<input type="text" name="yxjgfs" style="width:50px" value="<?php echo $yxjgfs?>" />分;<br/>
						(5)程序填空题每题分值:<input type="text" name="cxtkfs" style="width:50px" value="<?php echo $cxtkfs?>" />分;<br/>
						(6)程序设计题每题分值:<input type="text" name="cxfs" style="width:50px" value="<?php echo $cxfs;?>" />分;<br/>
						是否限定一个账号只能在一台机器登陆:<select name="isvip" class="span1">
						<option value="Y" <?echo $isvip=='Y'?"selected":"" ?> >Yes</option>
						<option value="N" <?echo $isvip=='N'?"selected":"" ?> >No</option>
						</select><br/>
						<?require_once("../../include/set_post_key.php");?>
						<input type="hidden" value="<?=$eid?>" name="eid">
						<input type="submit" value="提交" class="mybutton">
						<input type="reset" value="重置" class="mybutton">
						</form>
						</div>
						</div>
						</div>
					<?
					}
					else
					{
						echo "No such Exam";
						exit(0);
					}
				}
				else if($type==1)//add choose
				{
					?>
					<div>
					<div class="leftmenu pull-left" id="left">
					<ul class="nav nav-pills bs-docs-sidenav">
					<li class="active"><a href="./">考试管理</a></li>
					<li><a href="admin_choose.php">选择题管理</a></li>
					<li><a href="admin_judge.php">判断题管理</a></li>
					<li><a href="admin_fill.php">填空题管理</a></li>
					<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li><a href=\"admin_point.php\">知识点管理</a></li>";
					}
					?>
					<li><a href="../">退出管理页面</a></li>
					</ul>
					</div>
					<div class="container" style="margin-left:23%" id="right">
					<h2 style="text-align:center">添加选择题</h2>
					<div style="height:40px">
					<ul class="nav nav-pills pull-left">
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
			  <li class="active"><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
					<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
					<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
					<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
					<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
					</ul>
					</div>
					<form class="form-search pull-right">
  					<div class="input-append">
  					<input type="hidden" name="eid" value="<?=$eid?>" />
  					<input type="hidden" name="type" value="<?=$type?>" />
  					<input type="hidden" name="page" value="<?=$page?>" />
   	 				<input type="text" class="span3 search-query" value="<?=$search?>" name="search" placeholder="查询创建者或知识点">
    				<input type="submit" class="btn" value="Search">
    		 		</div>
					</form>
					<table class="table table-hover table-bordered table-striped table-condensed jiadian">
					<thread>
					<th width=5%>题目ID</th>
					<th width=32%>题目描述</th>
					<th width=10%>创建时间</th>
					<th width=8%>创建者</th>
					<th width=8%>知识点</th>
					<th width=4%>难度</th>
					<th width=8%>操作</th>
					</thread>
					<tbody>
					<?
						$sql="SELECT `choose_id`,`question`,`addtime`,`creator`,`point`,`easycount` FROM `ex_choose` $searchsql ORDER BY `choose_id` DESC $sqladd";
						$result = mysql_query($sql) or die(mysql_error());
						while($row=mysql_fetch_object($result)){
						echo "<tr>";
						echo "<td>$row->choose_id</td>";
						$question=$row->question;
						echo "<td>$question</td>";
						echo "<td style=\"font-size:9px\">$row->addtime</td>";
						echo "<td>$row->creator</td>";
						echo "<td>$row->point</td>";
						echo "<td>$row->easycount</td>";
						//$query="SELECT COUNT(*) FROM `exp_choose` WHERE `exam_id`='$eid' and `choose_id`='$row->choose_id'";
						$query="SELECT COUNT(*) FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='1' AND `question_id`='$row->choose_id'";
						$myresult=mysql_query($query) or die(mysql_error());
						$myrow=mysql_fetch_array($myresult);
						$num=$myrow[0];
						if($num==1)
							echo "<td>已添加</td>";
						else
							echo "<td><span id=\"choose$row->choose_id\"><a href=\"javascript:void(0);\" 
						onclick=\"addquestion('choose$row->choose_id','$eid','$row->choose_id','$type')\">添加到试卷</a></span></td>";
						mysql_free_result($myresult);
						echo "</tr>";
						}
						mysql_free_result($result);
						echo "</tbody></table>";
						echo "<div class=\"pagination\" style=\"text-align:center\">";
						echo "<ul>";
						echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=1&page=1&search=$search\">First</a></li>";
						if($page==1)
							echo "<li class=\"disabled\"><a href=\"\">Previous</a></li>";
						else
							echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=1&page=$prepage&search=$search\">Previous</a></li>";
						for($i=$startpage;$i<=$endpage;$i++)
						{
							if($i==$page)
								echo "<li class=\"active\"><a href=\"add_exam_problem.php?eid=$eid&type=1&page=$i&search=$search\">$i</a></li>";
							else
						  		echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=1&page=$i&search=$search\">$i</a></li>";
						}
						if($page==$lastpage)
							echo "<li class=\"disabled\"><a href=\"\">Next</a></li>";
						else
							echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=1&page=$nextpage&search=$search\">Next</a></li>";
						echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=1&page=$lastpage&search=$search\">Last</a></li>";
						echo "</ul>";
						echo "</div>";
						echo"</div></div>";
				}
				else if($type==2)//add judge
				{
					?>
					<div>
					<div class="leftmenu pull-left" id="left">
					<ul class="nav nav-pills bs-docs-sidenav">
					<li class="active"><a href="./">考试管理</a></li>
					<li><a href="admin_choose.php">选择题管理</a></li>
					<li><a href="admin_judge.php">判断题管理</a></li>
					<li><a href="admin_fill.php">填空题管理</a></li>
					<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li><a href=\"admin_point.php\">知识点管理</a></li>";
					}
					?>
					<li><a href="../">退出管理页面</a></li>
					</ul>
					</div>
					<div class="container" style="margin-left:23%" id="right">
					<h2 style="text-align:center">添加判断题</h2>
					<div style="height:40px">
					<ul class="nav nav-pills pull-left">
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
			  <li class="active"><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
					<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
					<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
					<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
					<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
					</ul>
					</div>
					<form class="form-search pull-right">
  					<div class="input-append">
  					<input type="hidden" name="eid" value="<?=$eid?>" >
  					<input type="hidden" name="type" value="<?=$type?>" >
  					<input type="hidden" name="page" value="<?=$page?>" >
   	 				<input type="text" class="span3 search-query" value="<?=$search?>" name="search" placeholder="查询创建者或知识点">
    				<input type="submit" class="btn" value="Search">
    		 		</div>
					</form>
					<table class="table table-hover table-bordered table-striped table-condensed jiadian">
					<thread>
					<th width=5%>题目ID</th>
					<th width=32%>题目描述</th>
					<th width=10%>创建时间</th>
					<th width=8%>创建者</th>
					<th width=8%>知识点</th>
					<th width=4%>难度</th>
					<th width=8%>操作</th>
					</thread>
					<tbody>
					<?
					$sql="SELECT `judge_id`,`question`,`addtime`,`creator`,`point`,`easycount` FROM `ex_judge` $searchsql ORDER BY `judge_id` DESC $sqladd";
					$result = mysql_query($sql) or die(mysql_error());
					while($row=mysql_fetch_object($result)){
					echo "<tr>";
					echo "<td>$row->judge_id</td>";
					$question=$row->question;
					echo "<td>$question</td>";
					echo "<td style=\"font-size:9px\">$row->addtime</td>";
					echo "<td>$row->creator</td>";
					echo "<td>$row->point</td>";
					echo "<td>$row->easycount</td>";
					//$query="SELECT COUNT(*) FROM `exp_judge` WHERE `exam_id`='$eid' and `judge_id`='$row->judge_id'";
					$query="SELECT COUNT(*) FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='2' AND `question_id`='$row->judge_id'";
					$myresult=mysql_query($query) or die(mysql_error());
					$myrow=mysql_fetch_array($myresult);
					$num=$myrow[0];
					if($num==1)
						echo "<td>已添加</td>";
					else
						echo "<td><span id=\"judge$row->judge_id\"><a href=\"javascript:void(0);\" 
					onclick=\"addquestion('judge$row->judge_id','$eid','$row->judge_id','$type')\">添加到试卷</a></span></td>";
					mysql_free_result($myresult);
					echo "</tr>";
					}
					mysql_free_result($result);
					echo "</tbody></table>";
					echo "<div class=\"pagination\" style=\"text-align:center\">";
					echo "<ul>";
					echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=2&page=1&search=$search\">First</a></li>";
					if($page==1)
						echo "<li class=\"disabled\"><a href=\"\">Previous</a></li>";
					else
						echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=2&page=$prepage&search=$search\">Previous</a></li>";
					for($i=$startpage;$i<=$endpage;$i++)
					{
						if($i==$page)
							echo "<li class=\"active\"><a href=\"add_exam_problem.php?eid=$eid&type=2&page=$i&search=$search\">$i</a></li>";
						else
					  		echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=2&page=$i&search=$search\">$i</a></li>";
					}
					if($page==$lastpage)
						echo "<li class=\"disabled\"><a href=\"\">Next</a></li>";
					else
						echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=2&page=$nextpage&search=$search\">Next</a></li>";
					echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=2&page=$lastpage&search=$search\">Last</a></li>";
					echo "</ul>";
					echo "</div>";
					echo "</div></div>";
				}
				else if($type==3)//add fill
				{
					?>
					<div>
					<div class="leftmenu pull-left" id="left">
					<ul class="nav nav-pills bs-docs-sidenav">
					<li class="active"><a href="./">考试管理</a></li>
					<li><a href="admin_choose.php">选择题管理</a></li>
					<li><a href="admin_judge.php">判断题管理</a></li>
					<li><a href="admin_fill.php">填空题管理</a></li>
					<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li><a href=\"admin_point.php\">知识点管理</a></li>";
					}
					?>
					<li><a href="../">退出管理页面</a></li>
					</ul>
					</div>
					<div class="container" style="margin-left:23%" id="right">
					<h2 style="text-align:center">添加填空题</h2>
					<div style="height:40px">
					<ul class="nav nav-pills pull-left">
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
			  <li class="active"><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
					<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
					<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
					<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
					<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
					</ul>
					</div>
					<form class="form-search pull-right">
  					<div class="input-append">
  					<input type="hidden" name="eid" value="<?=$eid?>" >
  					<input type="hidden" name="type" value="<?=$type?>" >
  					<input type="hidden" name="page" value="<?=$page?>" >
   	 				<input type="text" class="span3 search-query" value="<?=$search?>" name="search" placeholder="查询创建者或知识点">
    				<input type="submit" class="btn" value="Search">
    		 		</div>
					</form>
					<table class="table table-hover table-bordered table-striped table-condensed jiadian">
					<thread>
					<th width=5%>题目ID</th>
					<th width=32%>题目描述</th>
					<th width=10%>创建时间</th>
					<th width=8%>创建者</th>
					<th width=8%>知识点</th>
					<th width=4%>难度</th>
					<th width=8%>操作</th>
					</thread>
					<tbody>
					<?
					$sql="SELECT `fill_id`,`question`,`addtime`,`creator`,`point`,`easycount` FROM `ex_fill` $searchsql ORDER BY `fill_id` DESC $sqladd";
					$result = mysql_query($sql) or die(mysql_error());
					while($row=mysql_fetch_object($result)){
					echo "<tr>";
					echo "<td>$row->fill_id</td>";
					$question=$row->question;
					echo "<td>$question</td>";
					echo "<td style=\"font-size:9px\">$row->addtime</td>";
					echo "<td>$row->creator</td>";
					echo "<td>$row->point</td>";
					echo "<td>$row->easycount</td>";
					//$query="SELECT COUNT(*) FROM `exp_fill` WHERE `exam_id`='$eid' and `fill_id`='$row->fill_id'";
					$query="SELECT COUNT(*) FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='3' AND `question_id`='$row->fill_id'";
					$myresult=mysql_query($query) or die(mysql_error());
					$myrow=mysql_fetch_array($myresult);
					$num=$myrow[0];
					if($num==1)
						echo "<td>已添加</td>";
					else
						echo "<td><span id=\"fill$row->fill_id\"><a href=\"javascript:void(0);\" 
					onclick=\"addquestion('fill$row->fill_id','$eid','$row->fill_id','$type')\">添加到试卷</a></span></td>";
					mysql_free_result($myresult);
					echo "</tr>";
					}
					mysql_free_result($result);
					echo "</tbody></table>";
					echo "<div class=\"pagination\" style=\"text-align:center\">";
					echo "<ul>";
					echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=3&page=1&search=$search\">First</a></li>";
					if($page==1)
						echo "<li class=\"disabled\"><a href=\"\">Previous</a></li>";
					else
						echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=3&page=$prepage&search=$search\">Previous</a></li>";
					for($i=$startpage;$i<=$endpage;$i++)
					{
						if($i==$page)
							echo "<li class=\"active\"><a href=\"add_exam_problem.php?eid=$eid&type=3&page=$i&search=$search\">$i</a></li>";
						else
					  		echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=3&page=$i&search=$search\">$i</a></li>";
					}
					if($page==$lastpage)
						echo "<li class=\"disabled\"><a href=\"\">Next</a></li>";
					else
						echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=3&page=$nextpage&search=$search\">Next</a></li>";
					echo "<li><a href=\"add_exam_problem.php?eid=$eid&type=3&page=$lastpage&search=$search\">Last</a></li>";
					echo "</ul>";
					echo "</div>";
					echo "</div></div>";
				}
				else if($type==5)//the main exam question
				{
					?>
					<div class="myfont">
					<div class="leftmenu pull-left" id="left">
					<ul class="nav nav-pills bs-docs-sidenav">
					<li class="active"><a href="./">考试管理</a></li>
					<li><a href="admin_choose.php">选择题管理</a></li>
					<li><a href="admin_judge.php">判断题管理</a></li>
					<li><a href="admin_fill.php">填空题管理</a></li>
					<?
					if(isset($_SESSION['administrator']))
					{
						echo "<li><a href=\"admin_point.php\">知识点管理</a></li>";
					}
					?>
					<li><a href="../">退出管理页面</a></li>
					</ul>
					</div>
					<div class="container" style="margin-left:23%" id="right">
					<h2 style="text-align:center">试卷一览</h2>
					<div style="height:40px">
					<ul class="nav nav-pills pull-left">
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=9">考试基本信息</a></li>
			  <li class="active"><a href="add_exam_problem.php?eid=<?=$eid?>&type=5">试卷一览</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=1">选择题</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=2">判断题</a></li>
					<li><a href="add_exam_problem.php?eid=<?=$eid?>&type=3">填空题</a></li>
					<li><a href="add_program.php?eid=<?=$eid?>&type=4">程序题</a></li>
					<li><a href="add_exam_user.php?eid=<?=$eid?>">添加考生</a></li>
					<li><a href="exam_user_score.php?eid=<?=$eid?>">考生成绩</a></li>
					<li><a href="exam_analysis.php?eid=<?=$eid?>">考试分析</a></li>
					</ul>
					</div>
					<br>
					<table class="pull-left huanhang">
					<tr><td><h5>一.选择题</h5></td></tr>
					<?
						$numofchoose=0;
						/*$query1="SELECT `ex_choose`.`choose_id`,`question`,`ams`,`bms`,`cms`,`dms`,`answer` FROM `ex_choose`,`exp_choose` 
						WHERE `exam_id`='$eid' and `ex_choose`.`choose_id`=`exp_choose`.`choose_id` ORDER BY `choose_id`";*/
						$query1="SELECT `ex_choose`.`choose_id`,`question`,`ams`,`bms`,`cms`,`dms`,`answer` FROM `ex_choose`,`exp_question` 
						WHERE `exam_id`='$eid' AND `type`='1' AND `ex_choose`.`choose_id`=`exp_question`.`question_id` ORDER BY `choose_id`";
						$result1=mysql_query($query1) or die(mysql_error());
						while($row1=mysql_fetch_object($result1)){
							$numofchoose++;
							$question="<pre>".$row1->question."</pre>";
							//$question=nl2br(htmlspecialchars($row1->question));
							//$question=str_replace("<br />", "<br/>&nbsp;&nbsp;&nbsp;",$question);
							echo "<tr><td>$numofchoose.&nbsp;&nbsp;$question";
							echo "<a href=\"del_exam_problem.php?eid=$eid&type=1&qid=$row1->choose_id\">[去除该题]</a>
							<font color=red>答案:$row1->answer</font></td></tr>";
							echo "<tr><td>(A).$row1->ams";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "(B).$row1->bms</td></tr>";
							echo "<tr><td>(C).$row1->cms";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "(D).$row1->dms</td></tr>";
						}
						mysql_free_result($result1);
					?>
					<tr><td><h5>二.判断题</h5></td></tr>
					<?
						$numofjudge=0;
						/*$query2="SELECT `ex_judge`.`judge_id`,`question`,`answer` FROM `ex_judge`,`exp_judge` 
						WHERE `exam_id`='$eid' and `ex_judge`.`judge_id`=`exp_judge`.`judge_id` ORDER BY `judge_id`";*/
						$query2="SELECT `ex_judge`.`judge_id`,`question`,`answer` FROM `ex_judge`,`exp_question` 
						WHERE `exam_id`='$eid' AND `type`='2' AND `ex_judge`.`judge_id`=`exp_question`.`question_id` ORDER BY `judge_id`";
						$result2=mysql_query($query2) or die(mysql_error());
						while($row2=mysql_fetch_object($result2)){
							$numofjudge++;
							echo "<tr>";
							$question="<pre>".$row2->question."</pre>";
							//$question=nl2br(htmlspecialchars($row2->question));
							//$question=str_replace("<br />", "<br/>&nbsp;&nbsp;&nbsp;",$question);
							echo "<td>$numofjudge.&nbsp;&nbsp;$question";
							echo "<a href=\"del_exam_problem.php?eid=$eid&type=2&qid=$row2->judge_id\">[去除该题]</a>
							<font color=red>答案:$row2->answer</font></td>";
							echo "</tr>";
						}
						mysql_free_result($result2);
					?>
					<tr><td><h5>三.填空题</h5></td></tr>
					<?
						$numoffill=0;
						$numofprgans=0;
						$numofprgfill=0;
						$fillnum=0;
						/*$query3="SELECT `ex_fill`.`fill_id`,`question`,`answernum` FROM `ex_fill`,`exp_fill` 
						WHERE `exam_id`='$eid' and `ex_fill`.`fill_id`=`exp_fill`.`fill_id` ORDER BY `fill_id`";*/
						$query3="SELECT `ex_fill`.`fill_id`,`question`,`answernum`,`kind` FROM `ex_fill`,`exp_question` 
						WHERE `exam_id`='$eid' AND `type`='3' AND `ex_fill`.`fill_id`=`exp_question`.`question_id` ORDER BY `fill_id`";
						$result3=mysql_query($query3) or die(mysql_error());
						while($row3=mysql_fetch_object($result3)){
							$fillnum++;
							echo "<tr>";
							$question="<pre>".$row3->question."</pre>";
							//$question=nl2br(htmlspecialchars($row3->question));
							//$question=str_replace("<br />", "<br/>&nbsp;&nbsp;&nbsp;",$question);
							echo "<td>$fillnum.&nbsp;&nbsp;$question";
							echo "<a href=\"del_exam_problem.php?eid=$eid&type=3&qid=$row3->fill_id\">[去除该题]</a></td>";
							echo "</tr>";
							if($row3->kind==1)
								$numoffill+=$row3->answernum;
							else if($row3->kind==2)
								$numofprgans++;
							else
								$numofprgfill++;
							$fillanswer="SELECT `answer_id`,`answer` FROM `fill_answer` WHERE `fill_id`='$row3->fill_id' ORDER BY `answer_id`";
							$fillresult=mysql_query($fillanswer) or die(mysql_error());
							while($fillrow=mysql_fetch_object($fillresult)){
								echo "<tr><td><font color=red>答案$fillrow->answer_id:$fillrow->answer</font></td></tr>";
							}
						}
						mysql_free_result($result3);
					?>
					<tr><td><h5>四.程序设计题</h5></td></tr>
					<?
						$numofprogram=0;
						/*$query4="SELECT `program_id`,`title`,`description` FROM `exp_program`,`problem` WHERE `exam_id`='$eid' AND `program_id`=`problem_id`";*/
						$query4="SELECT `question_id` as `program_id`,`title`,`description` FROM `exp_question`,`problem` 
						WHERE `exam_id`='$eid' AND `type`='4' AND `question_id`=`problem_id`";
						$result4=mysql_query($query4) or die(mysql_error());
						while($row4=mysql_fetch_object($result4)){
							$numofprogram++;
							echo "<tr>";
							echo "<td><pre><span id=\"whethershow\">$numofprogram.</span><a href='../../problem.php?id=$row4->program_id'>$row4->title</a>";
							echo "<h4>Description</h4>";
							echo "<p>$row4->description</p>";
							echo "</pre></td>";
							echo "</tr>";
						}
					?>
					</table>
					<?
						$querymain="SELECT `choosescore`,`judgescore`,`fillscore`,`prgans`,`prgfill`,`programscore` 
						FROM `exam` WHERE `exam_id`='$eid'";
						$resultmain=mysql_query($querymain) or die(mysql_error());
						$rowmain=mysql_fetch_array($resultmain);
						$choosescore=$rowmain[0];
						$judgescore=$rowmain[1];
						$fillscore=$rowmain[2];
						$prgans=$rowmain[3];
						$prgfill=$rowmain[4];
						$programscore=$rowmain[5];
						mysql_free_result($resultmain);
						$sumchoose=$choosescore*$numofchoose;
						$sumjudge=$judgescore*$numofjudge;
						$sumfill=$fillscore*$numoffill+$prgans*$numofprgans+$prgfill*$numofprgfill;
						$sumprogram=$programscore*$numofprogram;
						$sumquestion=$sumchoose+$sumjudge+$sumfill+$sumprogram;
					?>
					<table class="pull-left"  border=1 style="text-align:center">
					<thread>
						<th>题型</th>
						<th>每题(空)分数</th>
						<th>题(空)数</th>
						<th>总分</th>
					</thread>
					<tbody>
						<tr>
							<td>选择题</td>
							<td><?=$choosescore?></td>
							<td><?=$numofchoose?>道</td>
							<td><?=$sumchoose?>分</td>
						</tr>
						<tr>
							<td>判断题</td>
							<td><?=$judgescore?></td>
							<td><?=$numofjudge?>道</td>
							<td><?=$sumjudge?>分</td>
						</tr>
						<tr>
							<td>基础填空题</td>
							<td><?=$fillscore?></td>
							<td><?=$numoffill?>空</td>
							<td><?=$fillscore*$numoffill?>分</td>
						</tr>
						<tr>
							<td>写运行结果</td>
							<td><?=$prgans?></td>
							<td><?=$numofprgans?>道</td>
							<td><?=$prgans*$numofprgans?>分</td>
						</tr>
						<tr>
							<td>程序填空题</td>
							<td><?=$prgfill?></td>
							<td><?=$numofprgfill?>道</td>
							<td><?=$prgfill*$numofprgfill?>分</td>
						</tr>
						<tr>
							<td>程序设计题</td>
							<td><?=$programscore?></td>
							<td><?=$numofprogram?>道</td>
							<td><?=$sumprogram?>分</td>
						</tr>
						<tr>
							<td>总计</td>
							<td>-----</td>
							<td>-----</td>
							<td><?=$sumquestion?>分</td>
						</tr>
					</tbody>
					</table>
					</div>
					</div>
					<?
				}
				else
				{
					echo "<script language='javascript'>\n";
		    		echo "alert(\"Invaild type\");\n";  
	        		echo "history.go(-1);\n";
	        		echo "</script>";
				}
			}
			else
			{
				echo "<script language='javascript'>\n";
		    	echo "alert(\"Invaild data\");\n";  
	        	echo "history.go(-1);\n";
	        	echo "</script>";
			}
		}
	}
	else{
		echo "<script language='javascript'>\n";
	    echo "alert(\"Invaild path\");\n";  
        echo "history.go(-1);\n";
        echo "</script>";
	}
?>
<script src="../css/questionadd.js"></script>
<script type="text/javascript">
function chkinput(form)
{
	for(var i=0;i<form.length;i++)
	{
		if(form.elements[i].value=="")
		{
			alert("信息不能为空");
			form.elements[i].focus();
			return false;
		}
	}
	return(true);
}
// $(function(){
// 		var x=<?if(isset($numofprogram)) echo "1";else echo "0";?>;
// 		if(x==1)
// 		$('#whethershow').html("");
// });
$(function(){
	if($("#left").height()<700)
		$("#left").css("height",700)
	if($("#left").height()>$("#right").height()){
		$("#right").css("height",$("#left").height())
	}
	else{
		$("#left").css("height",$("#right").height())
	}
	var x=<?if(isset($numofprogram)) echo "1";else echo "0";?>;
		if(x==1)
		$('#whethershow').html("");
});
</script>
<?
	require_once("./teacher-footer.php");
?>