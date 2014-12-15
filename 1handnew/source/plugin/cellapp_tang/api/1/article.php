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

//读入文章内容
/*
  这个模块用来读取文章，读取相关文章列表，读取前5个最热的评论
  判断是否投票贴，如果是投票贴读取toup
  并可以给用用户加一次积分
  
*/
$aid=$_GET['aid'] ;
$uid=$_G['uid'];
//文章阅读数＋1
DB::query('update pre_portal_article_count set viewnum=viewnum+1 where aid=%d',array($aid));

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
//
//下面读取前5个评论 （最新的，如果要改为最热的则
//
$sql = 'select a.id,a.uid,a.cid,a.idtype,a.postip,a.dateline,a.status,a.message, b.username, '
       .' c.reply_cid,c.have_pic,c.pic,c.have_location,c.latitude,c.longitude,c.locationname, '
       .' c.from,c.click,c.lou,c.commentnum,c.favtimes,c.sharetimes '
       .' from '.DB::table('portal_comment').' as a '
       .' left outer join '.DB::table('1hand_comments_ext').' as c on c.cid=a.cid '
       .' left outer join '.DB::table('ucenter_members').' as b on a.uid=b.uid '
       .' where a.id='.$_GET['aid'].' and c.reply_cid is null '  
       .' order by a.dateline desc limit 0,5'; 

echo $sql;    
   
$query = DB::QUERY($sql);
$comment = array();
			while($row = DB::fetch($query)) {
				$comment[] = cellapp_core::getvalues($row, array(
				'id', 'uid', 'cid', 'idtype', 'postip', 'dateline', 'status', 'message', 'username',
                'reply_cid','have_pic','pic','have_location','latitude','longitude','locationname',
                'from','c.click','c.lou','commentnum','favtimes','sharetimes'
				));
		
			}
//
//判断是否投票贴，并读入投票贴内容，与当前结果
//			
$poll_flag = $data[0]['tag'];
if (($poll_flag & 8)==8){

	list($poll_tid, $poll_date, $poll_price) = split ('[,]', $data[0]['fromurl']);
	//需要读取选项内容，标题，投票结束时间，参与总人数
	$sql = 'select a.polloptionid,a.tid,a.votes,a.displayorder,a.polloption '
       .' from '.DB::table('forum_polloption').' as a  where a.tid='.$poll_tid
       .' order by a.displayorder '; 

    $query = DB::QUERY($sql);
    $pollotion = array();
	while($row = DB::fetch($query)) {
				$pollotion[] = cellapp_core::getvalues($row, array(
				'polloptionid', 'tid','votes','displayorder','polloption'));
		
    }
    //读取投票标题,投票设置
	$sql = 'select a.subject,b.overt,b.multiple,b.maxchoices,b.isimage,b.expiration,b.voters from '
	       .DB::table('forum_thread').' as a LEFT outer JOIN '
	       .DB::table('forum_poll').' as b on a.tid=b.tid where a.tid='.$poll_tid.' ';
    $query = DB::QUERY($sql);
    $row = DB::fetch($query);
    $poll= cellapp_core::getvalues($row, array(
				'subject', 'overt','multiple','maxchoices','isimage','expiration','voters'));
    $poll['poll_tid'] = $poll_tid;
    $poll['poll_date'] = $poll_date;
    $poll['poll_price'] = $poll_price;    

//
}
		
echo ('ok1');
//奖励用户积分
$bouns1 = 50;
if(!$uid) {
   //如果没有用户登陆直接返回列表
	    echo('uid=null');
           $message='';
}else {
	//判断用户是否获得阅读奖励
	$row = C::t('#cellapp#1hand_user_article')->fetch_all_by_uid_action($_G['uid'],$aid,1);
	
	if (count($row)==0){
	
	    echo('row==0');
	
		$attr = array();
		$attr['uid'] = $_G['uid'];
		$attr['aid'] = $aid;
		$attr['action'] = 1;
		$attr['systemtime'] = date("Y-m-d H:i:s");
		C::t('#cellapp#1hand_user_article')->insert($attr);  //记录用户阅读过这篇文章
        //发放阅读奖励
		echo "bouns";
		$bouns = 5;
		DB::query('update pre_common_member_count set extcredits8=extcredits8+%d where uid=%d',array($bouns,$_G['uid']));
		DB::query('insert into  pre_1hand_user_extcredit_log(uid,systemtime,action,extcredits8) values(%d,%s,%s,%d)',
		       array($_G['uid'],date("Y-m-d H:i:s"), iconv("gb2312","UTF-8",'阅读文章奖励'),$bouns));
       $message = 		 iconv("gb2312","UTF-8",'阅读文章奖励 ').$bouns. iconv("gb2312","UTF-8",'金币 '); 
		
	} else {
		    echo('row=='.count($row));
	
	}

}	
//文章阅读数＋1
		DB::query('update pre_portal_article_count set viewnum=viewnum+1 where aid=%d',array($aid));


		 $variable = array(
				'data' => $data,
				'comment' => $comment,
				'related' => $related,
				'message' => $message,
				'polloption' => $pollotion,
				'poll' => $poll
				);
    cellapp_core::result(cellapp_core::variable($variable));
?>