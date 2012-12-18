<?php
    require_once('./include/db_info.inc.php');
//	require_once('./include/setlang.php');
	$view_title= "Source Code";
   
require_once("./include/const.inc.php");
if (!isset($_GET['left'])){
	$view_errors= "No such code!\n";
//	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
$ok=false;
$id=strval(intval($_GET['left']));
$sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
$result=mysql_query($sql);
$row=mysql_fetch_object($result);
$slanguage=$row->language;
$sresult=$row->result;
$stime=$row->time;
$smemory=$row->memory;
$sproblem_id=$row->problem_id;
$view_user_id=$suser_id=$row->user_id;
mysql_free_result($result);


if (isset($OJ_AUTO_SHARE)&&$OJ_AUTO_SHARE&&isset($_SESSION['user_id'])){
	$sql="SELECT 1 FROM solution where 
			result=4 and problem_id=$sproblem_id and user_id='".$_SESSION['user_id']."'";
	$rrs=mysql_query($sql);
	$ok=(mysql_num_rows($rrs)>0);
	mysql_free_result($rrs);
}
$view_source="No source code available!";
if (isset($_SESSION['user_id'])&&$row && $row->user_id==$_SESSION['user_id']) $ok=true;
if (isset($_SESSION['source_browser'])) $ok=true;

		$sql="SELECT `source` FROM `source_code` WHERE `solution_id`=".$id;
		$result=mysql_query($sql);
		$row=mysql_fetch_object($result);
		if($row)
			$view_source=$row->source;

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
</head>
<body>
<div id="wrapper">
	<?php require_once("oj-header.php");?>
<div id=main>
	
<link href='highlight/styles/shCore.css' rel='stylesheet' type='text/css'/> 
<link href='highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css'/> 
<script src='highlight/scripts/shCore.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCpp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCss.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushJava.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushDelphi.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushRuby.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushBash.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPython.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushPhp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushPerl.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCSharp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushVb.js' type='text/javascript'></script>

<script language='javascript'> 
SyntaxHighlighter.config.bloggerMode = false;
SyntaxHighlighter.config.clipboardSwf = 'highlight/scripts/clipboard.swf';
SyntaxHighlighter.all();
</script>

<!-- Requires jQuery -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
	<!-- Optional jquery.corner for rounded buttons -->
	<script src="jquery.corner.js" type="text/javascript"></script>
	
	<!-- Requires CodeMirror 2.16 -->
	<script type="text/javascript" src="mergely/codemirror.js"></script>
	<link type="text/css" rel="stylesheet" href="mergely/codemirror.css" />
	
	<!-- Requires Mergely -->
	<script type="text/javascript" src="mergely/mergely.js"></script>
	<link type="text/css" rel="stylesheet" href="mergely/mergely.css" />
	
	<script type="text/javascript">

        $(document).ready(function () {
			$('#compare').mergely({
				cmsettings: { readOnly: false, lineWrapping: true }
			});
			$.ajax({
				type: 'GET', async: true, dataType: 'text',
				url: 'getsource.php?id=<?php echo intval($_GET['left'])?>',
				success: function (response) {
					$('#compare').mergely('lhs', response);
				}
			});
			$.ajax({
				type: 'GET', async: true, dataType: 'text',
				url: 'getsource.php?id=<?php echo intval($_GET['right'])?>',
				success: function (response) {
					$('#compare').mergely('rhs', response);
				}
			});
		});
	</script>
<?php

   if ($ok==true){
		if($view_user_id!=$_SESSION['user_id'])
			echo "<a href='mail.php?to_user=$view_user_id&title=$MSG_SUBMIT $id'>Mail the author</a>";
		$brush=strtolower($language_name[$slanguage]);
		if ($brush=='pascal') $brush='delphi';
		if ($brush=='obj-c') $brush='c';
		if ($brush=='freebasic') $brush='vb';
		echo "<pre class=\"brush:".$brush.";\">";
		ob_start();
		echo "/**************************************************************\n";
		echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n";
		echo "\tLanguage: ".$language_name[$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
		if ($sresult==4){
			echo "\tTime:".$stime." ms\n";
			echo "\tMemory:".$smemory." kb\n";
		}
		echo "****************************************************************/\n\n";
		$auth=ob_get_contents();
		ob_end_clean();

		echo htmlspecialchars(str_replace("\n\r","\n",$view_source))."\n".$auth."</pre>";
		
	}else{
		
		echo "I am sorry, You could not view this code!";
	}
?>
<div id="mergely-resizer">
		<div id="compare">
		</div>
</div>


<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>




