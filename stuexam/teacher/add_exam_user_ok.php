<?php
	require_once("myinc.inc.php");
	require_once("../../include/check_post_key.php");
	checkAdmin(2);
	if(isset($_POST['eid']))
		$eid=intval($_POST['eid']);
	if($eid>0)
	{
		Delete("ex_privilege","rightstr='e{$eid}'");
		$pieces = explode("\n", trim($_POST['ulist']));
		if (count($pieces)>0 && strlen($pieces[0])>0)
		{
			for ($i=0;$i<count($pieces);$i++){
				$pieces[$i]=trim($pieces[$i]);
			}
		}
		$pieces = array_unique($pieces);
		if (count($pieces)>0 && strlen($pieces[0])>0)
		{
			$randnum=rand(1,39916800);
			$query="INSERT INTO `ex_privilege`(`user_id`,`rightstr`,`randnum`) VALUES('".trim($pieces[0])."','e$eid','$randnum')";
			for ($i=1;$i<count($pieces);$i++){
				$randnum=rand(1,39916800);
				if(isset($pieces[$i]))
					$query=$query.",('".trim($pieces[$i])."','e$eid','$randnum')";
			}
			mysql_query($query) or die(mysql_error());
		}
		echo "<script> window.location.href=\"add_exam_user.php?eid=$eid\";</script>";
	}
	else{
		alertmsg("Invaild Path");
	}
?>