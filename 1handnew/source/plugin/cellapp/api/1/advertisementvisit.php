<?php

if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}
$advid = $_GET['advid'];
$date = date("Y.m.d");
$time = date("U");
$uid = $_GET['uid'];
$userip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT * FROM `pre_1hand_advertisement_visit` where advid='$advid' and dateline='$date'";

$query = DB::QUERY($sql);
$result = DB::fetch($query);

if($result[id] == null){
	$sqltwo = "insert into pre_1hand_advertisement_visit(advid, dateline, number) values('$advid','$date','1')";
	$querytwo = DB::QUERY($sqltwo);
}
else {
	$number = $result[number]+1;
	$sqlthree = "update pre_1hand_advertisement_visit set number='$number' where id='$result[id]'";
	$querythree = DB::QUERY($sqlthree);
	
}
if($uid == '0'){
	echo "hello";
	$sqlfour = "insert into pre_1hand_advertisement_log(uid, dateline, advid, type) values('$userip', '$time', '$advid', '1')";
	$queryfour = DB::QUERY($sqlfour);
	
}
else {
	$sqlfive = "insert into pre_1hand_advertisement_log(uid, dateline, advid, type) values('$uid', '$time', '$advid', '1')";
	$queryfive = DB::QUERY($sqlfive);
}
$sql = " select visiturl from pre_1hand_advertisement where advid = '$advid' ";
$visiturl = DB::QUERY($sql);
header($visiturl);
//header('Location: http://beta.1hand.cn/advertisement/test.html');
//header('Location: http://www.baidu.com');
?>