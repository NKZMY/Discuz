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
/*

   接受上传评论的图片文件，把它们存储在/data/attachment/portal/年/月




*/
	$upload = new discuz_upload();
	$_FILES["Filedata"]['name'] = addslashes(diconv(urldecode($_FILES["Filedata"]['name']), 'UTF-8'));
	$upload->init($_FILES['Filedata'], 'portal');
	$attach = $upload->attach;
	if(!$upload->error()) {
		$upload->save();
	}
	if($upload->error()) {
		$errorcode = 4;
	}
	//如果没有错误
	if(!$errorcode) {
		if($attach['isimage'] && empty($_G['setting']['portalarticleimgthumbclosed'])) {
			require_once libfile('class/image');
			$image = new image();
			$thumbimgwidth = $_G['setting']['portalarticleimgthumbwidth'] ? $_G['setting']['portalarticleimgthumbwidth'] : 300;
			$thumbimgheight = $_G['setting']['portalarticleimgthumbheight'] ? $_G['setting']['portalarticleimgthumbheight'] : 300;
			$attach['thumb'] = $image->Thumb($attach['target'], '', $thumbimgwidth, $thumbimgheight, 2);
			$image->Watermark($attach['target'], '', 'portal');
			//echo "image watermark";
		}

		if(getglobal('setting/ftp/on') && ((!$_G['setting']['ftp']['allowedexts'] && !$_G['setting']['ftp']['disallowedexts']) || ($_G['setting']['ftp']['allowedexts'] && in_array($attach['ext'], $_G['setting']['ftp']['allowedexts'])) || ($_G['setting']['ftp']['disallowedexts'] && !in_array($attach['ext'], $_G['setting']['ftp']['disallowedexts']))) && (!$_G['setting']['ftp']['minsize'] || $attach['size'] >= $_G['setting']['ftp']['minsize'] * 1024)) {
			if(ftpcmd('upload', 'portal/'.$attach['attachment']) && (!$attach['thumb'] || ftpcmd('upload', 'portal/'.getimgthumbname($attach['attachment'])))) {
				@unlink($_G['setting']['attachdir'].'/portal/'.$attach['attachment']);
				@unlink($_G['setting']['attachdir'].'/portal/'.getimgthumbname($attach['attachment']));
				$attach['remote'] = 1;
			} else {
				if(getglobal('setting/ftp/mirror')) {
					@unlink($attach['target']);
					@unlink(getimgthumbname($attach['target']));
					$errorcode = 5;
				}
			}
		}

		$setarr = array(
			'uid' => $_G['uid'],
			'filename' => $attach['name'],
			'attachment' => $attach['attachment'],
			'filesize' => $attach['size'],
			'isimage' => $attach['isimage'],
			'thumb' => $attach['thumb'],
			'remote' => $attach['remote'],
			'filetype' => $attach['extension'],
			'dateline' => $_G['timestamp'],
			'aid' => $aid
		);
		$setarr['attachid'] = C::t('portal_attachment')->insert($setarr, true);
		if($attach['isimage']) {
			require_once libfile('function/home');
			$smallimg = pic_get($attach['attachment'], 'portal', $attach['thumb'], $attach['remote']);
			$bigimg = pic_get($attach['attachment'], 'portal', 0, $attach['remote']);
			$coverstr = addslashes(serialize(array('pic'=>'portal/'.$attach['attachment'], 'thumb'=>$attach['thumb'], 'remote'=>$attach['remote'])));
			echo "{\"aid\":$setarr[attachid], \"isimage\":$attach[isimage], \"smallimg\":\"$smallimg\", \"bigimg\":\"$bigimg\"}";
			exit();
		} else {
			$fileurl = 'portal.php?mod=attachment&id='.$attach['attachid'];
			echo "{\"aid\":$setarr[attachid], \"isimage\":$attach[isimage], \"file\":\"$fileurl\", \"errorcode\":$errorcode}";
			exit();
		}
	}
	 else
	
	 {
		echo "{\"aid\":0, \"errorcode\":$errorcode}";
	}


?>