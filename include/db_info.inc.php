<?
	@session_start();
static 	$DB_HOST="127.0.0.1";
static 	$DB_NAME="jol";
static 	$DB_USER="jol";
static 	$DB_PASS="147258";
	// connect db 
static 	$OJ_NAME="SDIBT";
static 	$OJ_HOME="http://acm.sdibt.edu.cn/";
static 	$OJ_ADMIN="sdibtacm@126.com";
static 	$OJ_DATA="/home/judge/data";
static 	$OJ_BBS="discuss";//"bbs" for phpBB3 bridge or "discuss" for mini-forum
static  $OJ_ONLINE=true;
static  $OJ_LANG="en";
static  $OJ_SIM=true;
static  $OJ_DICT=true;
static  $OJ_LANGMASK=0; //1mC 2mCPP 4mPascal 8mJava
static  $OJ_EDITE_AREA=true;//true: syntax highlighting is active
static  $OJ_MAIL=true;     //true: mail function is available
static  $OJ_AUTO_SHARE=true;//true: One can view all AC submit if he/she has ACed it onece.
static  $OJ_VCODE=true;
if (isset($_SESSION['OJ_LANG'])) $OJ_LANG=$_SESSION['OJ_LANG'];
	if(mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS));
	else die('Could not connect: ' . mysql_error());
	// use db
	mysql_query("set names utf8");
	mysql_set_charset("utf8");
	
	if(mysql_select_db($DB_NAME));
	else die('Can\'t use foo : ' . mysql_error());
        date_default_timezone_set("PRC");
?>
