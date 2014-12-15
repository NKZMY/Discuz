<?php

/**

 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_1hand_equipment_authorize extends discuz_table
{
	public function __construct() {

		$this->_table = '1hand_equipment_authorize';
		$this->_pk    = 'id';

		parent::__construct();
	}
	public function get_equipment_authorize(){
		$test =  mysql_query("SELECT * FROM `pre_1hand_equipment_authorize`  ");
		$i = 0;
		while($row = mysql_fetch_array($test)){
			$result[$i] = $row;
			$i++;
		}
		mysql_close($con);
		
		return $result;
	}
public function update($id, $state){
		$dateline = date("U");
		
		return mysql_query("UPDATE `pre_1hand_equipment_authorize` SET state='$state',dateline='$dateline' where id='$id'");
	}
	
	

}

?>