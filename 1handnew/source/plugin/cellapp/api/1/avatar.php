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
define('UC_DATADIR', 'uc_server/data');

if ($_GET['uid']=='') { 
   //如果没有uid
  $uid='2';
} else {
  $uid = $_GET['uid'];
}
$size = in_array($_GET['size'], array('big', 'middle', 'small')) ? $_GET['size'] : 'big';


$home = get_home($uid);
if(!is_dir(UC_DATADIR.'/avatar/'.$home)) {
   set_home($uid, UC_DATADIR.'./avatar/');
}

$avatar_name = UC_DATADIR .'/avatar/'.get_avatar($uid, $size, $avatartype);

$sql = 'select a.gender from '
    .DB::table('common_member_profile').' as a where uid='.$uid;
$query = DB::QUERY($sql);
$data = array();
$row = DB::fetch($query);
$gender = $row['gender'];

//根据性别确定圈的文件
$quanArray = array(
   '1'=>array('big'   =>'data/1handdata/avatar/avatar_l_common_male.png',
              'middle'=>'data/1handdata/avatar/avatar_m_common_male.png',
              'small'=>'data/1handdata/avatar/avatar_s_common_male.png'
              ),
   '2'=>array('big'   =>'data/1handdata/avatar/avatar_l_common_female.png',
              'middle'=>'data/1handdata/avatar/avatar_m_common_female.png',
              'small'=>'data/1handdata/avatar/avatar_s_common_female.png'
              ),
   '0'=>array('big'   =>'data/1handdata/avatar/avatar_l_unregisteredandsecret.png',
              'middle'=>'data/1handdata/avatar/avatar_s_unregisteredandsecret.png',
              'small'=>'data/1handdata/avatar/avatar_s_unregisteredandsecret.png'
              )
   );
//print_r ($quan);
$quan = $quanArray[$gender][$size];


//否则加框后输出文件
$img = imagecreatefromjpeg($avatar_name);
$quanimage = imagecreatefrompng($quan);
$crop = new CircleCrop($img);
$crop->crop($quanimage)->display();


//根据uid，返回头像文件名
function get_avatar($uid, $size = 'big', $gender = '') {
		$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$genderadd = $gender == '' ? '' : $gender;
  return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$genderadd."_avatar_$size.jpg";
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

class CircleCrop
{

    private $src_img;
    private $src_w;
    private $src_h;
    private $dst_img;
    private $dst_w;
    private $dst_h;

    public function __construct($img)
    {
        
        $this->src_img = $img;
        $this->src_w = imagesx($img);
        $this->src_h = imagesy($img);
        $this->dst_w = imagesx($img);
        $this->dst_h = imagesy($img);
    }

    public function __destruct()
    {
        if (is_resource($this->dst_img))
        {
            imagedestroy($this->dst_img);
        }
    }

        function LoadJpeg($imgname)
{
    /* 尝试打开 */
    $im = @imagecreatefromjpeg($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a black image */
        $im  = imagecreatetruecolor(500, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 500, 30, $bgc);

        /* Output an error message */
        imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
    }

    return $im;
}

    public function display()
    {
        header("Content-type: image/png");
        imagepng($this->dst_img);
        return $this;
    }

    public function reset()
    {
        if (is_resource(($this->dst_img)))
        {
            imagedestroy($this->dst_img);
        }
        $this->dst_img = imagecreatetruecolor($this->dst_w, $this->dst_h);
        imagecopy($this->dst_img, $this->src_img, 0, 0, 0, 0, $this->dst_w, $this->dst_h);
        return $this;
    }

    public function size($dstWidth, $dstHeight)
    {
        $this->dst_w = $dstWidth;
        $this->dst_h = $dstHeight;
        return $this->reset();
    }

    public function crop($quan)
    {
        // Intializes destination image
        $this->reset();

        // Create a black image with a transparent ellipse, and merge with destination
        $mask = imagecreatetruecolor($this->dst_w, $this->dst_h);
        $maskTransparent = imagecolorallocate($mask, 255, 255, 255);
        imagecolortransparent($mask, $maskTransparent);
		
        imagefilledellipse($mask, $this->dst_w / 2, $this->dst_h / 2, $this->dst_w-3, $this->dst_h-3, $maskTransparent);
		
        imagecopymerge($this->dst_img, $mask, 0, 0, 0, 0, $this->dst_w, $this->dst_h, 100);
         //imagecopymerge($this->dst_img, $mask, 0, 0, 0, 0, 100, 100 , 100);
        $w = imagesx($quan);
        $h = imagesy($quan);

        $newquan = imagecreate($this->dst_w, $this->dst_h);
        imagecopyresized($newquan, $quan, 0, 0, 0, 0, $this->dst_w, $this->dst_h, $w,$h);
		
        //imagecopy($this->dst_img, $newquan, 0, 0, 0, 0, $this->dst_w, $this->dst_h);

        // Fill each corners of destination image with transparency
        $dstTransparent = imagecolorallocate($this->dst_img, 255, 255, 255);
        imagefill($this->dst_img, 0, 0, $dstTransparent);
		imagefill($this->dst_img, $this->dst_w , 0, $dstTransparent);
        imagefill($this->dst_img, 0, $this->dst_h - 1, $dstTransparent);
        imagefill($this->dst_img, $this->dst_w - 1, $this->dst_h - 1, $dstTransparent);
        imagecolortransparent($this->dst_img, $dstTransparent);
		imagecopy($this->dst_img, $newquan, 0, 0, 0, 0, $this->dst_w, $this->dst_h);
//        $quan = imagecreatefrompng('data/1handdata/avatar/avatar_l_common_female.png');
//        imagecopymerge($this->dst_img, $quan, 0, 0, 0, 0, $this->dst_w, $this->dst_h, 100);
        
        return $this;
    }
}

//new CircleCrop(  );
//$crop->display();

?>
