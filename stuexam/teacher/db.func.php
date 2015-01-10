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
?>