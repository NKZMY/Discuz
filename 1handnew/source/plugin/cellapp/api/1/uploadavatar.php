<?php

//负责上传用户的头像，剪裁成不同的尺寸，然后根据用户的ID，hash到相应的文件夹中
//图像剪裁之后的尺寸是：200*200；120*120；48*48
//自己的实现思路：1、用户上传了200*200的图片；2、将文件存储在指定的文件夹内，并且修改文件的名称。
// 					3、继续将文件进行剪裁，然后存储在相应文件夹呢；4、完成上传工作
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

//进行剪裁部分
$t = new ThumbHandler(); //使用剪裁的接口

$t->setSrcImg("$sourcepic");
$t->setDstImg($avatar_big);
$t->createImg(200,200);

$t->setSrcImg("$sourcepic");
$t->setDstImg($avatar_middle);
$t->createImg(120,120);

$t->setSrcImg("$sourcepic");
$t->setDstImg($avatar_small);
$t->createImg(48,48);



//根据uid、尺寸、头像类型来生成存放用户头像的文件

function upload(){
		$size = $_FILES["file"]["size"]; //  得到文件的大小，size的单位是字节
		
		//判断文件的大小，如果文件超过规定大小则返回err；如果没有超过，则存储，然后返回成功标志。
		$limitedSize = 2000000;
		$result;
		if($size <= $limitedSize){
			if($_FILES["file"]["error"] > 0){
				//文件出错，返回错误信息
				$result = "error";
				
			}
			else {
				//文件合法，准备上传文件
				//还需要判断一下文件夹内是不是已经存在了同名文件
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
	
//根据uid，生成存放用户头像文件夹的目录
function set_home($uid, $dir = '.') {
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		!is_dir($dir.'/'.$dir1) && mkdir($dir.'/'.$dir1, 0777);
		!is_dir($dir.'/'.$dir1.'/'.$dir2) && mkdir($dir.'/'.$dir1.'/'.$dir2, 0777);
		!is_dir($dir.'/'.$dir1.'/'.$dir2.'/'.$dir3) && mkdir($dir.'/'.$dir1.'/'.$dir2.'/'.$dir3, 0777);
	}
	
//根据uid得到存放用户头像文件夹的目录
function get_home($uid) {
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		return $dir1.'/'.$dir2.'/'.$dir3;
	}



?>