<?php
	function alertmsg($msg,$url=null,$isback=1){
		echo "<script language='javascript'>\n";
		if($msg!="") 
			echo "alert('{$msg}');\n";
		if($isback==0)
			echo "location='{$url}';\n";
		else
			echo "history.go(-1);\n";
		echo "</script>";
	}
	function test_input($data){
		$data = trim($data);
  		//$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		$data = mysql_real_escape_string($data);
  		return $data;
	}
	function checkAdmin($val,$creator=null,$priflag=null){
		if($val==5){
			if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']||$priflag==1))
				return true;
			return false;
		}
		else if($val==4){
			if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
				return true;
			else
				return false;
		}
		else if($val==3){
			if(!(isset($_SESSION['administrator'])
				||isset($_SESSION['contest_creator'])
				||isset($_SESSION['problem_editor']))){
				echo "You have no privilege";
				echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
				exit(1);
			}
		}
		else if($val==2){
			if(!(isset($_SESSION['administrator'])
				||isset($_SESSION['contest_creator']))){
				echo "You have no privilege";
				echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
				exit(1);
			}
		}
		else if($val==1){
			if(isset($_SESSION['administrator']))
				return true;
			else
				return false;
		}
	}
	function checkuserid(){
		header("Content-type: text/html; charset=utf-8");
		if(!isset($_SESSION['user_id'])){
			echo "听说有个人他没有登陆,然后他挂科了^_^<br/>";
			echo "<a href='/JudgeOnline/loginpage.php'>Please Login First!</a>";
			exit(0);
		}
	}
	//show problem

	function problemshow($problem,$searchsql){
		if($problem<0||$problem>2)
			$problem=0;
		if(!checkAdmin(1)&&$problem==2)
			$problem=0;
		if($searchsql=="")
		{
			if($problem==0||checkAdmin(1))
				$prosql=" WHERE `isprivate`='$problem'";
			else
			{
				$user=$_SESSION['user_id'];
				$prosql=" WHERE `isprivate`='$problem' AND `creator` like '$user'";
			}
		}
		else
		{
			if($problem==0||checkAdmin(1))
				$prosql=" AND `isprivate`='$problem'";
			else
			{
				$user=$_SESSION['user_id'];
				$prosql=" AND `isprivate`='$problem' AND `creator` like '$user'";
			}
		}
		return $prosql;
	}

	//
	function splitpage($table,$searchsql="",$prosql=""){
		if(isset($_GET['page']))
		{
			if(!is_numeric($_GET['page']))
				$page=1;
			$page=intval($_GET['page']);
		}
		else
			$page=1;
		$each_page=20;// each page data num
		$pagenum=10;//the max of page num

		$sql="SELECT COUNT(*) FROM $table $searchsql $prosql";
		$result=mysql_query($sql) or die(mysql_error());
		$total=mysql_result($result, 0);
		mysql_free_result($result);

		$totalpage=ceil($total/$each_page);
		if($totalpage==0)       $totalpage=1;
		$page=$page<1?1:$page;
		$page=$page>$totalpage?$totalpage:$page;

		$offset=($page-1)*$each_page;
		$sqladd=" limit $offset,$each_page";

		$lastpage=$totalpage;
		$prepage=$page-1;
		$nextpage=$page+1;

		$startpage=$page-4;
		$startpage=$startpage<1?1:$startpage;
		$endpage=$startpage+$pagenum-1;
		$endpage=$endpage>$totalpage?$totalpage:$endpage;
		return array('page' => $page,
					'prepage' => $prepage,
					'startpage' => $startpage,
					'endpage' => $endpage,
					'nextpage' => $nextpage,
					'lastpage' => $lastpage,
					'eachpage' => $each_page,
					'sqladd' => $sqladd);
	}
	//function showpagelast($url,$page,$startpage,$endpage,$lastpage,$prepage,$nextpage){
	function showpagelast($url,$pageinfo,$urllast=""){	
		foreach ($pageinfo as $key => $value) {
			${$key}=$value;
		}

		echo "<div class=\"pagination\" style=\"text-align:center\">";
		echo "<ul>";
		echo "<li><a href=\"{$url}&page=1{$urllast}\">First</a></li>";
		if($page==1)
			echo "<li class=\"disabled\"><a href=\"\">Previous</a></li>";
		else
			echo "<li><a href=\"{$url}&page=$prepage{$urllast}\">Previous</a></li>";
		for($i=$startpage;$i<=$endpage;$i++)
		{
			if($i==$page)
				echo "<li class=\"active\"><a href=\"{$url}&page=$i{$urllast}\">$i</a></li>";
			else
		  		echo "<li><a href=\"{$url}&page=$i{$urllast}\">$i</a></li>";
		}
		if($page==$lastpage)
			echo "<li class=\"disabled\"><a href=\"\">Next</a></li>";
		else
			echo "<li><a href=\"{$url}&page=$nextpage{$urllast}\">Next</a></li>";
		echo "<li><a href=\"{$url}&page=$lastpage{$urllast}\">Last</a></li>";
		echo "</ul>";
		echo "</div>";
	}
?>
