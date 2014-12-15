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
//每页条数
if ($_GET['tpp']==''){ $_tpp=10;}
   else { $_tpp = $_GET['tpp'];} 
   	
if ($_GET['page']=='') { $_page=1;}
  else {$_page = $_GET['page'];}
  
$start = ($_page -1) * $_tpp;

if (($_GET['order']=='1')||($_GET['order']=='2')){
	$order = $_GET['order'];
}else {
	$order = 1;
}

$aid=$_GET['aid'];
/*
  读取评论模块
  目的，按页数来显示评论，如果提供了评论id，则只查询这个评论，和列表
  如果未提供评论id，则查询最热的三个评论
  输入参数 为tpp 每页返回条数
           page 页码
           order 排序方法 1＝ 按热度 2 按时间倒序
*/
/*


    第一部分：读取单一评论或最热评论



*/
if ($_page==1){
  //如果_page==1 则读取单一评论或最热评论，页码不为1则读取其他评论
  if  ($_GET['cid']!=0){
  
    //如果请求cid则读取单一评论,并且读取aid
    $sql = 'select a.id,a.uid,a.cid,a.idtype,a.postip,a.dateline,a.status,a.message, b.username, '
       .' c.reply_cid,c.have_pic,c.pic,c.have_location,c.latitude,c.longitude,c.locationname, '
       .' c.from,c.click,c.lou,c.commentnum,c.favtimes,c.sharetimes,d.resideprovince,d.residecity,d.gender '
       .' from '.DB::table('portal_comment').' as a '
       .' left outer join '.DB::table('1hand_comments_ext').' as c on c.cid=a.cid '
       .' left outer join '.DB::table('common_member').' as b on a.uid=b.uid '
       .' left outer join '.DB::table('common_member_profile').' as d on a.uid=d.uid '
       .' where a.cid='.$_GET['cid'];
    $query = DB::QUERY($sql);
    $row = DB::fetch($query);
    $comment[] = cellapp_core::getvalues($row, array(
			'id', 'uid', 'cid', 'idtype', 'postip', 'dateline', 'status', 'message', 'username',
            'reply_cid','have_pic','pic','have_location','latitude','longitude','locationname',
            'from','click','lou','commentnum','favtimes','sharetimes','resideprovince','residecity','gender'));
//    $aid=$comment[0]['id'];        

  } else {
   
   //如果没有请求cid则读取最热评论
   $sql = 'select a.id,a.uid,a.cid,a.idtype,a.postip,a.dateline,a.status,a.message, b.username, '
       .' c.reply_cid,c.have_pic,c.pic,c.have_location,c.latitude,c.longitude,c.locationname, '
       .' c.from,c.click,c.lou,c.commentnum,c.favtimes,c.sharetimes,d.resideprovince,d.residecity,d.gender '
       .' from '.DB::table('portal_comment').' as a '
       .' left outer join '.DB::table('1hand_comments_ext').' as c on c.cid=a.cid '
       .' left outer join '.DB::table('common_member').' as b on a.uid=b.uid '
       .' left outer join '.DB::table('common_member_profile').' as d on a.uid=d.uid '
       .' where a.id='.$aid.' and reply_cid is null '
       .' order by c.click,a.dateline desc limit 0,3'; 

   $query = DB::QUERY($sql);
   $comments_hot = array();
   while($row = DB::fetch($query)) {
    $comments_hot[] = cellapp_core::getvalues($row, array(
			'id', 'uid', 'cid', 'idtype', 'postip', 'dateline', 'status', 'message', 'username',
            'reply_cid','have_pic','pic','have_location','latitude','longitude','locationname',
            'from','click','lou','commentnum','favtimes','sharetimes','resideprovince','residecity','gender'));
   }
   
  }
}
/*
//
//第二部分：分页读取评论，只读取主评论
//


*/

$sql = 'select a.id,a.uid,a.cid,a.idtype,a.postip,a.dateline,a.status,a.message, b.username, '
       .' c.reply_cid,c.have_pic,c.pic,c.have_location,c.latitude,c.longitude,c.locationname, '
       .' c.from,c.click,c.lou,c.commentnum,c.favtimes,c.sharetimes,d.resideprovince,d.residecity,d.gender '
       .' from '.DB::table('portal_comment').' as a '
       .' left outer join '.DB::table('1hand_comments_ext').' as c on c.cid=a.cid '
       .' left outer join '.DB::table('common_member').' as b on a.uid=b.uid '
       .' left outer join '.DB::table('common_member_profile').' as d on a.uid=d.uid '
       .' where a.id='.$aid.' and (reply_cid =0 or reply_cid is null) ';
if ($order==1) {
	$sql = $sql . ' order by a.dateline desc '; 
}  else {
	$sql = $sql . ' order by c.click,a.dateline desc '; 
}
       
    $sql = $sql.' limit '.$start.','.$_tpp; 

$query = DB::QUERY($sql);
$comments = array();
while($row = DB::fetch($query)) {
  //每个主评论最多读取三个子评论
  $comments[] = cellapp_core::getvalues($row, array(
			'id', 'uid', 'cid', 'idtype', 'postip', 'dateline', 'status', 'message', 'username',
            'reply_cid','have_pic','pic','have_location','latitude','longitude','locationname',
            'from','click','lou','commentnum','favtimes','sharetimes','resideprovince','residecity','gender'));
  $len=count($comments);        
  //如果存在子评论则查询所有子评论
  if ($comments[$len-1]['commentnum'] >0){
    $subsql = 'select a.id,a.uid,a.cid,a.idtype,a.postip,a.dateline,a.status,a.message, b.username, '
       .' c.reply_cid,c.have_pic,c.pic,c.have_location,c.latitude,c.longitude,c.locationname, '
       .' c.from,c.click,c.lou,c.commentnum,c.favtimes,c.sharetimes,d.resideprovince,d.residecity,d.gender '
       .' from '.DB::table('portal_comment').' as a '
       .' left outer join '.DB::table('1hand_comments_ext').' as c on c.cid=a.cid '               //读子评论必须innerjoin ，不然会把所有地都列出来
       .' left outer join '.DB::table('common_member').' as b on a.uid=b.uid '
       .' left outer join '.DB::table('common_member_profile').' as d on a.uid=d.uid '
       .' where a.id='.$aid.' and reply_cid ='.$comments[$len-1]['cid']  
       .' order by a.dateline desc'; 
       
    $subquery = DB::QUERY($subsql);
    $subcomment = array();
    while($subrow = DB::fetch($subquery)) {
	  $subcomment[] = cellapp_core::getvalues($subrow, array(
			'id', 'uid', 'cid', 'idtype', 'postip', 'dateline', 'status', 'message', 'username',
            'reply_cid','have_pic','pic','have_location','latitude','longitude','locationname',
            'from','click','lou','commentnum','favtimes','sharetimes','resideprovince','residecity','gender'));
            //每个主评论最多读取三个子评论
    }
    $comments[$len-1]['subcomment'] = $subcomment;
  }
}


    $variable = array(
                'aid' => $aid,
				'comment' => $comment,
				'comments' => $comments,
				'comments_hot' => $comments_hot,
				'page' =>$_page,
				'message' => $message		
	);
    cellapp_core::result(cellapp_core::variable($variable));
