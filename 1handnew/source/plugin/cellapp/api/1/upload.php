<?php

//��data/attachmentĿ¼���½�1hand�ļ���������Ѿ��ϴ��˵��ļ�
//�ļ��ϴ����ܣ�����У���ǲ��ǿͻ��˷��͹����ġ��ͻ���post�����ļ������ƺ�����ļ����ϴ������سɹ���ʾ��

//��Ҫ���ǵ����⣺��α�֤�ļ��ϴ���ɣ�����ϴ�ʧ�ܣ��ͻ��˷��������ϴ�������
// ͼƬ����Ƶ;picture��vedio


// 
// ͼƬͳһ����data/attachment/1hand/picture��
// ��Ƶͳһ����data/attachment/1hand/vedio��


if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

$typeKind = $_POST['Kindtype'];

	//�ж��ϴ�������

	
	if($typeKind == 'picture'){
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
		$size = $_FILES["file"]["size"]; //  �õ��ļ��Ĵ�С��size�ĵ�λ���ֽ�
		
		//�ж��ļ��Ĵ�С������ļ������涨��С�򷵻�err�����û�г�������洢��Ȼ�󷵻سɹ���־��
		$limitedSize = 5000000;
		$result;
		if($size <= $limitedSize){
			if($_FILES["file"]["error"] > 0){
				//�ļ��������ش�����Ϣ
				$result = "error";
			}
			else {
				//�ļ��Ϸ���׼���ϴ��ļ�
				//����Ҫ�ж�һ���ļ������ǲ����Ѿ�������ͬ���ļ�
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