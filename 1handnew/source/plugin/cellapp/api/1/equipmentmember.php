<?php

if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}
$uid = $_GET['uid'];
$sql = "SELECT * FROM `newultrax`.`pre_1hand_equipment_authorize` where uid = '$uid' and state = 1";
$query = DB::QUERY($sql);
$data = array();
while($row = DB::fetch($query)) {
				$data[] = cellapp_core::getvalues($row, array('id', 'uid', 'eid', 'tid', 'ename', 'dateline', 'state'));
			}
$variable = array('data' => $data);
cellapp_core::result(cellapp_core::variable($variable));
?>