<?php
	@session_start();
	if (!(isset($_SESSION['administrator'])
		||isset($_SESSION['contest_creator'])
		||isset($_SESSION['problem_editor']))){
	echo "You have no privilege";
	echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
	exit(1);
}
?>
<div class="row-fluid learn-more">
	<div class="container">
		<div class="span4">
			<h3>SDIBTACM</h3>
			<ul class="nav">
				<li><a href="../">EXAM LIST</a></li>
				<li><a href="../../">ONLINE JUDGE</a></li>
				<li><a href="http://acm.sdibt.edu.cn:8080/judge/">VIRTUAL JUDGE</a></li>
			</ul>
		</div>
		<div class="span4">
			<h3>帮助中心</h3>
			<ul class="nav">
				<li><a href="../../faqs.php">FAQS</a></li>
				<li><a href="../../discuss.php">DISCUSS</a></li>
			</ul>
		</div>
		<div class="span4">
			<h3>关于我们</h3>
			<li><span style="color:#CCC">QQ交流群:191227938</span></li>
			<li><span style="color:#CCC"><address>现位于二教北区411</address></span></li>
			<li><a href="mailto:sdibtacm@126.com">联系与反馈:sdibtacm@126.com</a></li>
		</div>
		<div class="span4"></div>
	</div>
</div>
<div id="copyright">
	<div class="container">
		&copy 2014 SDIBTACM All Rights Reserved
	</div>
</div>
</body>
</html>