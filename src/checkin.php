<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	require_once("../lib/Location.php");
	require_once("../lib/Subscription.php");
	$user = User::resume();
	if(!$user){
		header("Location: login.php");
	}
?>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAPIENS - Subscriptions</title>
<link rel="stylesheet" type="text/css" href="../public/css/sapiens.css?1232" />
<link rel="stylesheet" type="text/css" href="../public/css/jquery.ui.1.8.16.ie.css" />
<link rel="stylesheet" type="text/css" href="../public/css/jquery-ui-1.8.16.custom.css" />
<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Chau+Philomene+One' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(fade_out, 3500);

		function fade_out() {
			$(".error").slideUp();
			$(".success").slideUp();
		}

		$('#toggle').click(function() {
			$('#kony').toggle('slow');
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
		<h1>Check In</h1>
		<div id="toggle"><a>(hide/show)</a></div>
		<div id="kony">
			Kony 2012
		</div>
		<label for="loc">Location</label></br>
		<div class="loc">
			<select name="severity_web">
				<?php 
					//$location = new Location(getLocationId($ROOT_LOCATION));
					//echo $location;
					checkInDropDown(); 
				?>
			</select>
			</div>
	</div>
</div>
</div>
</body>
</html>