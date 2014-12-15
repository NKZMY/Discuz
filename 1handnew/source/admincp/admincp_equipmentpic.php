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

showformheader('equipmentpic');
echo '<label for="file">上传装备库的图片：</label>
<input type="hidden" name="MAX_FILE_SIZE" value="20000000000000000">
<input type="file" name="files" value="haha"/> 
<br />
<input type="submit"  value="submit" />
</form>';
showformfooter();

echo $_FILES["files"]["name"]."<br>";
echo "tmp_name:".$_FILES["files"]["tmp_name"]."<br>";
echo "size:".$_FILES["files"]["size"]."<br>";
echo "err:".$_FILES["files"]["error"]."<br>";
chdir("../");
$url = "/data/attachment/equipment".$_FILES["files"]["name"];
move_uploaded_file($_FILES["files"]["tmp_name"], $url);

?>