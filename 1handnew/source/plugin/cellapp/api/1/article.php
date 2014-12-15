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

//������������
/*
  ���ģ��������ȡ���£���ȡ��������б���ȡǰ5�����ȵ�����
  �ж��Ƿ�ͶƱ���������ͶƱ����ȡtoup
  �����Ը����û���һ�λ���
  
*/
$aid=$_GET['aid'] ;
$uid=$_G['uid'];
//�����Ķ�����1
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
//��ȡ�������
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
//�����ȡǰ5������ �����µģ����Ҫ��Ϊ���ȵ���
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
//�ж��Ƿ�ͶƱ����������ͶƱ�����ݣ��뵱ǰ���
//			
$poll_flag = $data[0]['tag'];
if (($poll_flag & 8)==8){

	list($poll_tid, $poll_date, $poll_price) = split ('[,]', $data[0]['fromurl']);
	//��Ҫ��ȡѡ�����ݣ����⣬ͶƱ����ʱ�䣬����������
	$sql = 'select a.polloptionid,a.tid,a.votes,a.displayorder,a.polloption '
       .' from '.DB::table('forum_polloption').' as a  where a.tid='.$poll_tid
       .' order by a.displayorder '; 

    $query = DB::QUERY($sql);
    $pollotion = array();
	while($row = DB::fetch($query)) {
				$pollotion[] = cellapp_core::getvalues($row, array(
				'polloptionid', 'tid','votes','displayorder','polloption'));
		
    }
    //��ȡͶƱ����,ͶƱ����
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
//�����û�����
$bouns1 = 50;
if(!$uid) {
   //���û���û���½ֱ�ӷ����б�
	    echo('uid=null');
           $message='';
}else {
	//�ж��û��Ƿ����Ķ�����
	$row = C::t('#cellapp#1hand_user_article')->fetch_all_by_uid_action($_G['uid'],$aid,1);
	
	if (count($row)==0){
	
	    echo('row==0');
	
		$attr = array();
		$attr['uid'] = $_G['uid'];
		$attr['aid'] = $aid;
		$attr['action'] = 1;
		$attr['systemtime'] = date("Y-m-d H:i:s");
		C::t('#cellapp#1hand_user_article')->insert($attr);  //��¼�û��Ķ�����ƪ����
        //�����Ķ�����
		echo "bouns";
		$bouns = 5;
		DB::query('update pre_common_member_count set extcredits8=extcredits8+%d where uid=%d',array($bouns,$_G['uid']));
		DB::query('insert into  pre_1hand_user_extcredit_log(uid,systemtime,action,extcredits8) values(%d,%s,%s,%d)',
		       array($_G['uid'],date("Y-m-d H:i:s"), iconv("gb2312","UTF-8",'�Ķ����½���'),$bouns));
       $message = 		 iconv("gb2312","UTF-8",'�Ķ����½��� ').$bouns. iconv("gb2312","UTF-8",'��� '); 
		
	} else {
		    echo('row=='.count($row));
	
	}

}	
//�����Ķ�����1
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