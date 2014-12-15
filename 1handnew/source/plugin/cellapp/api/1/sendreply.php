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
 
 
 
 
/*
$sql = 'select a.aid,a.catid,a.bid,a.uid,a.username,a.title,a.highlight,a.author,a.from,a.fromurl,a.url,a.summary,'
       .' a.pic,a.thumb,a.remote,a.id,a.idtype,a.contents,a.allowcomment,a.owncomment,a.click1,a.click2,a.click3,a.click4,'
       .' a.click5,a.click6,a.click7,a.click8,a.tag,a.dateline,a.status,a.showinnernav,a.preaid,a.nextaid,a.htmlmade,a.htmlname,a.htmldir,'
       .' b.viewnum,b.commentnum,b.favtimes,b.sharetimes,c.content '
       .' from '.DB::table('portal_article_title').' as a left outer join '.DB::table('portal_article_count')
       .' as b on a.aid=b.aid left outer join '.DB::table('portal_article_content').' as c on a.aid=c.aid where a.aid='.$_GET['aid'] 
       .' order by a.dateline desc '; 

   
$query = DB::QUERY($sql);
$data = array();
			while($row = DB::fetch($query)) {
				$data[] = cellapp_core::getvalues($row, array(
				'aid', 'catid', 'bid', 'uid', 'username', 'title', 'highlight', 'author', 'from', 'fromurl',
				 'url', 'summary', 'pic', 'thumb', 'remote', 'id', 'idtype', 'contents', 'allowcomment', 
				 'owncomment', 'click1', 'click2', 'click3', 'click4', 'click5', 'click6', 'click7', 
				 'click8', 'tag', 'dateline', 'status', 'showinnernav', 'preaid', 'nextaid', 'htmlmade',
				  'htmlname', 'htmldir','viewnum','commentnum','favtimes','sharetimes','content'));
		
			}
//读取相关文章
$sql = 'select a.aid,a.catid,a.bid,a.uid,a.username,a.title,a.highlight,a.author,a.from,a.fromurl,a.url,a.summary,'
       .' a.pic,a.thumb,a.remote,a.id,a.idtype,a.contents,a.allowcomment,a.owncomment,a.click1,a.click2,a.click3,a.click4,'
       .' a.click5,a.click6,a.click7,a.click8,a.tag,a.dateline,a.status,a.showinnernav,a.preaid,a.nextaid,a.htmlmade,a.htmlname,a.htmldir,'
       .' b.viewnum,b.commentnum,b.favtimes,b.sharetimes '
       .' from '.DB::table('portal_article_title').' as a left outer join '.DB::table('portal_article_count')
       .' as b on a.aid=b.aid left outer join '.DB::table('portal_article_related').' as c on a.aid=c.raid where c.aid='.$_GET['aid'] 
       .' order by a.dateline desc '; 

   
$query = DB::QUERY($sql);
$related = array();
			while($row = DB::fetch($query)) {
				$related[] = cellapp_core::getvalues($row, array(
				'aid', 'catid', 'bid', 'uid', 'username', 'title', 'highlight', 'author', 'from', 'fromurl',
				 'url', 'summary', 'pic', 'thumb', 'remote', 'id', 'idtype', 'contents', 'allowcomment', 
				 'owncomment', 'click1', 'click2', 'click3', 'click4', 'click5', 'click6', 'click7', 
				 'click8', 'tag', 'dateline', 'status', 'showinnernav', 'preaid', 'nextaid', 'htmlmade',
				  'htmlname', 'htmldir','viewnum','commentnum','favtimes','sharetimes','content'));
		
			}
//下面读取前5个评论
$sql = 'select a.id,a.uid,a.cid,a.idtype,a.postip,a.dateline,a.status,a.message,'
       .' b.username '
       .' from '.DB::table('portal_comment').' as a left outer join '.DB::table('ucenter_members')
       .' as b on a.uid=b.uid where a.id='.$_GET['aid'] 
       .' order by a.dateline desc limit 0,5 '; 

echo $sql;    
   
$query = DB::QUERY($sql);
$comment = array();
			while($row = DB::fetch($query)) {
				$comment[] = cellapp_core::getvalues($row, array(
				'id', 'uid', 'cid', 'idtype', 'postip', 'dateline', 'status', 'message', 'username'));
		
			}
	*/		
