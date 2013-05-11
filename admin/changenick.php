<?php require_once("admin-header.php");?>
<?php if (!(isset($_SESSION['administrator'])) ){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
if(isset($_POST['do'])){
	//echo $_POST['user_id'];
	require_once("../include/check_post_key.php");
	//echo $_POST['passwd'];
	require_once("../include/my_func.inc.php");
	
	$user_id=$_POST['user_id'];
    $passwd =$_POST['nick'];
    if (get_magic_quotes_gpc ()) {
		$user_id = stripslashes ( $user_id);
		$nick = stripslashes ( $nick);
	}
	$user_id=mysql_real_escape_string($user_id);
	
	$sql="update `users` set `nick`='$passwd' where `user_id`='$user_id'  and user_id not in( select user_id from privilege where rightstr='administrator') ";
	mysql_query($sql);
	if (mysql_affected_rows()==1) echo "NickName Changed!";
  else echo "No such user! or He/Her is an administrator!";
}
?>
<form action='changenick.php' method=post>
	<b>Change NickName:</b><br />
	User:<input type=text size=10 name="user_id"><br />
	Nick:<input type=text size=10 name="nick"><br />
	<?php require_once("../include/set_post_key.php");?>
	<input type='hidden' name='do' value='do'>
	<input type=submit value='Change'>
</form>
