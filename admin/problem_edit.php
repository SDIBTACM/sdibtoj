<?require_once("../include/db_info.inc.php");?>
<?require_once("admin-header.php");

if (!(isset($_SESSION['administrator'])||isset($_SESSION['problem_editor']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}
if(isset($_GET['id'])){
	require_once("../include/check_get_key.php");
$sql="SELECT * FROM `problem` WHERE `problem_id`=".intval($_GET['id']);
$result=mysql_query($sql);
$row=mysql_fetch_object($result);

if(!isset($_SESSION['administrator'])&&isset($_SESSION['problem_editor'])&&$row->author!=$_SESSION['user_id']){
    echo "You don't have the privilege to edit this problem";
    exit(0);
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="../ckeditor/ckeditor.js"></script>
    <title>Edit Problem</title>
</head>
<body>
<center>
<p align="center"><font color="#333399" size="4">Welcome To Administrator's Page of Judge Online of ACM ICPC, <?=$OJ_NAME?>.</font>
<td width="100"></td>
</center>
<hr>

<h1>Edit problem</h1>
<form method=POST action=problem_edit.php>
<input type=hidden name=problem_id value=New Problem>

<p>Problem Id: <?=$row->problem_id?></p>
<input type=hidden name=problem_id value='<?=$row->problem_id?>'>
<p>Title:
    <input type=text name=title size=71 value='<?=htmlspecialchars($row->title)?>'>
</p>

<p>Time Limit:
    <input type=text name=time_limit size=20 value='<?=$row->time_limit?>'>S
</p>

<p>Memory Limit:
    <input type=text name=memory_limit size=20 value='<?=$row->memory_limit?>'>MByte
</p>

<p align=left>Description:<br>
    <textarea name="description"><?php echo htmlspecialchars($row->description)?></textarea>
</p>

<p align=left>Input:<br>

    <textarea name="input"><?php echo htmlspecialchars($row->input)?></textarea>
</p>

</p>
<p align=left>Output:<br>
    <textarea name="output"><?php echo htmlspecialchars($row->output)?></textarea>
</p>

<p>Sample Input:<br>
    <textarea rows=13 name=sample_input cols=120><?=htmlspecialchars($row->sample_input)?></textarea>
</p>

<p>Sample Output:<br>
    <textarea rows=13 name=sample_output cols=120><?=htmlspecialchars($row->sample_output)?></textarea>
</p>

<p>Hint:<!--<textarea rows=13 name=input cols=80></textarea>--><textarea name="hint"><?php echo htmlspecialchars($row->hint)?></textarea>
</p>

<p>SpecialJudge: 
N<input type=radio name=spj value='0' <?=$row->spj=="0"?"checked":""?>>
Y<input type=radio name=spj value='1' <?=$row->spj=="1"?"checked":""?>>
</p>

<p>Source:<br>
    <textarea name=source rows=1 cols=70><?=htmlspecialchars($row->source)?></textarea>
</p>

<div align=center>
<?require_once("../include/set_post_key.php");?>
<input type=submit value=Submit name=submit>
</div></form>

<script>
    CKEDITOR.replace('input');
    CKEDITOR.replace('output');
    CKEDITOR.replace('description');
    CKEDITOR.replace('hint');
</script>

<p>

<?require_once("../oj-footer.php");?>
<?}else{
require_once("../include/check_post_key.php");
$id=$_POST['problem_id'];
$title=$_POST['title'];
$time_limit=$_POST['time_limit'];
$memory_limit=$_POST['memory_limit'];
$description=$_POST['description'];
$input=$_POST['input'];
$output=$_POST['output'];
$sample_input=$_POST['sample_input'];
$sample_output=$_POST['sample_output'];
$hint=$_POST['hint'];
$source=$_POST['source'];
$spj=$_POST['spj'];
if (get_magic_quotes_gpc ()) {
	$title = stripslashes ( $title);
	$time_limit = stripslashes ( $time_limit);
	$memory_limit = stripslashes ( $memory_limit);
	$description = stripslashes ( $description);
	$input = stripslashes ( $input);
	$output = stripslashes ( $output);
	$sample_input = stripslashes ( $sample_input);
	$sample_output = stripslashes ( $sample_output);
//	$test_input = stripslashes ( $test_input);
//	$test_output = stripslashes ( $test_output);
	$hint = stripslashes ( $hint);
	$source = stripslashes ( $source); 
	$spj = stripslashes ( $spj);
	$source = stripslashes ( $source );
}
$basedir=$OJ_DATA."/$id";
echo "Sample data file in $basedir Updated!<br>";

	if($sample_input){
		//mkdir($basedir);
		$fp=fopen($basedir."/sample.in","w");
		fputs($fp,str_replace("\r\n","\n",$sample_input));
		fclose($fp);
		
		$fp=fopen($basedir."/sample.out","w");
		fputs($fp,str_replace("\r\n","\n",$sample_output));
		fclose($fp);
	}
	$title=mysql_real_escape_string($title);
	$time_limit=mysql_real_escape_string($time_limit);
	$memory_limit=mysql_real_escape_string($memory_limit);
	$description=mysql_real_escape_string($description);
	$input=mysql_real_escape_string($input);
	$output=mysql_real_escape_string($output);
	$sample_input=mysql_real_escape_string($sample_input);
	$sample_output=mysql_real_escape_string($sample_output);
//	$test_input=($test_input);
//	$test_output=($test_output);
	$hint=mysql_real_escape_string($hint);
	$source=mysql_real_escape_string($source);
//	$spj=($spj);
	
$sql="UPDATE `problem` set `title`='$title',`time_limit`='$time_limit',`memory_limit`='$memory_limit',
	`description`='$description',`input`='$input',`output`='$output',`sample_input`='$sample_input',`sample_output`='$sample_output',`hint`='$hint',`source`='$source',`spj`=$spj,`in_date`=NOW()
	WHERE `problem_id`=$id";

@mysql_query($sql) or die(mysql_error());
echo "Edit OK!";


		
echo "<a href='../problem.php?id=$id'>See The Problem!</a>";
}
?>
</body>
</html>

