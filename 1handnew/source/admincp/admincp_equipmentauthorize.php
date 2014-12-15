<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_district.php 26298 2011-12-08 03:58:22Z chenmengshu $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

include_once "table/table_1hand_equipment_authorize.php";
cpheader();
shownav('global', 'equipmentauthorize');
if(submitcheck('editsubmit')) {

	//实现更新操作
	foreach(C::t('1hand_equipment_authorize')->get_equipment_authorize()  as $value) {
		
	 if($_POST['authorize_state'][$value['id']] != $value['state'] ) {
			
			C::t('1hand_equipment_authorize')->update($value['id'], $_POST['authorize_state'][$value['id']]);
			
		}
		
	}
	cpmsg('装备库审核成功', 'action=equipmentauthorize', 'succeed');
}
else {
showsubmenu('装备库未审核');
	

	showformheader('equipmentauthorize');
	showtableheader();
	
	//将数据库中的数据读取到$thevalues[]中
	//echo C::t('1hand_equipment_authorize')->get_equipment_authorize($state);
	$thevalues = array();
	foreach(C::t('1hand_equipment_authorize')->get_equipment_authorize()  as $value) {
		$thevalues[] = array($value['id'], $value['uid'], $value['eid'], $value['tid'], $value['ename'], $value['state']);
		}
	echo cplang('装备库选择').' &nbsp; '.$html;
	
	//控制input的显示
	showsubtitle($values[0] ? array('', '用户id', '装备id', '申请链接', 'operation') : array('', '用户id', '装备id', '申请链接', 'operation'));
	foreach($thevalues as $value) {
		$valarr = array();
		$valarr[] = '';
		//$valarr[] = '<input type="text" id="displayorder_'.$value[0].'" class="txt" name="displayorder['.$value[0].']" value="'.$value[0].'"/>';
		$valarr[] = '<p id="p_'.$value[0].'"><input type="text" id="input_'.$value[0].'" class="txt" name="authorize_uid['.$value[0].']" value="'.$value[1].'"/></p>';
		$valarr[] = '<p id="p_'.$value[0].'"><input type="text" id="input_'.$value[0].'" class="txt" name="authorize_ename['.$value[0].']" value="'.$value[4].'"/></p>';
		$valarr[] = '<p id="p_'.$value[0].'"><a href="forum.php?mod=viewthread&tid='.$value[3].'" >查看原帖</a></p>';
		$valarr[] = '<p id="p_'.$value[0].'"><input type="text" id="input_'.$value[0].'" class="txt" name="authorize_state['.$value[0].']" value="'.$value[5].'"/></p>';
		showtablerow('id="td_'.$value[0].'"', array('', 'class="td25"','','','',''), $valarr);
	}
	showsubmit('editsubmit', 'submit');// 提交按钮
	showtablefooter();
	showformfooter();
	}
?>