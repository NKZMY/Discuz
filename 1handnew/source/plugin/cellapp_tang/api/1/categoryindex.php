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
//查询出顶级频道APP的频道id，并查询顶级频道下的频道列表

$sql = 'select catid from '.DB::table('portal_category').' where catname=\'APP\'';//查询出顶级频道APP的频道id
$query = DB::QUERY($sql);
$row = DB::fetch($query);
$upid = $row['catid'];

//查询APP频道下面所有的频道列表
$sql = 'select a.catid,a.upid,a.catname,a.articles,a.allowcomment,a.displayorder,'
       .' a.uid,a.username,a.dateline,a.closed,a.description,b.vcname,b.parameter,b.logo from '.DB::table('portal_category').' as a'
       .' inner join '. DB::table('1hand_category').' as b on a.catid=b.catid where a.upid='.$upid;//.$_GET['upid']; 在这里可以写死upid
$query = DB::QUERY($sql);
$data = array();
			while($row = DB::fetch($query)) {
				$data[] = cellapp_core::getvalues($row, array('catid', 'upid', 'catname', 'articles', 'allowcomment', 'displayorder', 'notinheritedarticle', 
'notinheritedblock', 'domain', 'url', 'uid', 'username', 'dateline', 'closed', 'shownav', 'description', 
'vcname','parameter','logo'));
			}
//查询aid＝5的所有相关文章  aid=5是一篇专门用于存贮			


$sql = 'select a.aid,a.catid,a.bid,a.uid,a.username,a.title,a.highlight,a.author,a.from,a.fromurl,a.url,a.summary,'
       .' a.pic,a.thumb,a.remote,a.id,a.idtype,a.contents,a.allowcomment,a.owncomment,a.click1,a.click2,a.click3,a.click4,'
       .' a.click5,a.click6,a.click7,a.click8,a.tag,a.dateline,a.status,a.showinnernav,a.preaid,a.nextaid,a.htmlmade,a.htmlname,a.htmldir,'
       .' b.viewnum,b.commentnum,b.favtimes,b.sharetimes from '.DB::table('portal_article_title').' as a '
       .' left outer join '.DB::table('portal_article_count').' as b on a.aid=b.aid '
       .' left outer join '.DB::table('portal_article_related').' as c on c.raid=a.aid'
       .' where c.aid=5 ' 
       .' order by a.dateline desc'; 

   
$query = DB::QUERY($sql);
$datafirst = array();
			while($row = DB::fetch($query)) {
				$datafirst[] = cellapp_core::getvalues($row, array(
				'aid', 'catid', 'bid', 'uid', 'username', 'title', 'highlight', 'author', 'from', 'fromurl',
				 'url', 'summary', 'pic', 'thumb', 'remote', 'id', 'idtype', 'contents', 'allowcomment', 
				 'owncomment', 'click1', 'click2', 'click3', 'click4', 'click5', 'click6', 'click7', 
				 'click8', 'tag', 'dateline', 'status', 'showinnernav', 'preaid', 'nextaid', 'htmlmade',
				  'htmlname', 'htmldir','viewnum','commentnum','favtimes','sharetimes'));
		
			}


    $bouns =0;
    $bouns1 = 50;
		if(!$_G['uid']) {
			  //如果没有用户登陆直接返回列表
        $message=''; 
		}else {
			  //判断用户是否获得登陆奖励
			$row = C::t('#cellapp#1hand_user_login')->fetch_all_by_uid($_G['uid']);
		  if (count($row)>0){
   		   
   		    $atime1 = strtotime(date("y-m-d",strtotime($row[0]['lastlogin']))); //只要日期的时间戳
   		    $atime2 =strtotime(date("y-m-d"));
   		    if ($atime1 != $atime2){
   		       if (($atime2 - $atime1)>86400){
   		       	   $contiune=1;
   		       	   $bouns = $bouns1;
   		       } else {
   		       	  $contiune=$row[0]['contiunelogin'];
                $contiune = $contiune +1;
                if ($contiune == 2) {
                	$bouns =  70;
                } else if ($contiune == 3) {	 	   
                	$bouns =  90;
                } else if ($contiune == 4) {	 	   
                	$bouns =  110;
                } else if ($contiune == 5) {	 	   
                	$bouns =  130;
                } else if ($contiune == 6) {	 	   
                	$bouns =  160;
                } else if ($contiune >= 7) {	 	   
                	$bouns =  200;
                }
             }
             echo ('update');
             C::t('#cellapp#1hand_user_login')->update($_G['uid'],date("Y-m-d H:i:s"),$contiune);
             
         } else {
         	 $contiune=$row[0]['contiunelogin'];
             echo ('update  1');
             C::t('#cellapp#1hand_user_login')->update($_G['uid'],date("Y-m-d H:i:s"),$contiune);
         }	   
		    }else {
		    	//从未获得登陆奖励
		      $arr=array();
          $arr['uid'] = $_G['uid'];
          $arr['lastlogin'] =  date("Y-m-d H:i:s");
          $arr['contiunelogin'] = 1;
          $bouns=$bouns1;
		      C::t('#cellapp#1hand_user_login')->insert($arr);
		      
		    }
		    
		if 	($bouns>0){
			echo "bouns";
		  DB::query('update pre_common_member_count set extcredits8=extcredits8+%d where uid=%d',array($bouns,$_G['uid']));
		  DB::query('insert into  pre_1hand_user_extcredit_log(uid,systemtime,action,extcredits8) values(%d,%s,%s,%d)',
		       array($_G['uid'],date("Y-m-d H:i:s"), iconv("gb2312","UTF-8",'连续登陆奖励'),$bouns));
      $message = 		 iconv("gb2312","UTF-8",'连续登陆奖励 ').$bouns. iconv("gb2312","UTF-8",'刀 '); 
		}
		
		//
		//判断用户是否获得在线奖励
		//
		$row = C::t('#cellapp#1hand_user_online')->fetch_last_by_uid($_G['uid']);
		if (count($row)>0){
		
			  if ($row[0]['bouns_flag']==0){
			  	
   		    $lastdate = $row[0]['onlinedate']; 
   		    $time1 =  $row[0]['time1'];
   		    $time2 =  $row[0]['time2'];
   		    $time3 =  $row[0]['time3'];
   		    $time4 =  $row[0]['time4'];
   		    if (($time1>$lastdate) and ($time2>$lastdate) and ($time3>$lastdate) and ($time4>$lastdate)){
   		    	$bouns  = 10;
   		       			echo "bouns";
		        DB::query('update pre_1hand_user_online set bouns_flag=1 where uid=%d '
		            .' and onlinedate=%s ',array($_G['uid'],$lastdate));
		        DB::query('update pre_common_member_count set extcredits8=extcredits8+%d where uid=%d',array($bouns,$_G['uid']));
		        DB::query('insert into  pre_1hand_user_extcredit_log(uid,systemtime,action,extcredits8) values(%d,%s,%s,%d)',
		        array($_G['uid'],date("Y-m-d H:i:s"), iconv("gb2312","UTF-8",'上次在线奖励'),$bouns));
            $message = 	$message.chr(13).	 iconv("gb2312","UTF-8",'上次在线奖励 ').$bouns. iconv("gb2312","UTF-8",'刀 '); 
 	
   		    } 
   		    	
		    }
		   
	  }	
	  
}
		 $variable = array(
		    'onlineinterval' => '20.0',
				'data' => $data,
				'datafirst' => $datafirst,
				'message' => $message		);
    cellapp_core::result(cellapp_core::variable($variable));
    
?>