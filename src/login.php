<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	if(User::resume()){
		header('Location: sapiens.php');
	}
	if($_POST && $_POST['username'] && $_POST['password']){
		$result = User::login($_POST['username'], $_POST['password']);
		if($result != -1 && $result != -2){
			header('Location: sapiens.php');
		}
	}
?>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAPIENS - Login</title>
<link rel="stylesheet" type="text/css" href="../public/css/sapiens.css" />
<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Chau+Philomene+One' rel='stylesheet' type='text/css'></head>
<body>
<div id ="title">SAPI<span style="color:#4CE11C;">ENS</span></div>
<div id="container">
<div class="register">
	<form action="login.php" method="post">
		<label for="username">Username</label><input type="text" name="username"></br>
		<label for="password">Password</label><input type="password" name="password"></br></br>
		<input type="submit" name="login" value="Login"></br>
		<?php
		if(isset($result) && $result==-1)
			echo "<div class=\"error\">Username not found.</div>\n";
		elseif(isset($result) && $result==-2)
			echo "<div class=\"error\">Incorrect user/pass combo</div>\n";
		?>
		</br><a href="register.php">Register</a>
	</form>
</div>
</div>
</body>
</html>