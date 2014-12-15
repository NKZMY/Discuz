<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: portalarticlecontent 29296 2012-04-01 01:56:37Z congyushuai $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

$_GET['mod'] = 'portalarticlecontent';
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
	  
		$aid = $_GET['aid'];
		$test = new table_portal_article_content();
		$number = $test->get_article_by_aid($aid);
		
		$_G['portal_article_content'] = $number;
      
		$variable = array(
			'portal_article_content' => mobile_core::getvalues($_G['portal_article_content'],  array('cid', 'aid', 'id', 'idtype', 'title', 'content', 'pageorder', 'dateline')),
		);
		mobile_core::result(mobile_core::variable($variable));
	}

}

?>