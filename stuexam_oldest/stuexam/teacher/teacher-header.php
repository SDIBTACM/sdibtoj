<?php
	@session_start();
	require_once("myinc.inc.php");
	checkuserid();
	checkAdmin(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
  	<link href="../css/examsys.css" rel="stylesheet" type="text/css">
	<title>程序设计考试系统</title>
</head>
<script src="../css/jquery.js" type="text/javascript"></script>
<script src="../css/bootstrap.min.js" type="text/javascript"></script>
<body class="page-header-fixed">
	<div class="header navbar navbar-inverse" style="margin-bottom:0px">
		<div class="navbar-inner">
			<ul class="nav pull-left">
				<li>
					<a href="javascript:;">程序设计考试系统后台管理</a>
				</li>
			</ul>
			<ul class="nav pull-right">
				<li >
					<a href="javascript:;">Welcome <?php echo $_SESSION['user_id'];?></a>
				</li>
			</ul>
		</div>
	</div>