<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_myrepeats.php 31512 2012-09-04 07:11:08Z monkey $
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_1hand_user_article extends discuz_table
{
	public function __construct() {

		$this->_table = '1hand_user_article';
		$this->_pk    = '';

		parent::__construct();
	}
    //根据文章id，查询用户对文章的操作记录是否存在
	public function fetch_all_by_uid_action($uid,$aid,$action) {
		return DB::fetch_all("SELECT * FROM %t WHERE uid=%d and aid=%d and action = %d ", array($this->_table, $uid, $aid,$action));
	}

	
	public function insert_blank($uid) {
		return DB::query("insert into  %t (uid,onlinedate,bouns_flag) values (%d,current_date(),0)", array($this->_table, $uid));
	}

	public function insert($data) {
		DB::insert($this->_table,$data);
	}


}

?>