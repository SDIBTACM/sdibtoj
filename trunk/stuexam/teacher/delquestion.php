<?	
	@session_start();
	if(!isset($_SESSION['user_id']))
	{
		echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
		exit(0);
	}
	if (!(isset($_SESSION['administrator'])
		||isset($_SESSION['contest_creator'])
		||isset($_SESSION['problem_editor']))){
	echo "You have no privilege";
	echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
	exit(1);
	}
	require_once("../../include/db_info.inc.php");
	if(!isset($_GET['getkey']))
		exit(1);
	require_once("../../include/check_get_key.php");
?>
<?
	if(isset($_GET['type'])&&isset($_GET['id'])){
		$type=intval($_GET['type']);
		$id=intval($_GET['id']);
		if(filter_var($type, FILTER_VALIDATE_INT)&&filter_var($id, FILTER_VALIDATE_INT)&&$id>0){
			if($type==1)//delete choose
			{
				$prisql="SELECT `creator` FROM `ex_choose` WHERE `choose_id`='$id'";
				$priresult=mysql_query($prisql) or die(mysql_error());
				$creator=mysql_result($priresult, 0);
				mysql_free_result($priresult);
				if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
				{
					echo "<script language='javascript'>\n";
	    			echo "alert(\"You have no privilege to delete it!\");\n";  
        			echo "history.go(-1);\n";
        			echo "</script>";
				}
				else
				{
					$query="DELETE FROM `ex_choose` WHERE `choose_id`='$id'";
					mysql_query($query) or die(mysql_error());
					//$query="DELETE FROM `exp_choose` WHERE `choose_id`='$id'";
					$query="DELETE FROM `exp_question` WHERE `question_id`='$id' AND `type`='1'";
					mysql_query($query) or die(mysql_error());
					echo "<script language=javascript>location='admin_choose.php';</script>";
				}
			}
			else if($type==2)//delete judge
			{
				$prisql="SELECT `creator` FROM `ex_judge` WHERE `judge_id`='$id'";
				$priresult=mysql_query($prisql) or die(mysql_error());
				$creator=mysql_result($priresult, 0);
				mysql_free_result($priresult);
				if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
				{
					echo "<script language='javascript'>\n";
	    			echo "alert(\"You have no privilege to delete it!\");\n";  
        			echo "history.go(-1);\n";
        			echo "</script>";
				}
				else
				{
					$query="DELETE FROM `ex_judge` WHERE `judge_id`='$id'";
					mysql_query($query) or die(mysql_error());
					//$query="DELETE FROM `exp_judge` WHERE `judge_id`='$id'";
					$query="DELETE FROM `exp_question` WHERE `question_id`='$id' AND `type`='2'";
					mysql_query($query) or die(mysql_error());
					echo "<script language=javascript>location='admin_judge.php';</script>";
				}
			}
			else if($type==3)//delete fill
			{
				$prisql="SELECT `creator` FROM `ex_fill` WHERE `fill_id`='$id'";
				$priresult=mysql_query($prisql) or die(mysql_error());
				$creator=mysql_result($priresult, 0);
				mysql_free_result($priresult);
				if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
				{
					echo "<script language='javascript'>\n";
	    			echo "alert(\"You have no privilege to delete it!\");\n";  
        			echo "history.go(-1);\n";
        			echo "</script>";
				}
				else
				{
					$query="DELETE FROM `ex_fill` WHERE `fill_id`='$id'";
					mysql_query($query) or die(mysql_error());
					$query="DELETE FROM `fill_answer` WHERE `fill_id`='$id'";
					mysql_query($query) or die(mysql_error());
					//$query="DELETE FROM `exp_fill` WHERE `fill_id`='$id'";
					$query="DELETE FROM `exp_question` WHERE `question_id`='$id' AND `type`='3'";
					mysql_query($query) or die(mysql_error());
					echo "<script language=javascript>location='admin_fill.php';</script>";
				}
			}
			else if($type==9)//delete exam
			{
				$prisql="SELECT `creator` FROM `exam` WHERE `exam_id`='$id'";
				$priresult=mysql_query($prisql) or die(mysql_error());
				$creator=mysql_result($priresult,0);
				mysql_free_result($priresult);
				if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
				{
					echo "<script language='javascript'>\n";
	    			echo "alert(\"You have no privilege to delete it!\");\n";  
        			echo "history.go(-1);\n";
        			echo "</script>";
				}
				else
				{
					$query="DELETE FROM `exam` WHERE `exam_id`='$id'";
					mysql_query($query) or die(mysql_error());
					//$query="DELETE FROM `exp_choose` WHERE `exam_id`='$id'";
					$query="DELETE FROM `exp_question` WHERE `exam_id`='$id'";
					mysql_query($query) or die(mysql_error());
					$query="DELETE FROM `ex_privilege` WHERE `rightstr`='e$id'";
					mysql_query($query) or die(mysql_error());
					echo "<script language=javascript>location='./';</script>";
				}
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
	else{
		echo "<script language='javascript'>\n";
	    echo "alert(\"Invaild path\");\n";  
        echo "history.go(-1);\n";
        echo "</script>";
	}
?>