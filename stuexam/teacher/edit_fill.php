<?
	require_once("./teacher-header.php");
?>
<?
	function test_input($data){
		$data = trim($data);
  		//$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		$data = mysql_real_escape_string($data);
  		return $data;
	}
	if(isset($_POST['fill_des']))
	{
		require_once("../../include/check_post_key.php");
		$question=test_input($_POST['fill_des']);
		$point=test_input($_POST['point']);
		$easycount=intval($_POST['easycount']);
		$answernum=intval($_POST['numanswer']);
		$kind=intval($_POST['kind']);
		$isprivate=intval($_POST['isprivate']);
		//$answernum=count($_POST)-6;
		//important the first is the postkey the second is the fill_des 
		//the third is the hidden fillid
		//the forth is the point the fifth is the easycount the sixth is kind
		$fillid=intval($_POST['fillid']);
		$prisql="SELECT `creator`,`isprivate` FROM `ex_fill` WHERE `fill_id`='$fillid'";
		$priresult=mysql_query($prisql) or die(mysql_error());
		$prirow=mysql_fetch_array($priresult);
		$creator=$prirow['creator'];
		$private2=$prirow['isprivate'];
		mysql_free_result($priresult);
		if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
		{
			echo "<script language='javascript'>\n";
	    	echo "alert(\"You have no privilege to modify it!\");\n";  
        	echo "location='edit_fill.php?id=$fillid'\n";
        	echo "</script>";
		}
		else if($private2==2&&!isset($_SESSION['administrator']))
		{
			echo "<script language='javascript'>\n";
	    	echo "alert(\"You have no privilege to modify it!\");\n";  
        	echo "location='admin_fill.php'\n";
        	echo "</script>";
		}
		else
		{
			$query="UPDATE `ex_fill` SET `question`='".$question."',`answernum`='$answernum',`point`='".$point."',`easycount`='$easycount',
			`kind`='$kind',`isprivate`='$isprivate' WHERE `fill_id`='$fillid'";
			mysql_query($query) or die(mysql_error());
			$query="DELETE FROM `fill_answer` WHERE `fill_id`='$fillid'";
			mysql_query($query) or die(mysql_error());
			for($i=1;$i<=$answernum;$i++)
			{
				$answer=test_input($_POST["answer$i"]);
				$query="INSERT INTO `fill_answer` 
				(`fill_id`,`answer_id`,`answer`) 
				VALUES('$fillid','$i','".$answer."')";
				mysql_query($query) or die(mysql_error());
			}
			echo "<script>alert(\"修改成功\");</script>";
			echo "<script>window.location.href=\"./admin_fill.php\";</script>";
		}
	}
	else
	{
		if(isset($_GET['id']))
		{
			$id=intval($_GET['id']);
			if(filter_var($id, FILTER_VALIDATE_INT))
			{
				$query="SELECT `question`,`answernum`,`point`,`creator`,`easycount`,`kind`,`isprivate` FROM `ex_fill` 
				WHERE `fill_id`='$id'";
				$result=mysql_query($query) or die(mysql_error());
				$row_cnt=mysql_num_rows($result);
				if($row_cnt)
				{
					$row=mysql_fetch_array($result);
					mysql_free_result($result);
					$question=$row['question'];
					$answernumC=$row['answernum'];
					$point=$row['point'];
					$creator=$row['creator'];
					$easycount=$row['easycount'];
					$kind=$row['kind'];
					$isprivate=$row['isprivate'];
					if($isprivate==2&&!isset($_SESSION['administrator']))
					{
						echo "<script language='javascript'>\n";
	    				echo "alert(\"You have no privilege!\");\n";  
        				echo "location='admin_fill.php'\n";
        				echo "</script>";
					}
					if(!isset($_SESSION['administrator']))
					{
						if($isprivate==1&&$creator!=$_SESSION['user_id'])
						{
							echo "<script language='javascript'>\n";
	    					echo "alert(\"You have no privilege!\");\n";  
        					echo "location='admin_fill.php'\n";
        					echo "</script>";
						}
					}
				}
				else
				{
					mysql_free_result($result);
					echo "<script language='javascript'>\n";
	    			echo "alert(\"No such problem\");\n";  
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
		else
		{
			echo "<script language='javascript'>\n";
	    	echo "alert(\"Invaild path\");\n";  
        	echo "history.go(-1);\n";
        	echo "</script>";
		}
?>
<div>
<div class="leftmenu pull-left" id="left">
<ul class="nav nav-pills bs-docs-sidenav">
	<li class=""><a href="./">考试管理</a></li>
	<li class=""><a href="admin_choose.php">选择题管理</a></li>
	<li class=""><a href="admin_judge.php">判断题管理</a></li>
	<li class="active"><a href="admin_fill.php">填空题管理</a></li>
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
	<h2 style="text-align:center">查看修改填空题</h2>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" onSubmit="return chkinput(this)">
		<div class="pull-left span8">
			<label>题目描述:</label>
			<textarea style="width:490px;height:160px;overflow-x:visible;overflow-y:visible;" 
			name="fill_des"><?=$question?></textarea>
		</div>
		<?require_once("../../include/set_post_key.php");?>
		<label><font color=red>请按如下格式编写题目中需要填写的部分:</font></label>
		<textarea style="width:100pxx;height:100px;overflow-x:visible;overflow-y:visible;" 
		disabled>若x和n均是int型变量,且x和n的初值均为5,则计算表达式x+=n++后x的值为__(1)__,n的值为__(2)__
		</textarea>
		<div class="span8">
			<label>填空题类型:</label>
			<select name="kind" id="kind" onchange="showspan()">
				<option value="1" <?echo $kind==1?"selected":""?> >基础填空题</option>
				<option value="2" <?echo $kind==2?"selected":""?> >写运行结果</option>
				<option value="3" <?echo $kind==3?"selected":""?> >程序填空</option>
			</select>
			<label for="point">知识点:</label>
			<select name="point" id="point">
			<?
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
			<strong>答案:<font color=red id="warnmsg"></font></strong>
			<input class="btn" type="button" value="添加答案空" onclick="addinput()" />
			<input class="btn" type="button" value="删除答案空" onclick="delinput()" />
		</div>
		<input type="hidden" name="numanswer" id="numanswer" value="<?=$answernumC?>">
		<div class="span4" id="Content">
		<?
			if($answernumC)
			{
				$query="SELECT `answer_id`,`answer` FROM `fill_answer` WHERE `fill_id`='$id' ORDER BY `answer_id`";
				$result=mysql_query($query) or die(mysql_error());
				while($myrow=mysql_fetch_object($result))
				{
					echo "<input type=\"text\" maxlength=\"100\" name=\"answer$myrow->answer_id\"
				 	id=\"answer$myrow->answer_id\" value=\"$myrow->answer\">";
				}
				mysql_free_result($result);
			}
		?>
		</div>
		<div class="span8">
			<input type="hidden" value="<?=$id?>" name="fillid">
			<input type="submit" value="提交" class="mybutton">
			<input type="button" value="返回" onclick="javascript:history.go(-1);" class="mybutton">
		</div>
	</form>
</div>
</div>
<script type="text/javascript">
var cont=document.getElementById("Content");
var num_answer=<?=$answernumC?>;
function addinput()
{
	if(num_answer<8)
	{
		num_answer++;
		var Newinput=document.createElement("input");
		Newinput.setAttribute("type","text");
		Newinput.setAttribute("maxlength","100");
		Newinput.setAttribute("name","answer"+num_answer);
		Newinput.setAttribute("id","answer"+num_answer);
		cont.appendChild(Newinput);
		$('#numanswer').val(num_answer);
	}
	else
		alert("空数已达到上限");
}
function delinput()
{
	if(num_answer>0)
	{
		var index=cont.getElementsByTagName("input").length;
		var node = document.getElementById("answer"+index);
		node.parentNode.removeChild(node);
		num_answer--;
		$('#numanswer').val(num_answer);
	}
	else
	{
		alert("Nothing to be deleted");
	}
}
function chkinput(form)
{
	if(form.fill_des.value=="")
	{
		alert("请输入题目描述");
		form.fill_des.focus();
		return(false); 
	}
	for(var i=1;i<form.length;i++)
	{
		if(form.elements[i].value=="")
		{
			alert(form.elements[i].name+"不能为空");
			form.elements[i].focus();
			return false;
		}
	}
	return(true);
}
function showspan()
{
	if($('#kind').val()==1)
		$('#warnmsg').html('(*请确保下面文本框个数与题目中的填空数相同)');
	else if($('#kind').val()==2)
		$('#warnmsg').html('(*请确保空数个数与程序在编译器端运行结果的行数一致)');
	else if($('#kind').val()==3)
		$('#warnmsg').html('(*请确保下面文本框个数与题目中的填空数相同)');
}
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
	if($('#kind').val()==1)
		$('#warnmsg').html('(*请确保下面文本框个数与题目中的填空数相同)');
	else if($('#kind').val()==2)
		$('#warnmsg').html('(*请确保空数个数与程序在编译器端运行结果的行数一致)');
	else if($('#kind').val()==3)
		$('#warnmsg').html('(*请确保下面文本框个数与题目中的填空数相同)');
});
</script>
<?
}
	require_once("./teacher-footer.php");
?>
