<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	if(User::resume()){
		include("links.php");
	}
	else{
		include("login.php");
	}
?>