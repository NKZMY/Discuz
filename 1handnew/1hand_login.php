<?php
$name = $_POST["name"];
$password = $_POST["password"];
if($name == "1hand" && $password == "1hand"){
	header('location: member.php?mod=logging&action=login');
	
	setcookie("user", "1hand", time()+360000);

}
else {
header('location: 1hand_login.html');
}

?>