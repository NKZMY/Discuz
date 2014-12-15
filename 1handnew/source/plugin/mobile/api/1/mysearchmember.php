<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: mysearch 29296 2012-04-01 01:56:37Z congyushuai $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

$_GET['mod'] = 'mysearchmember';
include_once 'portal.php';

class mobile_api {

	function common() {
		global $_G;
		if(!empty($_GET['pw'])) {
			$_GET['action'] = 'pwverify';
		}
		$_G['portal']['allowglobalstick'] = false;
	}

	function output() {
		global $_G;
		$keyword = $_GET['keyword'];
		$test =  mysql_query("SELECT  * FROM `pre_common_member` where username like '%$keyword%'");
		$i = 0;
		while($row = mysql_fetch_array($test)){
			$result[$i] = $row;
			$i++;
		}
		
		
		$variable = array(
			'mysearchmember' => mobile_core::getvalues($result,array('/^\d+$/'),array('uid', 'email', 'username', 'password', 'status', 'emailstatus', 'avatarstatus', 'videophotostatus', 'adminid', 'groupid', 'groupexpiry', 'extgroupids', 'regdate', 'credits', 'notifysound', 'timeoffset', 'newpm', 'newprompt', 'accessmasks', 'allowadmincp', 'onlyacceptfriendpm', 'conisbind')),
		);
		mobile_core::result(mobile_core::variable($variable));
	  
		
	}

}

?>