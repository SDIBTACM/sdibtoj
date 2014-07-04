<?require_once("oj-header.php");?>
<title>Sign Up</title>
<?
	if(!isset($_SESSION['user_id'])){
	echo "<a href=./loginpage.php>Please Login First!</a>";
	require_once('oj-footer');
	exit();
	}
	if(!isset($_GET['cid'])){
	echo "No such contest!\n";
	require_once("oj-footer.php");
	exit(0);
	}
?>

<form action = "cstregister.php?cid=<?echo $_GET['cid']?>" method = "post">
	<br><br>
	<center><table>
		<tr><td colspan=2 height=40 width=100%>&nbsp;&nbsp;&nbsp;
			<h1 style="COLOR: #1A5CC8"><?=$MSG_SIGN_UP_INFO?></h1></td></tr>
		<tr><td colspan=2 height=20 width=100%>
			Please fill the following information,<span style="color:#FF0000">*</span>is necessary.
		</td></tr>

		<tr><td width=30%><?=$MSG_STU_REALNAME?>:
			<td width=70%><input name="sturealname" size=28 type=text>
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_ID?>:
			<td><input name="stuid" size=28 type=text>
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_SEX?>:
			<td><span id="container" style="width:100%">
				<select name="stusex" size="1" id="stusex" style="width:85%">
				<option selected value="M"><?=$MSG_SEX_MALE?></option>
				<option value="F"><?=$MSG_SEX_FEMALE?></option>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_PHONE?>:
			<td><input name="stuphone" size=28 type=text>
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_EMAIL?>:
			<td><input name="stuemail" size=28 type=text>
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_SCHOOL_NAME?>:
			<td><input name="stuschoolname" size=28 type=text>
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_DEPARTMENT?>:
			<td><input name="studepartment" size=28 type=text>
			<span style="color:#FF0000">*</span>
			</td></td>
		</tr>
		<tr><td><?=$MSG_STU_MAJOR?>:
			<td><input name="stumajor" size=28 type=text>
			<span style="color:#FF0000">*</span>
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
<?require_once("oj-footer.php");?>