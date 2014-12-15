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
include_once "table/table_1hand_equipment_class.php";

cpheader();
$default_name = array('类别', '品牌', '装备');
shownav('global', 'equipment');
$values = array(intval($_GET['cid']), intval($_GET['bid']), intval($_GET['eid']));
$elems = array($_GET['category'], $_GET['brand'], $_GET['equipment']);
$level = 1;
$upids = array(0);
$theid = 0;
for($i=0;$i<3;$i++) {
	if(!empty($values[$i])) {
		$theid = intval($values[$i]);
		//echo $theid;
		$upids[] = $theid;
		$level++;
	} else {
		for($j=$i; $j<3; $j++) {
			$values[$j] = '';
		}
		break;
	}
}
if(submitcheck('editsubmit')) {
	$delids = array();
	//实现更新操作
	foreach(C::t('1hand_equipment_class')->fetch_all_by_upid($theid) as $value) {
		
		if(!isset($_POST['equipment_ename'][$value['id']])) {
			$delids[] = $value['id'];
		
		} 
		else if($_POST['equipment_ename'][$value['id']] != $value['ename'] || $_POST['equipment_cname'][$value['id']] != $value['cname'] || $_POST['equipment_pic'][$value['id']] != $value['pic']) {
			//echo $_POST['equipment_pic'][$value['id']];
			C::t('1hand_equipment_class')->update($value['id'], $_POST['equipment_ename'][$value['id']], $_POST['equipment_cname'][$value['id']], 
				$_POST['equipment_pic'][$value['id']]);
			
		}
		
	}
	//实现删除操作
	if($delids) {
		
		$ids = $delids;
		for($i=$level; $i<3; $i++) {
			$ids = array();
			foreach(C::t('1hand_equipment_class')->fetch_all_by_upid($delids) as $value) {
				$value['id'] = intval($value['id']);
				$delids[] = $value['id'];
				$ids[] = $value['id'];
			}
			if(empty($ids)) {
				break;
			}
		}
		
		 C::t('1hand_equipment_class')->delete($delids);	
	}
	//实现添加操作
	if(!empty($_POST['equipmentnew_cname'])) {
		for($i=0; $i < sizeof($_POST['equipmentnew_cname']); $i++){
			if(!empty($_POST['equipmentnew_cname'][$i])){
				
				
				C::t('1hand_equipment_class')->insert($_POST['equipmentnew_ename'][$i], $_POST['equipmentnew_cname'][$i], $level, $theid, $_POST['equipmentnew_pic'][$i]);
			}
		}
	}
	cpmsg('装备库设置成功', 'action=equipment&cid='.$values[0].'&bid='.$values[1].'&eid='.$values[2], 'succeed');
	
}
else {

	showsubmenu('装备库设置');
	showtips('district_tips');

	showformheader('equipment&cid='.$values[0].'&bid='.$values[1].'&eid='.$values[2]);
	showtableheader();
	
	//将数据库中的数据读取到$thevalues[]中
	$options = array(1=>array(), 2=>array(), 3=>array());
	$thevalues = array();
	foreach(C::t('1hand_equipment_class')->fetch_all_by_upid($upids)  as $value) {
		$options[$value['level']][] = array($value['id'], $value['cname']);
		
		if($value['upid'] == $theid) {
			
			$thevalues[] = array($value['id'], $value['ename'], $value['cname'], $value['pic']);
		}
	}

	$names = array('category', 'brand', 'equipment');
	for($i=0; $i<3;$i++) {
		$elems[$i] = !empty($elems[$i]) ? $elems[$i] : $names[$i];
	}
	//控制select的显示
	$html = '';
	for($i=0;$i<3;$i++) {
		$l = $i+1;
		$jscall = ($i == 0 ? 'this.form.brand.value=\'\';this.form.equipment.value=\'\';' : '')."refreshdistrict('$elems[0]', '$elems[1]', '$elems[2]')";
		$html .= '<select name="'.$elems[$i].'" id="'.$elems[$i].'" onchange="'.$jscall.'">';
		$html .= '<option value="">'.$default_name[$i].'</option>';
		foreach($options[$l] as $option) {
			
			$selected = $option[0] == $values[$i] ? ' selected="selected"' : '';
			$html .= '<option value="'.$option[0].'"'.$selected.'>'.$option[1].'</option>';
		}
		$html .= '</select>&nbsp;&nbsp;';
	}
	echo cplang('装备库选择').' &nbsp; '.$html;
	
	//控制input的显示
	showsubtitle($values[0] ? array('', '英文名称', '中文名称', '上传图片', 'operation') : array('', '英文名称', '中文名称', '上传图片', 'operation'));
	foreach($thevalues as $value) {
		$valarr = array();
		$valarr[] = '';
		//$valarr[] = '<input type="text" id="displayorder_'.$value[0].'" class="txt" name="displayorder['.$value[0].']" value="'.$value[0].'"/>';
		$valarr[] = '<p id="p_'.$value[0].'"><input type="text" id="input_'.$value[0].'" class="txt" name="equipment_ename['.$value[0].']" value="'.$value[1].'"/></p>';
		$valarr[] = '<p id="p_'.$value[0].'"><input type="text" id="input_'.$value[0].'" class="txt" name="equipment_cname['.$value[0].']" value="'.$value[2].'"/></p>';
		$valarr[] = '<p id="p_'.$value[0].'"><input type="file" id="input_'.$value[0].'" class="file" name="equipment_pic['.$value[0].']" value="'.$value[3].'"/>'.$value[3].'</p>';
		
		$valarr[] = '<a href="javascript:;" onclick="deletedistrict('.$value[0].');return false;">'.cplang('delete').'</a>';
		showtablerow('id="td_'.$value[0].'"', array('', 'class="td25"','','','',''), $valarr);
	}
	showtablerow('', array('colspan=2'), array(
			'<div><a href="javascript:;" onclick="addrow(this, 0, 1);return false;" class="addtr">'.cplang('add').'</a></div>'
		)); //“添加”按钮
	showsubmit('editsubmit', 'submit');// 提交按钮
	$adminurl = ADMINSCRIPT.'?action=equipment';
echo <<<SCRIPT
<script type="text/javascript">
var rowtypedata = [
	[[1,'', ''],[1,'<input type="text" class="txt" name="equipmentnew_ename[]" value="" />', 'td25'],[2,'<input type="text" class="txt" name="equipmentnew_cname[]" value="" />', ''],[3,'<input type="file" class="file" name="equipmentnew_pic[]" value="" />', '']],
];

function refreshdistrict(category, brand, equipment) {
	location.href = "$adminurl"
		+ "&category="+category+"&brand="+brand+"&equipment="+equipment
		+"&cid="+$(category).value + "&bid="+$(brand).value+"&eid="+$(equipment).value;
}
function editdistrict(did) {
	$('input_' + did).style.display = "block";
	$('span_' + did).style.display = "none";
}

function deletedistrict(did) {
	var elem = $('p_' + did);
	elem.parentNode.removeChild(elem);
	var elem = $('td_' + did);
	elem.parentNode.removeChild(elem);
}



</script>
SCRIPT;
	showtablefooter();
	showformfooter();
}

?>