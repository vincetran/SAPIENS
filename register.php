<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Assignment 3</title>
<link rel="stylesheet" type="text/css" href="public/css/sapiens.css" />
<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'></head>
<body>
<div id ="title">SAPI<span style="color:#4CE11C;">ENS</span></div>
<div id="container">
<div class="register">
	<form action="newuser.php" method="post">
		<label for="username">Username*</label><input type="text" name="username"></br>
		<label for="password">Password*</label><input type="password" name="password"></br>
		<label for="email">Email*</label><input type="text" name="email"></br>
		<label for="fname">First Name</label><input type="text" name="fName"></br>
		<label for="lname">Last Name</label><input type="text" name="lName"></br>
		<label for="lname">Cell Phone Provider</label><select >
		  <option value="volvo">Volvo</option>
		  <option value="saab">Saab</option>
		  <option value="mercedes">Mercedes</option>
		  <option value="audi">Audi</option>
		</select></br>
		<input type="submit" name="register" value="Register">
	</form>
</div>
</div>