<?php
echo '<form action="equipmentpic_upload.php" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="hidden" name="MAX_FILE_SIZE" value="20000000000000000">
<input type="file" name="files" value="haha"/> 
<br />
<input type="submit"  value="ÉÏ´«" />
</form>';

echo $_FILES["files"]["name"]."<br>";
echo "tmp_name:".$_FILES["files"]["tmp_name"]."<br>";
echo "size:".$_FILES["files"]["size"]."<br>";
echo "err:".$_FILES["files"]["error"]."<br>";
$url = "equipment_pic/".$_FILES["files"]["name"];
move_uploaded_file($_FILES["files"]["tmp_name"], $url);
?>