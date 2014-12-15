<?php

/**

 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_1hand_equipment extends discuz_table
{
	public function __construct() {

		$this->_table = '1hand_equipment';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function get_equipment(){
		
		$test =  mysql_query("SELECT * FROM `newultrax`.`pre_1hand_equipment`");
		$i = 0;
		while($row = mysql_fetch_array($test)){
			$result[$i] = $row;
			$i++;
		}
		mysql_close($con);
		
		return $result;
	}
	public function insert($name, $class_id){
		$dateline = date("U");
		
		return mysql_query("INSERT INTO `newultrax`.`pre_1hand_equipment` (name, class_id, dateline, state)  VALUES ('$name', '$class_id', '$dateline', '0')");
		
	}
	public function delete($id){
		$dateline = date("U");
		
		return mysql_query("UPDATE `newultrax`.`pre_1hand_equipment` SET state='1',dateline='$dateline' where id='$id'");
	}
	
	public function update($id, $name){
		$dateline = date("U");
		
		return mysql_query("UPDATE `newultrax`.`pre_1hand_equipment` SET name='$name',dateline='$dateline' where id='$id'");
	}
	

}

?>