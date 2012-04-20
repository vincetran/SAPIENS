<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Assignment 3</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<?php
	include("global.php");

?>
<body>
<div id="container">
	<div id="center">
	<form action="newuser.php" method="post">
		Username:* <input type="text" name="username"></br>
		Password:* <input type="password" name="password"></br>
		Email:* <input type="text" name="email"></br>
		First Name: <input type="text" name="fName"></br>
		Last Name: <input type="text" name="lName"></br></br>
		* = Required
		<?php
			if(isset($_POST['register']))
			{
				if(register($_POST['username'], $_POST['password'], $_POST['fName'], $_POST['lName'], $_POST['email']) == -1)
				{
					echo "<p class=\"error\">Username already exists. Please choose a new username.</p>";
				}
			}
		?>
		<input type="submit" name="register" value="Register">
	</form>
</div>
</div>