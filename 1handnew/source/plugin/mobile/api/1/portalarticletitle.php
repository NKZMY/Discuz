<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: portalarticletitle 29296 2012-04-01 01:56:37Z congyushuai $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

$_GET['mod'] = 'portalarticletitle';
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
		$category = $_GET['category'];
		$page = $_GET['page'];
		$tpp = $_GET['tpp'];
		$begin = ($page-1) * $tpp;
		
		//echo $category.$page.$tpp;
		$test = new table_portal_article_title();
		$number = $test->get_all_by_catid($category, $begin, $tpp);
		
		$_G['portal_article_title'] = $number;
      
		$variable = array(
			'portal_article_title' => mobile_core::getvalues($_G['portal_article_title'], array('/^\d+$/'), array('aid', 'catid', 'bid', 'uid', 'username', 'title', 'highlight', 'author', 'from', 'fromurl', 'url', 'summary', 'pic', 'thumb', 'remote', 'id', 'idtype', 'contents', 'allowcomment', 'owncomment', 'click1', 'click2', 'click3', 'click4', 'click5', 'click6', 'click7', 'click8', 'tag', 'dateline', 'status', 'showinnernav')),			
			'page' => $_G['page'],
			'tpp' => $_G['tpp'],
		);
		mobile_core::result(mobile_core::variable($variable));
	}

}

?>