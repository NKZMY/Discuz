<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forumindex.php 27451 2012-02-01 05:48:47Z monkey $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}
  		if(!$_G['uid']) { exit;}

	$row = C::t('#cellapp#1hand_user_online')->fetch_today_by_uid($_G['uid']);
	if (count($row)>0){
	  		
    $lastdate = $row[0]['onlinedate']; 
    $time1 =  $row[0]['time1'];
    $time2 =  $row[0]['time2'];
    $time3 =  $row[0]['time3'];
    $time4 =  $row[0]['time4'];
    if ($time1>$lastdate){
       if ($time2>$lastdate){
          if ($time3>$lastdate){
             $time4 = time();
                 C::t('#cellapp#1hand_user_online')->update_time4($_G['uid'],date("Y-m-d H:i:s",$time4));

          } else {
           $time3 = time();
           C::t('#cellapp#1hand_user_online')->update_time3($_G['uid'],date("Y-m-d H:i:s",$time3));

          }
       } else {
         $time2 = time();
         C::t('#cellapp#1hand_user_online')->update_time2($_G['uid'],date("Y-m-d H:i:s",$time2));
       }   
    } else {
      $time1 = time();
      C::t('#cellapp#1hand_user_online')->update_time1($_G['uid'],date("Y-m-d H:i:s",$time1));
    }
	} else {
	
    C::t('#cellapp#1hand_user_online')->insert_blank($_G['uid']);
	
	}		
  echo 'ok';
?>