<?php

if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}
include_once 'pm.php';
$fromuid = $_GET["fromuid"];
$fromusername = $_GET["fromusername"];
$touids = $_GET["touids"];
$subject = $_GET["subject"];
$message = $_GET["message"];
test();
//sendpm($fromuid, $fromusername, $touids, $subject, $message);
//return $result;

?>