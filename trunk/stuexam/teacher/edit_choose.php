<?php
	require_once("./teacher-header.php");
?>
<?php
	if(isset($_POST['choose_des']))
	{
		require_once("../../include/check_post_key.php");
		$arr['question']=test_input($_POST['choose_des']);
		$arr['ams']=test_input($_POST['ams']);
		$arr['bms']=test_input($_POST['bms']);
		$arr['cms']=test_input($_POST['cms']);
		$arr['dms']=test_input($_POST['dms']);
		$arr['point']=test_input($_POST['point']);
		$arr['answer']=$_POST['answer'];
		$arr['easycount']=intval($_POST['easycount']);
		$arr['isprivate']=intval($_POST['isprivate']);
		$chooseid=intval($_POST['chooseid']);
		
		$prisql="SELECT `creator`,`isprivate` FROM `ex_choose` WHERE `choose_id`='$chooseid'";
		$prirow=fetchOne($prisql);
		$creator=$prirow['creator'];
		$private2=$prirow['isprivate'];
		if(checkAdmin(4,$creator))
		{
			alertmsg("You have no privilege to modify it!","edit_choose.php?id={$chooseid}",0);
		}
		else if($private2==2&&!checkAdmin(1))
		{
			alertmsg("You have no privilege to modify it!","admin_choose.php",0);
		}
		else
		{
			Update('ex_choose',$arr,"choose_id={$chooseid}");
			alertmsg("修改成功","admin_choose.php",0);
			unset($arr);
		}
	}
	else
	{
		if(isset($_GET['id']))
		{
			$id=intval($_GET['id']);
			if(filter_var($id, FILTER_VALIDATE_INT))
			{
				$query="SELECT `question`,`ams`,`bms`,`cms`,`dms`,`answer`,`creator`,`point`,`easycount`,`isprivate` FROM `ex_choose` 
				WHERE `choose_id`='$id'";
				$row=fetchOne($query);
				if($row)
				{
					$question=$row['question'];
					$ams=$row['ams'];
					$bms=$row['bms'];
					$cms=$row['cms'];
					$dms=$row['dms'];
					$answer=$row['answer'];
					$creator=$row['creator'];
					$point=$row['point'];
					$easycount=$row['easycount'];
					$isprivate=$row['isprivate'];
					if($isprivate==2&&!checkAdmin(1))
					{
						alertmsg("You have no privilege!","admin_fill.php",0);
					}
					if(!checkAdmin(1))
					{
						if($isprivate==1&&$creator!=$_SESSION['user_id'])
						{
							alertmsg("You have no privilege!","admin_choose.php",0);
						}
					}
				}
				else{
					alertmsg("No such problem");
				}
			}
			else{
				alertmsg("Invaild data");
			}
		}
		else{
			alertmsg("Invaild path");
		}
?>
<script language="javascript">
function chkinput(form){
	if(form.choose_des.value==""){
	alert("请输入题目描述");
	form.choose_des.focus();
	return(false); 
	}
	if(form.ams.value==""){
	alert("请输入A选项!");
	form.ams.focus();
	return(false); 
	}
	if(form.bms.value==""){
	alert("请输入B选项!");
	form.bms.focus();
	return(false); 
	}
	if(form.cms.value==""){
	alert("请输入C选项!");
	form.cms.focus();
	return(false); 
	}
	if(form.dms.value==""){
	alert("请输入D选项!");
	form.dms.focus();
	return(false); 
	}
	if(form.point.value==""){
	alert("请输入知识点!");
	form.point.focus();
	return(false); 
	}
	if(form.answer.value==""){
	alert("请输入答案!");
	return(false); 
	}
	return(true);
}
</script>
<div>
<div class="leftmenu pull-left" id="left">
<ul class="nav nav-pills bs-docs-sidenav">
	<li class=""><a href="./">考试管理</a></li>
	<li class="active"><a href="admin_choose.php">选择题管理</a></li>
	<li class=""><a href="admin_judge.php">判断题管理</a></li>
	<li class=""><a href="admin_fill.php">填空题管理</a></li>
	<?php
		if(checkAdmin(1))
		{
			echo "<li><a href=\"admin_point.php\">知识点管理</a></li>";
		}
	?>
	<li><a href="../">退出管理页面</a></li>
</ul>
</div>
<div class="container" style="margin-left:23%" id="right">
	<h2 style="text-align:center">查看修改选择题</h2>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" onSubmit="return chkinput(this)">
		<div class="pull-left span4">
			<label>题目描述:</label>
			<textarea style="width:250px;height:295px;overflow-x:visible;overflow-y:visible;" 
			name="choose_des"><?=$question?></textarea>
		</div>
		<?require_once("../../include/set_post_key.php");?>
		<div class="span4">
			<label>选项描述:</label>
			选项A: <textarea name="ams" style="width:319px;height:40px"><?=$ams?></textarea></br>
			选项B: <textarea name="bms" style="width:319px;height:40px"><?=$bms?></textarea></br>
			选项C: <textarea name="cms" style="width:319px;height:40px"><?=$cms?></textarea></br>
			选项D: <textarea name="dms" style="width:319px;height:40px"><?=$dms?></textarea></br>
			<strong>答案:</strong>
			A<input type="radio" name="answer" value="A" <?=($answer=='A')?"checked":""?> >&nbsp;
			B<input type="radio" name="answer" value="B" <?=($answer=='B')?"checked":""?> >&nbsp;
			C<input type="radio" name="answer" value="C" <?=($answer=='C')?"checked":""?> >&nbsp;
			D<input type="radio" name="answer" value="D" <?=($answer=='D')?"checked":""?> >&nbsp;
		</div>
		<div class="span8">
			<input type="hidden" value="<?=$id?>" name="chooseid">
			<label for="point">知识点:</label>
			<select name="point" id="point">
			<?php
				$sql="SELECT * FROM `ex_point`";
				$result=mysql_query($sql) or die(mysql_error());
				while($row=mysql_fetch_object($result))
				{
					if($row->point==$point)
						echo "<option value=\"$row->point\" selected>$row->point</option>";
					else
						echo "<option value=\"$row->point\">$row->point</option>";
				}
				mysql_free_result($result);
			?>
			</select>
			<label for="easycount">难度系数:</label>
			<select name="easycount" id="easycount">
				<option value="0" <?echo $easycount==0?"selected":""?> >0</option>
				<option value="1" <?echo $easycount==1?"selected":""?> >1</option>
				<option value="2" <?echo $easycount==2?"selected":""?> >2</option>
				<option value="3" <?echo $easycount==3?"selected":""?> >3</option>
				<option value="4" <?echo $easycount==4?"selected":""?> >4</option>
				<option value="5" <?echo $easycount==5?"selected":""?> >5</option>
				<option value="6" <?echo $easycount==6?"selected":""?> >6</option>
				<option value="7" <?echo $easycount==7?"selected":""?> >7</option>
				<option value="8" <?echo $easycount==8?"selected":""?> >8</option>
				<option value="9" <?echo $easycount==9?"selected":""?> >9</option>
				<option value="10" <?echo $easycount==10?"selected":""?> >10</option>
			</select>
			<label for="isprivate">请选题库类型:</label>
			<select name="isprivate" id="isprivate" onchange="showmsg()">
				<option value="0" <?echo $isprivate==0?"selected":""?> >公共题库</option>
				<option value="1" <?echo $isprivate==1?"selected":""?> >私人题库</option>
				<option value="2" <?echo $isprivate==2?"selected":""?> >系统隐藏</option>
			</select><strong><font color=red id="msg"></font></strong><br/>
			<input type="submit" value="提交" class="mybutton">
			<input type="button" value="返回" onclick="javascript:history.go(-1);" class="mybutton">
		</div>
	</form>
</div>
</div>
<script type="text/javascript">
function showmsg()
{
	if($('#isprivate').val()==0)
		$('#msg').html('(*公共题库所有人都可见)');
	else if($('#isprivate').val()==1)
		$('#msg').html('(*私人题库仅限本人和最高管理员可见)');
	else if($('#isprivate').val()==2)
		$('#msg').html('(*系统隐藏选择确认后,仅限最高管理员可以查看和修改，请谨慎选择和查看)');
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
}
	require_once("./teacher-footer.php");
?>