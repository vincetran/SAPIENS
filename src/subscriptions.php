<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	require_once("../lib/Location.php");
	require_once("../lib/Subscription.php");
	$user = User::resume();
	if(!$user){
		header("Location: login.php");
	}
	if(isset($_POST['loc']))
	{
		$location = new Location(getLocationId($_POST['loc']));
		$subscription = new Subscription($location);
		$user = User::resume();
		$subscribeResult = $subscription->add($user, $_POST['severity_web'], $_POST['severity_email'], $_POST['severity_txt']);
	}
	if(isset($_POST['unsub']))
	{
		$location = new Location($_POST['loc_id']);
		$subscription = new Subscription($location);
		$user = User::resume();
		if($subscription->check($user)){
			$unsubResult = $subscription->remove($user);
		}
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
<script>
	$(function() {
		var locations = [];
		$.post("../lib/getLocations.php", function(data){
			for(var i=0; i<data.length; i++){
				locations.push(data[i].name);
			}
		$( "#tags" ).autocomplete({
			source: locations
		});
		}, "json")
		$("#subz").click(function(event){
			var tag = $("#tags").val();
			var isLegit = false;
			for(var i = 0; i < locations.length; i++){
				if(locations[i] == tag){
					isLegit = true;
				}
			}
			if(!isLegit){
				alert("The location you entered is not part of our database.\n"+
					"Please try again.");
				event.preventDefault();
			}
		});

	});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(fade_out, 3500);

		function fade_out() {
			$(".error").slideUp();
			$(".success").slideUp();
		}

		$('#toggle').click(function() {
			$('#currsub').toggle('slow');
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
		<?php 
			if(isset($subscribeResult) && $subscribeResult == 1){
				echo "</br><div class=\"success\">Subscription Addition Succeeded!</div>";}
			elseif(isset($subscribeResult) && $subscribeResult == -1){
				echo "</br><div class=\"error\">Subscribe failed for some reason o_O</div>";}
			elseif(isset($subscribeResult) && $subscribeResult == -2){
				echo "</br><div class=\"error\">You're already subscribed to that location</div>";}
			elseif(isset($unsubResult) && $unsubResult){
				echo "</br><div class=\"success\">Successfully unsubscribed</div>";}
				elseif(isset($unsubResult) && !$unsubResult){
				echo "</br><div class=\"error\">Failed trying to unsubscribe</div>";}
		?>
		<h1>Current Subscriptions</h1>
		<div id="toggle"><a>(hide/show)</a></div>
		<div id="currsub">
			<table >
				<tr>
					<td><b>Location Name</b></td>
					<td><b>Location Description</b></td>
					<td><b>Minimum Web Severity</b></td>
					<td><b>Minimum Email Severity</b></td>
					<td><b>Minimum Text Severity</b></td>
				</tr>
			<?php $user->getSubs(); ?>
			</table>
		</div>
		<h1>Add a Subscription</h1>
		<form action="subscriptions.php" method="post">
			<label for="loc">Location</label></br>
			<input type="text" name="loc" size="30" id="tags"></br></br>

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
			<select name="severity_txt">
				<?php severityDropDown(); ?>
			</select>
			</div>
			
			</br></br><input type="submit" id="subz" name="subscribe" value="Subscribe to Location"></br></br></br>
		</form>
	</div>

</div>
</div>
</body>
</html>