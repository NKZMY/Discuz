<?php





/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: sendpm.php 27451 2012-02-01 05:48:47Z monkey $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

/*$_GET['mod'] = 'spacecp';
$_GET['ac'] = 'pm';*/
//$_GET['op'] = 'send';


//include_once 'home.php';
include_once 'source/include/spacecp/spacecp_pm';

		$fromuid = $_GET["fromuid"];
		$fromusername = $_GET["fromusername"];
		$touids = $_GET["touids"];
		$subject = $_GET["subject"];
		$message = $_GET["message"];
		sendpm($touids, $subject, $message, $fromuid );
/*include_once 'source/function/function_core.php';
*/


?>



	
