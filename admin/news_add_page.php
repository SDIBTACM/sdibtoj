<?require_once("../include/db_info.inc.php");?>
<?require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}
?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>New Problem</title>
</head>
<body leftmargin="30" >

<form method=POST action=news_add.php>

<p align=left>Post a News</p>
<p align=left>Title:<input type=text name=title size=71></p>

<p align=left>Content:<br>
<textarea name="content"></textarea>
</p>
    <?require_once("../include/set_post_key.php");?>
<input type=submit value=Submit name=submit>
</div></form>
<script>
    CKEDITOR.replace('content');
</script>
<p>
<?require_once("../oj-footer.php");?>
</body></html>

