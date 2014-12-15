<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forumupload.php 27451 2012-02-01 05:48:47Z monkey $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}
echo "hello";

if ($_G['uid']==''){ 
  $message='需要登录';
} else { 
  //如果
  $uid=$_GET['uid']; 
  if ($uid=='')
  {
    $uid= $_G['uid'];
  }
  
  $sql = 'select a.uid,a.realname,a.gender,a.birthyear,a.birthmonth,a.birthday,a.idcardtype,a.idcard, '
       .' b.username,b.email,b.groupid,b.newpm,b.newprompt,'//groupid 用户分组，区分商家用？
       .' c.extcredits1,c.extcredits2,c.extcredits3,c.extcredits6,c.extcredits7,c.extcredits8,c.follower,c.following,'
       .' d.sightml '
       .' from '.DB::table('common_member_profile').' as a inner join '.DB::table('common_member').' as b on a.uid=b.uid '
       .' inner join '.DB::table('common_member_count').' as c on a.uid=c.uid '
       .' inner join '.DB::table('common_member_field_forum').' as d on a.uid=d.uid '

       .' where a.uid='.$uid ;
  
  $query = DB::QUERY($sql);
  $data = array();
  while($row = DB::fetch($query)) {
	$data[] = cellapp_core::getvalues($row, array(
	'uid', 'realname', 'gender', 'birthyear', 'birthmonth', 'birthday', 'idcardtype', 'idcard',
       'username','email','groupid','newpm','newprompt',
       'extcredits1','extcredits2','extcredits3','extcredits6','extcredits7','extcredits8','follower','following',
       'sightml'
	
	));
  }
		
}

$variable = array(
				'data' => $data,
				'message' => $message		);
cellapp_core::result(cellapp_core::variable($variable));
  
?>