<?php

if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}
$uid = $_GET['uid'];
$eid = $_GET['eid'];
$tid = $_GET['tid'];
$ename = $_GET['ename'];
$sql = "INSERT INTO `newultrax`.`pre_1hand_equipment_authorize` ('uid', 'eid', 'tid', 'ename', 'dateline', 'state')  VALUES ('$uid', '$eid', '$tid', '$ename', '0', '0')";
$query = DB::QUERY($sql);

?>