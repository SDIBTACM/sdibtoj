<?require_once("admin-header.php");?>
<?
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
if(isset($_POST['do'])){
       require_once("../include/check_post_key.php");
	$start=($_POST['start']);
        $end =$_POST['end'];
        $author=mysql_real_escape_string($_POST['author']);
	$sql="select `rightstr` from privilege where user_id='$author' and rightstr='problem_editor'";
        $result=mysql_query($sql);
        if(mysql_affected_rows()==1)
        { 
        $sql="update `problem` set author='$author' where  problem_id>=$start and problem_id<=$end ";
	//echo $sql;
         mysql_query($sql);
        }
       else
           {echo "This user don't have the privilege";
               exit(1);
           }
}
?>
<form method=post>
	<b>Assign problem to User:</b><br />
	from PID:<input type=text size=10 name="start" value=1000>
	to PID:<input type=text size=10 name="end" value=1000><br />
        to user:
          <input type=text size=40 name="author" value=""><br />
	<input type='hidden' name='do' value='do'>
	<input type=submit value='Add'>
        <input type=hidden name="postkey" value="<?=$_SESSION['postkey']?>">
</form>
