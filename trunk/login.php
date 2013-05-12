<?
	require_once("./include/db_info.inc.php");
       require_once("./include/my_func.inc.php");
	$user_id=mysql_escape_string($_POST['user_id']);
	$password=$_POST['password'];
	session_destroy();
	session_start();
        $sql="SELECT `school`,`email`,`nick` FROM `users` WHERE `user_id`='$user_id'";
	$result=mysql_query($sql);
	$row_cnt=mysql_num_rows($result);
	if ($row_cnt==0){ 
               echo "<script language='javascript'>\n";
	       echo "alert('No such user');\n";  
               echo "history.go(-1);\n";
                echo "</script>";

              exit(0);
	}


	$sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='".$user_id."'";
	$result=mysql_query($sql);
	$row = mysql_fetch_array($result);
         if ($row && pwCheck($password,$row['password']))	{	
	 //	$_SESSION['user_id']=$row['user_id'];
	       	mysql_free_result($result);
                 $password1=pwGen($password);
                 if($OJ_VIP_CONTEST)
                 {           
                    $today=date('Y-m-d');
                    $ip1=$_SERVER['REMOTE_ADDR'];
                    $sql="SELECT * from `loginlog` WHERE `user_id`='$user_id' and `time`>='$today' and ip<>'$ip1' and user_id not in( select user_id from privilege where rightstr='administrator') order by time DESC limit 0,1 ";
              // echo $sql;
                    $result=mysql_query($sql);
                    $row_cnt=mysql_num_rows($result);
                    if($row_cnt>0)
                    {  
                       $row=mysql_fetch_row($result);
                       echo "<script language='javascript'>\n";
                    echo "alert('Do not login in diff machine,Please Contact administrator');\n";  
                        echo "history.go(-1);\n";
                        echo "</script>";
                        exit(0);

                    }
                 }
	          $_SESSION['user_id']=$row['user_id'];
                   mysql_free_result($result);

 

	         $sql="INSERT INTO `loginlog` VALUES('$user_id','$password1','".$_SERVER['REMOTE_ADDR']."',NOW())";
	@mysql_query($sql) or die(mysql_error());
        	$sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".$_SESSION['user_id']."'";
		$result=mysql_query($sql);
		echo mysql_error();
		while ($row=mysql_fetch_assoc($result))
			$_SESSION[$row['rightstr']]=true;
//		$_SESSION['ac']=Array();
//		$_SESSION['sub']=Array();
		echo "<script language='javascript'>\n";
		echo "history.go(-2);\n";
		echo "</script>";
	}else{
		mysql_free_result($result);
		echo "<script language='javascript'>\n";
		echo "alert('Password Wrong!');\n";
		echo "history.go(-1);\n";
		echo "</script>";
	}
?>
