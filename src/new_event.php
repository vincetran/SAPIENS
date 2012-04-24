<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	require_once("../lib/Location.php");
	require_once("../lib/Subscription.php");
	require_once("../lib/EventManager.php");
	if(!User::resume()){
		header("Location: login.php");
	}
	if($_POST && $_POST['loc'] && $_POST['desc'])
	{
		$location = new Location(getLocationId($_POST['loc']));
		
		$user = User::resume();
		$sub = new Subscription($location);
		$severity = $_POST['severity'];
		$desc = $_POST['desc'];
		if($sub->check($user)){
			$db = connectDb();
			$stmt = $db->prepare("INSERT INTO events(loc_id, event_severity, event_description) VALUES(?,?,?)");
			$stmt->bind_param('sss', $location->id, $severity, $desc);
			$stmt->execute();
			$stmt->close();
			$alertResult = 1;
		}else{
			$alertResult = 0;
		}
		
		
		
		//echo "Alert from User: $user->username at location: $location->name severity: $severity Description: $desc";	
	}
?>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAPIENS - Add a New Event</title>
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
			if(isset($alertResult) && $alertResult == 1){
				echo "</br><div class=\"success\">Successfully Added a New Event!</div>";}
			if(isset($alertResult) && $alertResult == 0){
				echo "</br><div class=\"success\">You must subscribe to this location in order to make an event for it!</div>";}
		?>
		<h1>Add a New Event</h1>
		<form action="new_event.php" method="post">
			<label for="loc">Location</label></br>
			<input type="text" name="loc" size="30" id="tags"></br></br>

			<div class="severe">
			<label for="severity">Severity</label></br>
			<select name="severity">
				<?php severityDropDown(); ?>
			</select>
			</div>
			
			<br/></br><label for="desc">Event Description</label></br>
			<textarea name="desc" maxlength="256" id="desc" rows="5" cols="50"></textarea></br>
			
			</br><input type="submit" id="alert" name="alert" value="Add a New Event"></br></br></br>
		</form>
	</div>

</div>
</div>
</body>
</html>