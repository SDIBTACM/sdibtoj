<?php
	function alertmsg($msg,$url=null,$isback=1){
		echo "<script language='javascript'>\n";
		if($msg!="") 
			echo "alert('{$msg}');\n";
		if($isback==0)
			echo "location='{$url}';\n";
		else
			echo "history.go(-1);\n";
		echo "</script>";
	}
	function test_input($data){
		$data = trim($data);
  		//$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		$data = mysql_real_escape_string($data);
  		return $data;
	}
	function checkAdmin($val,$creator=null,$priflag=null){
		if($val==5){
			if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']||$priflag==1))
				return true;
			return false;
		}
		else if($val==4){
			if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
				return true;
			else
				return false;
		}
		else if($val==3){
			if(!(isset($_SESSION['administrator'])
				||isset($_SESSION['contest_creator'])
				||isset($_SESSION['problem_editor']))){
				echo "You have no privilege";
				echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
				exit(1);
			}
		}
		else if($val==2){
			if(!(isset($_SESSION['administrator'])
				||isset($_SESSION['contest_creator']))){
				echo "You have no privilege";
				echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
				exit(1);
			}
		}
		else if($val==1){
			if(isset($_SESSION['administrator']))
				return true;
			else
				return false;
		}
	}
	function checkuserid(){
		header("Content-type: text/html; charset=utf-8");
		if(!isset($_SESSION['user_id'])){
			echo "听说有个人他没有登陆,然后他挂科了^_^<br/>";
			echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
			exit(0);
		}
	}
?>
