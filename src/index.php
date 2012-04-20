<?php
	include("../lib/global.php");
	if($_POST && $_POST['username'] && $_POST['password']){
		$result = login($_POST['username'], $_POST['password']);
		echo "login: " . $result;
	}
?>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAPIENS Login</title>
<link rel="stylesheet" type="text/css" href="../public/css/sapiens.css" />
<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'></head>
<body>
<div id ="title">SAPI<span style="color:#4CE11C;">ENS</span></div>
<div id="container">
<div class="register">
	<form action="login.php" method="post">
		<label for="username">Username</label><input type="text" name="username"></br>
		<label for="password">Password</label><input type="password" name="password"></br>
		<input type="submit" name="login" value="login">
	</form>
</div>
</div>
</body>
</html>