<?php require_once("admin-header.php");
//require_once("../include/check_get_key.php");
if (!(isset($_SESSION['administrator']))){
        echo "<a href='../loginpage.php'>Please Login First!</a>";
        exit(1);
}
?>
<?php $uid=($_GET['uid']);
      $logtime=date('Y-m-d G:i:s',$_GET['logtime']);
      //echo $logtime;
$sql="DELETE  FROM `loginlog` WHERE `user_id`=\"$uid\" and `time`=\"$logtime\"";
     //echo $sql;
mysql_query($sql) or die(mysql_error());
?>
<script language=javascript>
        history.go(-1);
</script>

