<?require_once("oj-header.php")?>
<title>LOGIN</title>
<br><br>
<?
if (isset($_SESSION['user_id'])){
        echo "<a href=logout.php>Please logout First!</a>";
        exit(1);
}
?>
<form action=loginD.php method=post>
        <center>
        <table width=400 algin=center>
        <tr><td width=25%><?=$MSG_USER_ID?>:<td width=75%><input name="user_id" type="text" size=20></tr>
        <tr><td><?=$MSG_PASSWORD?>:<td><input name="password" type="password" size=20></tr>
        <?php if($OJ_VCODE){?>
         <tr><td>Code:
                        <td><input name="vcodeD" size=4 type=text>
                        <img src=vcode.php align="absmiddle" onclick="javascript:newgdcode(this,this.src);">
                </tr>
          <?php }?>
         <tr><td><td><input name="submit" type="submit" size=10 value="Submit">
        <a href="lostpassword.php">Lost Password</a></tr>
        </table>
        <center>
</form>
<br><br>
<script language="javascript">
    function newgdcode(obj, url) {
        obj.src = 'vcode.php?nowtime=' + new Date().getTime();
    }
</script>
<?require_once("oj-footer.php");?>                             
