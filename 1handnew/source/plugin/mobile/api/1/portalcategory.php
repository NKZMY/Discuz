<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forumdisplay.php 29296 2012-04-01 01:56:37Z congyushuai $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

$_GET['mod'] = 'portalcategory';
include_once 'portal.php';
include_once 'source\class\table\table_portal_category.php';

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
		
		$test = new table_portal_category();
		$number = $test->getAll();
		
		$_G['portal_category'] = $number;
		$variable = array(
			'portal_category' => mobile_core::getvalues($_G['portal_category'], array('/^\d+$/'), array('catid', 'upid', 'catname', 'articles', 'allowcomment', 'displayorder', 'notinheritedarticle', 'notinheritedblock', 'domain', 'url', 'uid', 'username', 'dateline', 'closed', 'shownav', 'description', 'seotitle', 'keyword', 'primaltplname', 'articleprimaltplname', 'disallowpublish', 'foldername', 'notshowarticlesummay', 'perpage', 'maxpages'))
		);

		mobile_core::result(mobile_core::variable($variable));
	
	}

}

?>