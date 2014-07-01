<?require_once('oj-header.php');?>
<title>Update Information</title>
<?
	if(!isset($_SESSION['user_id'])){
		echo "<a href=./loginpage.php>Please Login First!</a>";
		require_once('oj-footer.php');
		exit();
	}
	require_once('./include/db_info.inc.php');
	if(!isset($_GET['cid'])){
	echo "No such contest!\n";
	require_once("oj-footer.php");
	exit(0);
	}
$cid=intval($_GET['cid']);
$sql="SELECT * FROM `contestreg` WHERE `contestreg`.`user_id`='".$_SESSION['user_id']."' AND `contestreg`.`contest_id`='$cid'";
$result=mysql_query($sql);
/*$row_cnt=mysql_num_rows($result);

if($row_cnt==0){
	echo "<script language='javascript'>\n";
	echo "alert('Please register for the contest')\n";  
	echo "history.go(-1);\n";
	echo "</script>";
	exit(0);
}*/
$row=mysql_fetch_object($result);
if($row==false){
	echo "<script language='javascript'>\n";
	echo "alert('Please register for the contest')\n";  
	echo "history.go(-1);\n";
	echo "</script>";
	exit(0);
}
?>
<form action="updateregister.php?cid=<?echo $_GET['cid']?>" method="post">
	<br><br>
	<center><table>
		<tr><td colspan=2 height=40 width=100%>&nbsp;&nbsp;&nbsp;
			<h1 style="COLOR: #1A5CC8">Update Information</h1></td></tr>
		<tr><td colspan=2 height=20 width=100%>
			Please fill the following information,<span style="color:#FF0000">*</span>is necessary.
		</td></tr>

		<tr><td width=30%><?=$MSG_STU_REALNAME?>:
			<td width=70%><input name="sturealname" size=28 type=text
				value="<?=htmlspecialchars($row->sturealname)?>">
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_ID?>:
			<td><input name="stuid" size=28 type=text
				value="<?=htmlspecialchars($row->stuid)?>">
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_SEX?>:
			<td><span id="container" style="width:100%">
				<select name="stusex" size="1" id="stusex" style="width:85%">
				<?
					if($row->stusex=="M"){
						echo "<option value='M' selected>$MSG_SEX_MALE</option>";
						echo "<option value='F'>$MSG_SEX_FEMALE</option>";
					}
					else{
						echo "<option value='F' selected>$MSG_SEX_FEMALE</option>";
						echo "<option value='M'>$MSG_SEX_MALE</option>";
					}
				?>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_PHONE?>:
			<td><input name="stuphone" size=28 type=text
				value="<?=htmlspecialchars($row->stuphone)?>">
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_EMAIL?>:
			<td><input name="stuemail" size=28 type=text
				value="<?=htmlspecialchars($row->stuemail)?>">
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_SCHOOL_NAME?>:
			<td><input name="stuschoolname" size=28 type=text
				value="<?=htmlspecialchars($row->stuschoolname)?>">
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_DEPARTMENT?>:
			<td><input name="studepartment" size=28 type=text
				value="<?=htmlspecialchars($row->studepartment)?>">
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_MAJOR?>:
			<td><input name="stumajor" size=28 type=text
				value="<?=htmlspecialchars($row->stumajor)?>">
			</td></td>
		</tr>
		<tr><td>
			<td><input value="Submit" name="submit" type="submit">
				&nbsp; &nbsp;
				<input value="Reset" name="reset" type="reset">
			</td></td>
		</tr>
	</table>
	</center>
	<br><br>
</form>
<?mysql_free_result($result)?>
<?require_once('oj-footer.php');?>

