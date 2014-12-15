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

$_GET['mod'] = 'mysearchcomment';
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
		$keywords = $_GET['keywords'];
		$keyword = explode("*", $keywords);
		$number = 0;
		foreach($keyword as $key){
			$test[$number] =  mysql_query("SELECT  cid FROM `pre_portal_comment` where message like '%$key%'");
			$number++;
			}
			
		/*为了得到包含关键词cid的一维数组*/
		$i = 0;
		$cid_array=array();
		for($flag = 0; $flag < $number; $flag++){
			while($row = mysql_fetch_array($test[$flag])){
				$cid_array[$i] = $row['cid'];
				$i++;
			}
		}
		
		/*对cid数组进行了排序，得到有序的cid数组*/
		$temp = array_count_values($cid_array);
		arsort($temp);
		reset($temp);
		$lastresult=array();
		$j = 0;
		while (list($key, $val) = each($temp))
		{
			$lastresult[$j]=$key;
			$j++;
		}
		
		/*按照得到的有序cid数组进行查询最后的结果*/	
		$numbertwo = 0;
		foreach($lastresult as $comment_id){
			$testtwo[$numbertwo] =  mysql_query("SELECT  * FROM `pre_portal_comment` where cid = '$comment_id'");
			$numbertwo++;
			}
			
		$k = 0;		
		for($flagtwo = 0; $flagtwo < $numbertwo; $flagtwo++){
			while($rowtwo = mysql_fetch_array($testtwo[$flagtwo])){
				$result[$k] = $rowtwo;
				$k++;
			}
		}
		/********************************************************/
		$_G['my_search_comment'] = $result;
		
		$variable = array(
			'my_search_comment' => mobile_core::getvalues($_G['my_search_comment'],array('/^\d+$/'),array('cid', 'uid', 'username', 'id', 'idtype', 'postip', 'dateline', 'status', 'message')),
		);
		mobile_core::result(mobile_core::variable($variable));
	  
		
	}

}

?>