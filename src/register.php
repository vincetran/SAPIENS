<?php
	include("../lib/global.php");
?>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAPIENS</title>
<link rel="stylesheet" type="text/css" href="../public/css/sapiens.css" />
<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'></head>
<body>
<div id ="title">SAPI<span style="color:#4CE11C;">ENS</span></div>
<div id="container">
<div class="register">
	<form action="register.php" method="post">
		<label for="username">Username*</label><input type="text" name="username"></br>
		<label for="password">Password*</label><input type="password" name="password"></br>
		<label for="email">Email*</label><input type="text" name="email"></br>
		<label for="fname">First Name</label><input type="text" name="fName"></br>
		<label for="lname">Last Name</label><input type="text" name="lName"></br>
		<label for="pnumber">Phone Number</label><input type="text" name="pNumber"></br>
		<label for="provider">Cell Phone Provider</label>
		<div><select name="cps" >
			<?php getCPSDropDown(); ?>
		</select></div></br>
		<?php
			if(isset($_POST['register']))
			{
				if(register($_POST['username'], $_POST['password'], $_POST['fName'], $_POST['lName'], $_POST['email'], $_POST['pNumber'], $_POST['cps']) == -1)
				{
					echo "<p class=\"error\">Username already exists. Please choose a new username.</p>";
				}
			}
		?>
		<input type="submit" name="register" value="Register">
	</form>
</div>
</div>
</body>
</html>