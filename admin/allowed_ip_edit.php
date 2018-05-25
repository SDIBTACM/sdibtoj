<?php
require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}
define('IS_POST', strstr($_SERVER['REQUEST_METHOD'], 'POST') ? 1 : 0);
define('IS_GET', strstr($_SERVER['REQUEST_METHOD'], 'GET') ? 1 : 0);
$row = null;

if (IS_POST) {
    require_once("../include/check_post_key.php");
    $new = $_POST['ip'];
    $new = trim($new);
    $fp = fopen("allowed_ip", "w");
    fwrite($fp, $new);
    fclose($fp);
    echo "change success";
} else if (IS_GET) {

    try {
        $fp = fopen("allowed_ip", "r");
        $row = fread($fp,filesize("allowed_ip"));
    } catch (Exception $e) {

    }
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet href='../include/hoj.css' type='text/css'>
<form method=POST action="">
    <h3>set IP</h3>
    <pre><textarea name = "ip" rows=25 cols=20><?=$row?></textarea></pre>
    <pre>请按照 <a href="https://zh.wikipedia.org/wiki/%E6%97%A0%E7%B1%BB%E5%88%AB%E5%9F%9F%E9%97%B4%E8%B7%AF%E7%94%B1">CIDR</a> 的表示方法输入,例如：
        192.168.1.0/24 代表允许 192.168.1.0 - 192.168.1.255 范围内的 IP 登录
        172.16.0.0/12 代表允许 172.16.0.0 - 172.31.255.255 范围内的 IP 登录
        网段由子网掩码计算而来，可在 <a href="http://help.bitscn.com/ip/">IP地址和网络转换器</a> 中进行计算
    </pre>
    <?require_once("../include/set_post_key.php");?>
    <input type=submit value=Submit name=submit>
</form>
