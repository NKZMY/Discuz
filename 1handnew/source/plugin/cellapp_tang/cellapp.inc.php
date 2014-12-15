<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: cloudsearch.inc.php 29366 2012-04-09 03:00:26Z zhouxiaobo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('IN_MOBILE_API', 1);
define('IN_MOBILE', 1);


require_once 'source/plugin/cellapp/cellapp.class.php';

$_GET['mobile'] = 'no';

$modules = array(
		'categoryindex','onlinetime','articlelist','article','sendreply','commentlist',
		'portal','portalupload','profile');


if(!in_array($_GET['module'], $modules)) {
	cellapp_core::result(array('error' => 'module_not_support module='.$_GET['module']));
}

$_GET['version'] = !empty($_GET['version']) ? intval($_GET['version']) : 1;
$_GET['version'] = $_GET['version'] > MOBILE_PLUGIN_VERSION ? MOBILE_PLUGIN_VERSION : $_GET['version'];

if(empty($_GET['module']) || empty($_GET['version']) || !preg_match('/^[\w\.]+$/', $_GET['module']) || !preg_match('/^[\d\.]+$/', $_GET['version'])) {
	cellapp_core::result(array('error' => 'param_error'));
}

if($_GET['module'] == 'extends') {
	require_once 'source/plugin/cellapp/mobile_extends.php';
	return;
}

$apifile = 'source/plugin/cellapp/api/'.$_GET['version'].'/'.$_GET['module'].'.php';

if(file_exists($apifile)) {
	require_once $apifile;
} else {
	if($_GET['version'] > 1) {
		for($i = $_GET['version']; $i >= 1; $i--) {
			$apifile = 'source/plugin/cellapp/api/'.$i.'/'.$_GET['module'].'.php';
			if(file_exists($apifile)) {
				$_GET['version'] = $i;
				require_once $apifile;
				break;
			} elseif($i==1 && !file_exists($apifile)) {
				cellapp_core::result(array('error' => 'module_not_exists  with'.$apifile));
			}
		}
	} else {
		cellapp_core::result(array('error' => 'module_not_exist '.$apifile));
	}
}
?>