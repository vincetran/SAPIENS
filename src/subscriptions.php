<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	if(!User::resume()){
		header("Location: login.php");
	}
?>

<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAPIENS - Subscriptions</title>
<link rel="stylesheet" type="text/css" href="../public/css/sapiens.css?1232" />
<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Chau+Philomene+One' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script>
	$(function() {
		var availableTags = [
			"ActionScript",
			"AppleScript",
			"Asp",
			"BASIC",
			"C",
			"C++",
			"Clojure",
			"COBOL",
			"ColdFusion",
			"Erlang",
			"Fortran",
			"Groovy",
			"Haskell",
			"Java",
			"JavaScript",
			"Lisp",
			"Perl",
			"PHP",
			"Python",
			"Ruby",
			"Scala",
			"Scheme"
		];
		$( "#tags" ).autocomplete({
			source: availableTags
		});
	});
</script>
</head>

<body>
<div id ="title">SAPI<span style="color:#4CE11C;">ENS</span></div>

<div id="container">
<div id="navbar">
	<a href="event_list.php">Event List</a>
	<a href="event_map.php">Event Map</a>
	<a href="subscriptions.php">Subscriptions</a>
	<a href="checkin.php">Check In</a>
	<a href="new_event.php">Submit a New Event</a>
	<a href="logout.php">Logout</a>
</div>

	<div class="sub">
		<h1>Current Subscriptions</h1>
		<h1>Add a Subscription</h1>
		<form action="subscriptions.php" method="post">
			<div class="ui-widget">
			<label for="loc">Location</label></br>
			<input type="text" name="loc" size="30" id="tags"></br></br>
			</div>

			<label for="severity">Severity Levels</label></br></br>

			<div class="severe">
			<label for="severity_web">Web</label></br>
			<select name="severity_web">
				<?php severityDropDown(); ?>
			</select>
			</div>

			<div class="severe">
			<label for="severity_email">Email</label></br>
			<select name="severity_email">
				<?php severityDropDown(); ?>
			</select>
			</div>

			<div class="severe">
			<label for="severity_txt">Text</label></br>
			<select name="severity_web">
				<?php severityDropDown(); ?>
			</select>
			</div>
			

			</br></br><input type="submit" name="subscribe" value="Subscribe to Location"></br>
		</form>
	</div>

</div>
</div>
</body>
</html>