<?php

if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}
$classid = $_GET['classid'];
$sql = "SELECT * FROM `newultrax`.`pre_1hand_equipment` where class_id = '$classid'";
$query = DB::QUERY($sql);
$data = array();
while($row = DB::fetch($query)) {
				$data[] = cellapp_core::getvalues($row, array('id', 'name', 'class_id'));
			}
$variable = array('data' => $data);
cellapp_core::result(cellapp_core::variable($variable));
?>