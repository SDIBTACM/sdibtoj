<?php
        require_once('./include/db_info.inc.php');
        $view_title= "Welcome To Online Judge";
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
$lost_user_id=$_POST['user_id'];
$lost_key=$_POST['lost_key'];
  if(get_magic_quotes_gpc()){
        $lost_user_id=stripslashes($lost_user_id);
        $lost_key=stripslashes($lost_key);
  }
  $sql=" update `users` set password='".pwGen($lost_key)."'WHERE `user_id`='".mysql_real_escape_string($lost_user_id)."'";
  if(
   $_SESSION['lost_user_id']==$lost_user_id &&
   $_SESSION['lost_key']==$lost_key
  ){
         $result=mysql_query($sql);
    $view_errors="Password Reseted to the key you've just inputed.Click <a href=index.php>Here</a> to login!";
       echo $view_errors;
       exit(0);
  }else{
         $view_errors="Password Reset Fail";
  }
?>
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo "welcome to SDIBT"?></title>
        <link rel=stylesheet href='include/hoj.css'  type='text/css'>
</head>
<body>
<div id="wrapper">
        <?php require_once("oj-header.php");?>
<div id=main>
        <form action=lostpassword2.php method=post>
        <center>
        <table width=400 algin=center>
        <tr><td width=200><?php echo "用户名"?>:<td width=200><input name="user_id" type="text" size=20></tr>
        <tr><td><?php echo "邮箱收到的验证码"?>:<td><input name="lost_key" type="text" size=20></tr>
        <tr><td><td><input name="submit" type="submit" size=10 value="Submit">
</tr>
        </table>
        <center>
</form>

<div id=foot>
        <?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
lostpassword2.php
