<?php

//�����ϴ��û���ͷ�񣬼��óɲ�ͬ�ĳߴ磬Ȼ������û���ID��hash����Ӧ���ļ�����
//ͼ�����֮��ĳߴ��ǣ�200*200��120*120��48*48
//�Լ���ʵ��˼·��1���û��ϴ���200*200��ͼƬ��2�����ļ��洢��ָ�����ļ����ڣ������޸��ļ������ơ�
// 					3���������ļ����м��ã�Ȼ��洢����Ӧ�ļ����أ�4������ϴ�����
if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}
define('UC_DATADIR', 'uc_server/data');
require_once 'thumb.php';


	

//$sourcepic = upload();
$sourcepic = "source/plugin/cellapp/api/1/1.jpg";
$uid = $_POST['uid'];
$home = get_home($uid);
if(!is_dir(UC_DATADIR.'/avatar/'.$home)) {
	set_home($uid, UC_DATADIR.'./avatar/');
	}

$avatar_big = UC_DATADIR .'/avatar/'.get_avatar($uid, 'big', $avatartype);
$avatar_middle = UC_DATADIR .'/avatar/'.get_avatar($uid, 'middle', $avatartype);
$avatar_small = UC_DATADIR .'/avatar/'.get_avatar($uid, 'small', $avatartype);

//���м��ò���
$t = new ThumbHandler(); //ʹ�ü��õĽӿ�

$t->setSrcImg("$sourcepic");
$t->setDstImg($avatar_big);
$t->createImg(200,200);

$t->setSrcImg("$sourcepic");
$t->setDstImg($avatar_middle);
$t->createImg(120,120);

$t->setSrcImg("$sourcepic");
$t->setDstImg($avatar_small);
$t->createImg(48,48);



//����uid���ߴ硢ͷ�����������ɴ���û�ͷ����ļ�

function upload(){
		$size = $_FILES["file"]["size"]; //  �õ��ļ��Ĵ�С��size�ĵ�λ���ֽ�
		
		//�ж��ļ��Ĵ�С������ļ������涨��С�򷵻�err�����û�г�������洢��Ȼ�󷵻سɹ���־��
		$limitedSize = 2000000;
		$result;
		if($size <= $limitedSize){
			if($_FILES["file"]["error"] > 0){
				//�ļ��������ش�����Ϣ
				$result = "error";
				
			}
			else {
				//�ļ��Ϸ���׼���ϴ��ļ�
				//����Ҫ�ж�һ���ļ������ǲ����Ѿ�������ͬ���ļ�
				$baseUrl = "data/attachment/1hand/avatar/";
				$fileName = $_FILES["file"]["name"];
				$url = $baseUrl . $fileName;
				if(file_exists($url)){
					$result =  "aready exist";
				}
				else {
							
					move_uploaded_file($_FILES["file"]["tmp_name"], $url);
					$result = $url;
				}
				
			}
		}
		else {
			$result =  "too big";
		}
		return $url;
}

function get_avatar($uid, $size = 'big', $type = '') {
		$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$typeadd = $type == 'real' ? '_real' : '';
		return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
	}
	
//����uid�����ɴ���û�ͷ���ļ��е�Ŀ¼
function set_home($uid, $dir = '.') {
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		!is_dir($dir.'/'.$dir1) && mkdir($dir.'/'.$dir1, 0777);
		!is_dir($dir.'/'.$dir1.'/'.$dir2) && mkdir($dir.'/'.$dir1.'/'.$dir2, 0777);
		!is_dir($dir.'/'.$dir1.'/'.$dir2.'/'.$dir3) && mkdir($dir.'/'.$dir1.'/'.$dir2.'/'.$dir3, 0777);
	}
	
//����uid�õ�����û�ͷ���ļ��е�Ŀ¼
function get_home($uid) {
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		return $dir1.'/'.$dir2.'/'.$dir3;
	}



?>