<?php

if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}

/*$sql = 'SELECT * FROM `pre_1hand_advertisement`';
//echo $sql;
$query = DB::QUERY($sql);
$data = array();
while($row = DB::fetch($query)) {
				$data[] = cellapp_core::getvalues($row, array('advid', 'avalible', 'typeid', 'length', 'width', 'visiturl', 'clickurl', 'category', 'row'));
			}
$variable = array('data' => $data);
cellapp_core::result(cellapp_core::variable($variable));*/
echo $_GET['number'];
echo $_GET['password'];

?>