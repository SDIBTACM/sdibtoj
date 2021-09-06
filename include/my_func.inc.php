<?
function pwGen($password,$md5ed=False) 
{
        if (!$md5ed) $password=md5($password);
        $salt = sha1(rand());
        $salt = substr($salt, 0, 4);
        $hash = base64_encode( sha1($password . $salt, true) . $salt ); 
        return $hash; 
}

function pwCheck($password,$saved)
{
        if (isOldPW($saved)){
                $mpw = md5($password);
                if ($mpw==$saved) return True;
                else return False;
        }
        $svd=base64_decode($saved);
        $salt=substr($svd,20);
        $hash = base64_encode( sha1(md5($password) . $salt, true) . $salt );
        if (strcmp($hash,$saved)==0) return True;
        else return False;
}

function isOldPW($password)
{
        for ($i=strlen($password)-1;$i>=0;$i--)
        {
                $c = $password[$i];
                if ('0'<=$c && $c<='9') continue;
                if ('a'<=$c && $c<='f') continue;
                if ('A'<=$c && $c<='F') continue;
                return False;
        }
        return True;
}



function is_valid_user_name($user_name){
	$len=strlen($user_name);
	for ($i=0;$i<$len;$i++){
		if (
			($user_name[$i]>='a' && $user_name[$i]<='z') ||
			($user_name[$i]>='A' && $user_name[$i]<='Z') ||
			($user_name[$i]>='0' && $user_name[$i]<='9') ||
			$user_name[$i]=='_'||
                        ($i==0 && $user_name[$i]=='*')
		);
		else return false;
	}
	return true;
}

function sec2str($sec){
	return sprintf("%02d:%02d:%02d",$sec/3600,$sec%3600/60,$sec%60);
}

function is_running($cid){
	require_once("./include/db_info.inc.php");
	$sql="SELECT count(*) FROM `contest` WHERE `contest_id`='$cid' AND `end_time`>NOW()";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$cnt=intval($row[0]);
	mysql_free_result($result);
	return $cnt>0;
}

function check_ac($cid,$pid){
	require_once("./include/db_info.inc.php");
	$sql="SELECT count(*) FROM `solution`,`contest` WHERE contest.contest_id='$cid'  AND solution.contest_id='$cid' AND `num`='$pid' AND contest.start_time < solution.in_date AND contest.end_time > solution.in_date  AND `result`='4' AND `user_id`='".$_SESSION['user_id']."'";
        $result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$ac=intval($row[0]);
	mysql_free_result($result);
	if ($ac>0) return "<font color=green>Y</font>";
	$sql="SELECT count(*) FROM `solution`,`contest` WHERE contest.contest_id='$cid'  AND solution.contest_id='$cid' AND `num`='$pid' AND contest.start_time<solution.in_date AND contest.end_time>solution.in_date AND `user_id`='".$_SESSION['user_id']."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$sub=intval($row[0]);
	mysql_free_result($result);
	if ($sub>0) return "<font color=red>N</font>";
	else return "";
}

function isstuno($str)
{
	$moder="/^[a-zA-Z]*\d+$/";
	if(preg_match($moder,$str))
		return true;
	else
		return false;
}

function isphonenum($str)
{
	$moder="/^1[34578]{1}\d{9}$/";
	if(preg_match($moder,$str))
		return true;
	else
		return false;
}

function isemail($str)
{
	$moder="/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
	if(preg_match($moder,$str))
		return true;
	else
		return false;
}
function noRefresh()
{
       $seconds = '5'; //时间段[秒]
       $refresh = '4'; //刷新次数
      //设置监控变量
       $cur_time = time();
       if(isset($_SESSION['last_time'])){
            $_SESSION['refresh_times'] += 1;
       }else{
            $_SESSION['refresh_times'] = 1;
            $_SESSION['last_time'] = $cur_time;
       }
//处理监控结果
     if($cur_time - $_SESSION['last_time'] < $seconds){
         if($_SESSION['refresh_times'] >= $refresh){
  //跳转至攻击者服务器地址
                 return true;
         }
     }else{
     $_SESSION['refresh_times'] = 0;
     $_SESSION['last_time'] = $cur_time;
    }
}

function isIpInSubnets($ip, $subnets)
{
    if (is_null($subnets)) return true;
    if (!is_array($subnets)) return isIpInSubnet($ip, $subnets);

    foreach ($subnets as $subnet) {
        if (isIpInSubnet($ip, $subnet) || $subnet == "")
            return true;
    }
    return false;
}

function isIpInSubnet($ip, $subnet)
{

    if (! (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
        filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) ) {
        return false;
    }

    if (substr_count($subnet, '/') < 1) {
        return $ip == $subnet;
    } else if (substr_count($subnet, '/') > 1) {
        return false;
    }

    $subnetArray = explode('/', $subnet);

    if (! (filter_var($subnetArray[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
        filter_var($subnetArray[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) ) {
        return false;
    }

    return isIpMatchSubnetWithMask($ip, $subnetArray[0], $subnetArray[1]);
}

function isIpMatchSubnetWithMask($ip, $subnet, $mask)
{
    if ($mask < 0 || $mask == null) {
        return false;
    }

    if ($mask == 0) {
        return true;
    }

	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && 
	    filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $mask <= 32) {
        return substr_compare(sprintf('%032b', ip2long($ip)), sprintf('%032b', ip2long($subnet)), 0, $mask) == 0;
    }

    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && 
	    filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && $mask <= 128) {
		$bytesAddr = unpack('n*', @inet_pton($ip));
		$bytesTest = unpack('n*', @inet_pton($subnet));
	
		if (!$bytesAddr || !$bytesTest) {
		    return false;
		}
	
		for ($i = 1, $ceil = ceil($netmask / 16); $i <= $ceil; ++$i) {
		    $left = $netmask - 16 * ($i - 1);
		    $left = ($left <= 16) ? $left : 16;
		    $mask = ~(0xffff >> $left) & 0xffff;
		    if (($bytesAddr[$i] & $mask) != ($bytesTest[$i] & $mask)) {
			return false;
		    }
        }
		return true;
        }

        return false;
    }
function isLength($str){
    $len=strlen($str);
    if($len>=8) return true;
    else return false;
}

function isMulti($str){
    $myType=0;
    //有数字
    if(preg_match('/\d/',$str)) $myType++;
    //有大写字母
    if(preg_match('/[A-Z]/',$str)) $myType++;
    //有小写写字母
    if(preg_match('/[a-z]/',$str)) $myType++;
    //有其它字符
    $str2=preg_replace('/[A-Za-z0-9]/','',$str);
    $len2=strlen($str2);
    if($len2>=1) $myType++;

    if($myType>=3) return true;
    else return false;
}

?>

