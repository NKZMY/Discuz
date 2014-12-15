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
include_once 'portal.php';

$_GET['mod'] = 'mysearcharticle';


		
		global $_G;
		$keywords = $_GET['keywords'];
		$keyword = explode("*", $keywords);
		$number = 0;
		foreach($keyword as $key){
			$test[$number] =  mysql_query("SELECT  aid FROM `newultrax`.`pre_portal_article_title` where title like '%$key%'");
			$number++;
			}
			
		/*为了得到包含关键词aid的一维数组*/
		$i = 0;
		$aid_array=array();
		for($flag = 0; $flag < $number; $flag++){
			while($row = mysql_fetch_array($test[$flag])){
				$aid_array[$i] = $row['aid'];
				$i++;
			}
		}
		
		/*对aid数组进行了排序，得到有序的aid数组*/
		$temp = array_count_values($aid_array);
		arsort($temp);
		reset($temp);
		$lastresult=array();
		$j = 0;
		while (list($key, $val) = each($temp))
		{
			$lastresult[$j]=$key;
			$j++;
		}
		
		/*按照得到的有序aid数组进行查询最后的结果*/	
		$numbertwo = 0;
		foreach($lastresult as $article_id){
			$testtwo[$numbertwo] =  mysql_query("SELECT  * FROM `newultrax`.`pre_portal_article_title` where aid = '$article_id'");
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
		$_G['my_search_article'] = $result;
		
		$variable = array(
			'my_search_article' => mobile_core::getvalues($_G['my_search_article'],array('/^\d+$/'),array('aid', 'catid', 'bid', 'uid', 'username', 'title', 'highlight', 'author', 'from', 'fromurl', 'url', 'summary', 'pic', 'thumb', 'remote', 'id', 'idtype', 'contents', 'allowcomment', 'owncomment', 'click1', 'click2', 'click3', 'click4', 'click5', 'click6', 'click7', 'click8', 'tag', 'dateline', 'status', 'showinnernav', 'preaid', 'nextaid', 'htmlmade', 'htmlname', 'htmldir')),
		);
		cellapp_core::result(cellapp_core::variable($variable));
	  
		
	



?>