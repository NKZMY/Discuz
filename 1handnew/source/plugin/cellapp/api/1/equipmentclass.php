<?php

if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}
$dateline = $_GET['dateline'];
$sql = "SELECT * FROM `newultrax`.`pre_1hand_equipment_class` where dateline > '$dateline'";
$query = DB::QUERY($sql);
$data = array();
while($row = DB::fetch($query)) {
				$data[] = cellapp_core::getvalues($row, array('id', 'ename', 'cname', 'level', 'upid', 'pic', 'dateline', 'state'));
			}
$variable = array('data' => $data);
cellapp_core::result(cellapp_core::variable($variable));
?>