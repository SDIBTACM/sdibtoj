<?
setcookie("CarbonBBS_UserID", null, 1, '/forum/', null, false, true);
setcookie("CarbonBBS_UserExpirationTime", null, 1, '/forum/', null, false, true);
setcookie("CarbonBBS_UserCode", null, 1, '/forum/', null, false, true);

session_start();
unset($_SESSION['user_id']);
session_destroy();

echo "<script language='javascript'>\n";
echo "history.go(-1);\n";
echo "</script>";
?>
