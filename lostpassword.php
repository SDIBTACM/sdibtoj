<?php
        require_once('./include/db_info.inc.php');
#        require_once('./include/setlang.php');
        $view_title= "Welcome To Online Judge";

require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
$lost_user_id=$_POST['user_id'];
$lost_email=$_POST['email'];
 $vcode=trim($_POST['vcode']);
    if($lost_user_id&&($vcode!= $_SESSION["vcode"]||$vcode==""||$vcode==null) ){
                echo "<script language='javascript'>\n";
                echo "alert('Verify Code Wrong!');\n";
                echo "history.go(-1);\n";
                echo "</script>";
                exit(0);
    }
  if(get_magic_quotes_gpc()){
        $lost_user_id=stripslashes($lost_user_id);
        $lost_email=stripslashes($lost_email);
  }
  $sql="SELECT `email` FROM `users` WHERE `user_id`='".mysql_real_escape_string($lost_user_id)."'";
                $result=mysql_query($sql);
                $row = mysql_fetch_array($result);
 if($row && $row['email']==$lost_email&&strpos($lost_email,'@')){
   $_SESSION['lost_user_id']=$lost_user_id;
   $_SESSION['lost_key']=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,16));

  
	require_once "include/email.class.php";
	//******************** 配置信息 ********************************
	$smtpserver = "smtp.126.com";//SMTP服务器
	$smtpserverport =25;//SMTP服务器端口
	$smtpusermail = "test@126.com";//SMTP服务器的用户邮箱
	$smtpemailto = $row['email'];//发送给谁
	$smtpuser = "test@126.com";//SMTP服务器的用户帐号
	$smtppass = "test";//SMTP服务器的用户密码
	$mailtitle = "OJ系统密码重置激活";//邮件主题
	$mailcontent = "$lost_user_id:\n您好！\n您在OJ系统选择了找回密码服务,为了验证您的身份,请将下面字串输入口令重置页面以确认身份:".$_SESSION['lost_key']."\n\n\n山东工商学院在线评测系统";//邮件内容
	$mailtype = "TXT";//邮件格式（HTML/TXT）,TXT为文本邮件
	//************************ 配置信息 ****************************
	$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
	$smtp->debug =false;//是否显示发送的调试信息
	$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
        require("./lostpassword2.php");

 }else{
?>

<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $view_title?></title>
        <link rel=stylesheet href='include/hoj.css' type='text/css'>
</head>
<body>
<div id="wrapper">
        <?php require_once("oj-header.php");?>
<div id=main>
        <form action=lostpassword.php method=post>
        <center>
        <table width=400 algin=center>
        <tr><td width=200><?php echo "用户名"?>:<td width=200><input name="user_id" type="text" size=20></tr>
        <tr><td><?php echo "E-mail"?>:<td><input name="email" type="text" size=20></tr>
        <?php if($OJ_VCODE){?>
                <tr><td><?php echo "code"?>:</td>
                        <td><input name="vcode" size=4 type=text><img alt="click to change" src=vcode.php onclick="this.src='vcode.php#'+Math.random()">*</td>
                </tr>
                <?php }?>
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
<?
}
?>
