<?php
//insert $table($key) values($val);
function Insert($table,$arr){
	$keys=join(",",array_keys($arr));
	$val = "'".join("','",array_values($arr))."'";
	$sql = "insert {$table}($keys) values({$val})";
	mysql_query($sql) or die(mysql_error());
	return mysql_insert_id();
}

//update $table set $key='$val' where $where;
function Update($table,$arr,$where){
	$str=null;
	foreach ($arr as $key => $value) {
		if($str==null)
			$sep="";
		else
			$sep=",";
		$str.=$sep.$key."='".$value."'";
	}
	$sql = "update {$table} set {$str} where {$where}";
	mysql_query($sql) or die(mysql_error());
}

//delete from $table where $where;
function Delete($table,$where){
	$sql = "delete from {$table} where {$where}";
	mysql_query($sql) or die(mysql_error());
}

//select one
function fetchOne($sql){
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	return $row;
}

//SortStuScore
function SortStuScore($table){
	$sqladd = "";
	$where = array();
	$whereflag = false;
	$order = array();
	$orderflag = false;
	if(isset($_GET['xsid']))
	{
		$xsid = $_GET['xsid'];
		$xsid = mysql_real_escape_string($xsid);
		$where[] = "{$table}.user_id like '%{$xsid}%'";
	}
	if(isset($_GET['xsname']))
	{
		$xsname = $_GET['xsname'];
		$xsname = mysql_real_escape_string($xsname);
		$where[] = "{$table}.nick like '%{$xsname}%'";
	}
	if(isset($_GET['sortanum']))
	{
		$sortanum = intval($_GET['sortanum']);
		if($sortanum&1) $order[]="choosesum ASC";
		if($sortanum&2) $order[]="judgesum ASC";
		if($sortanum&4) $order[]="fillsum ASC";
		if($sortanum&8) $order[]="programsum ASC";
		if($sortanum&16) $order[]="score ASC";
	}
	if(isset($_GET['sortdnum']))
	{
		$sortdnum = intval($_GET['sortdnum']);
		if($sortdnum&1) $order[]="choosesum DESC";
		if($sortdnum&2) $order[]="judgesum DESC";
		if($sortdnum&4) $order[]="fillsum DESC";
		if($sortdnum&8) $order[]="programsum DESC";
		if($sortdnum&16) $order[]="score DESC";
	}
	if(!empty($where[0]))
	{
		$where = join(' AND ',$where);
		$where = " WHERE ".$where;
	}
	else
		$where = join('',$where);
	if(!empty($order[0]))
	{
		$order = join(',',$order);
		$order = "ORDER BY ".$order;
	}
	else
		$order = join('',$order);
	$sqladd = $where." ".$order;
	return $sqladd;
}
?>