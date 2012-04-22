<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	if(User::logout()){
		header('Location: index.php');
	}
?>