/*			
    $bouns1 = 50;
		if(!$_G['uid']) {
			  //如果没有用户登陆直接返回列表
        $message='';
		}else {
			  //判断用户是否获得阅读奖励
			$row = C::t('#cellapp#1hand_user_login')->fetch_all_by_uid($_G['uid']);
		  if (count($row)>0){
			
    }
    
    */
    //准备insert
        /* 表名 pre_portal_comment
   字段列表 cid
    uid,username,id,idtype,postip,dateline,status,message
*/
	if(!$_G['uid']) {	  //如果没有用户登陆直接返回列表
        $msg='必须登录才可以回复';
	}else {
	//判断用户是否获得评论奖励
	  //根据回复情况加分，首评 +10 次评 ＋2

	$row = C::t('#cellapp#1hand_user_article')->fetch_all_by_uid_action($_G['uid'],$aid,2);
	
	if (count($row)==0){
        //首评奖励10
		$attr = array();
		$attr['uid'] = $_G['uid'];
		$attr['aid'] = $aid;
		$attr['action'] = 2;
		$attr['systemtime'] = date("Y-m-d H:i:s");
		C::t('#cellapp#1hand_user_article')->insert($attr);  //记录用户阅读过这篇文章
        //发放阅读奖励
		echo "bouns";
		$bouns = 10;   //奖励分值
		DB::query('update pre_common_member_count set extcredits8=extcredits8+%d where uid=%d',array($bouns,$_G['uid']));
		DB::query('insert into  pre_1hand_user_extcredit_log(uid,systemtime,action,extcredits8) values(%d,%s,%s,%d)',
		       array($_G['uid'],date("Y-m-d H:i:s"), iconv("gb2312","UTF-8",'阅读文章奖励'),$bouns));
       $message = 		 iconv("gb2312","UTF-8",'评论文章奖励 ').$bouns. iconv("gb2312","UTF-8",'金币 '); 
		
	} else {
	
        //首评外奖励2
		$attr = array();
		$attr['uid'] = $_G['uid'];
		$attr['aid'] = $aid;
		$attr['action'] = 3;
		$attr['systemtime'] = date("Y-m-d H:i:s");
		C::t('#cellapp#1hand_user_article')->insert($attr);  //记录用户阅读过这篇文章
        //发放阅读奖励
		echo "bouns";
		$bouns = 2;   //奖励分值
		DB::query('update pre_common_member_count set extcredits8=extcredits8+%d where uid=%d',array($bouns,$_G['uid']));
		DB::query('insert into  pre_1hand_user_extcredit_log(uid,systemtime,action,extcredits8) values(%d,%s,%s,%d)',
		       array($_G['uid'],date("Y-m-d H:i:s"), iconv("gb2312","UTF-8",'阅读文章奖励'),$bouns));
       $message = 		 iconv("gb2312","UTF-8",'评论文章奖励 ').$bouns. iconv("gb2312","UTF-8",'金币 '); 
	
	}

      

	  //查询当前楼层
      $sql = 'select max(b.lou) as maxlou '
       .' from '.DB::table('portal_comment').' as a left outer join '.DB::table('1hand_comments_ext')
       .' as b on a.cid=b.cid where a.id='.$_POST['aid'] ;
      $query = DB::QUERY($sql);
      $row = DB::fetch($query);
      $maxlou = $row['maxlou'];
	   
	
        //$msg=$_G['member']['username']; 用户名
      $arr_comment=array();
      $arr_comment['uid'] = $_G['uid'];//用户id
      $arr_comment['username'] = $_G['username'];
      $arr_comment['id'] = $_POST['aid'];//文章id
      $arr_comment['idtype'] = 'aid';
      $arr_comment['postip'] =  $_G['clientip'] ;
      $arr_comment['dateline'] = $_G['timestamp'];
      $arr_comment['status'] = 0;
      $arr_comment['message'] = $_POST['message'];
	  C::t('portal_comment')->insert($arr_comment);
	  $cid = mysql_insert_id();
	  
    /*
      表名 pre_1hand_comments_ext
    字段列表 cid,      reply_cid,have_pic,pic,location,latitude,longitude,from,click,lou,commentnum,favtimes,sharetimes  
    */
	  $arr_ext = array();
	  $arr_ext['cid'] = $cid;
	  $arr_ext['reply_cid'] =     $_POST['reply_cid'];
	  $arr_ext['have_pic'] =      $_POST['have_pic'];
	  $arr_ext['pic'] =           $_POST['pic'];
	  $arr_ext['have_location'] = $_POST['have_location'];
	  $arr_ext['latitude'] =      $_POST['latitude'];
	  $arr_ext['longitude'] =     $_POST['longitude'];
	  $arr_ext['locationname'] = $_POST['locationname'];
	  $arr_ext['from'] =          $_POST['from'];
	  $arr_ext['click'] =         0;
	  $arr_ext['lou'] =         $maxlou+1;
	  $arr_ext['commentnum'] =  0;
	  $arr_ext['favtimes'] =    0;
	  $arr_ext['sharetimes'] =  0;	  
	  C::t('#cellapp#1hand_comments_ext')->insert($arr_ext);
      //修改回复评论的记录，给他的	  
      $sql = 'select max(b.lou) as maxlou '
       .' from '.DB::table('portal_comment').' as a left outer join '.DB::table('1hand_comments_ext')
       .' as b on a.cid=b.cid where a.id='.$_POST['aid'] ;
      $query = DB::QUERY($sql);
	  
    }
    
    $variable = array(
		'ok' => '1',
		'message' => $message,
		'maxlou' => $maxlou
	);
	//print_r($_G);    //查看全部变量
    cellapp_core::result(cellapp_core::variable($variable));
    
?>