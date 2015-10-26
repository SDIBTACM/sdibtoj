<?
require_once("admin-header.php")
?>
<?
if (!(isset($_SESSION['administrator'])||isset($_SESSION['contest_creator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}




if(isset($_POST['do'])){
     require_once("../include/check_post_key.php");
	
    $dbhost = '127.0.0.1';
    $dbuser = 'ROOT';
    $dbpass = 'ROOT';
    $Vconn = mysql_connect($dbhost, $dbuser, $dbpass);
    if(! $Vconn )
    {
       die('Could not connect: ' . mysql_error());
    }
   if( mysql_select_db('vhoj'));
   else
     die('Can\'t use foo : ' . mysql_error()); 	 
    $user_id=mysql_real_escape_string($_POST['user_id']);
	
	$sql="SELECT * FROM `t_user` WHERE `C_USERNAME`='$user_id'";
	$result=mysql_query($sql);
	$row_cnt=mysql_num_rows($result);
	if ($row_cnt==0){ 
	   echo "no such user\n";
	   exit(0);
	
	}
	
	$sql="update `t_user` set `C_PASSWORD`='e10adc3949ba59abbe56e057f20f883e' where `C_USERNAME`='$user_id'";
	$retval = mysql_query( $sql, $Vconn );
	if(! $retval )
	{
	die('Could not update data: ' . mysql_error());
	}
	echo "Updated data successfully\n";
	mysql_close($Vconn);  
	}
?>
<form action='changevjpass.php' method=post>
	<b>Change Password:</b><br />
	User:<input type=text size=10 name="user_id"><br />
	<?require_once("../include/set_post_key.php");?>
        <input type='hidden' name='do' value='do'>
	<input type=submit value='Change'>
</form>
