<?php

/**

 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_1hand_equipment_class extends discuz_table
{
	public function __construct() {

		$this->_table = '1hand_equipment_class';
		$this->_pk    = 'id';

		parent::__construct();
	}
	public function get_all(){
		$test =  mysql_query("SELECT * FROM `newultrax`.`pre_1hand_equipment_class` ");
		$i = 0;
		while($row = mysql_fetch_array($test)){
			$result[$i] = $row;
			$i++;
		}
		mysql_close($con);
		
		return $result;
	}
	public function get_category(){
		
		$test =  mysql_query("SELECT * FROM `newultrax`.`pre_1hand_equipment_class` where level='1'");
		$i = 0;
		while($row = mysql_fetch_array($test)){
			$result[$i] = $row;
			$i++;
		}
		mysql_close($con);
		
		return $result;
	}
	
	public function get_brand(){
		
		$test =  mysql_query("SELECT * FROM `newultrax`.`pre_1hand_equipment_class` where level='2' ");
		$i = 0;
		while($row = mysql_fetch_array($test)){
			$result[$i] = $row;
			$i++;
		}
		mysql_close($con);
		
		return $result;
	}
	
	public function insert($ename, $cname, $level, $upid, $pic){
		$dateline = date("U");
		
		return mysql_query("INSERT INTO `newultrax`.`pre_1hand_equipment_class` (ename, cname, level, upid, pic, dateline, state)  VALUES ('$ename', '$cname', '$level', '$upid', '$pic', '$dateline', '0')");
		
	}
	
	public function delete($id){
		$dateline = date("U");
		
		return mysql_query("UPDATE `newultrax`.`pre_1hand_equipment_class` SET state='1',dateline='$dateline' where id='$id'");
		
	}
	
	public function update($id, $ename, $cname, $pic){
		$dateline = date("U");
		
		return mysql_query("UPDATE `newultrax`.`pre_1hand_equipment_class` SET ename='$ename',cname='$cname',pic='$pic',dateline='$dateline' where id='$id'");
	}
	

}

?>