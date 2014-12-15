<?php

//在data/attachment目录下新建1hand文件夹来存放已经上传了的文件
//文件上传功能：首先校验是不是客户端发送过来的。客户端post过来文件的名称后，完成文件的上传，返回成功标示。

//需要考虑的问题：如何保证文件上传完成；如果上传失败，客户端发起重新上传的请求。
// 图片、视频;picture、vedio


// 
// 图片统一放在data/attachment/1hand/picture下
// 视频统一放在data/attachment/1hand/vedio下


if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

$typeKind = $_POST['Kindtype'];

	//判断上传的种类

	
	if($typeKind == 'picture'){
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
				$baseUrl = "data/attachment/1hand/picture/";
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
			echo "too big";
		}
		$variable = array('result'=>$result);
		cellapp_core::result(cellapp_core::variable($variable));
	
	}
	else if($typeKind == 'vedio'){
		$size = $_FILES["file"]["size"]; //  得到文件的大小，size的单位是字节
		
		//判断文件的大小，如果文件超过规定大小则返回err；如果没有超过，则存储，然后返回成功标志。
		$limitedSize = 5000000;
		$result;
		if($size <= $limitedSize){
			if($_FILES["file"]["error"] > 0){
				//文件出错，返回错误信息
				$result = "error";
			}
			else {
				//文件合法，准备上传文件
				//还需要判断一下文件夹内是不是已经存在了同名文件
				$baseUrl = "data/attachment/1hand/vedio/";
				$fileName = $_FILES["file"]["name"];
				$url = $baseUrl . $fileName;
				if(file_exists($url)){
					$result =  "aready exist";
				}
				else {
							
					move_uploaded_file($_FILES["file"]["tmp_name"], $url);
					$result =  $url;
				}
				
			}
		}
		else {
			$result =  "too big";
		}
		
		$variable = array('result'=>$result);
		cellapp_core::result(cellapp_core::variable($variable));
		
	}


?>