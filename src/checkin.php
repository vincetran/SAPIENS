<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	require_once("../lib/Location.php");
	require_once("../lib/Subscription.php");
	$user = User::resume();
	if(!$user){
		header("Location: login.php");
	}
	if(isset($_POST['depth']))
	{
		$depth = $_POST['depth'];
		if(isset($_POST["loc_$depth"])){
			$user = User::resume();
			$location = new Location($_POST["loc_$depth"]);
			$checkinResult = $user->checkin($location);
		}
		else{
			echo "Shits broken - QQ";
		}
	}
?>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAPIENS - Check In</title>
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
	
	var id = -1;
	var counter = 0;

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
	function changeFunc(){
		
		$('#depth').val(counter);
		counter++;
		//var e = document.getElementById("location");
		//var loc_txt = e.options[e.selectedIndex].text;
		//var loc_id = e.options[e.selectedIndex].value;
		
		if(id == -1){
			var val = $('#location0').val();
			var loc_id = parseInt(val);
		
			//alert(loc_id);
			id = loc_id;
		}
		else{
			var val = $('#location'+id).val();
			var loc_id = parseInt(val);
		
			//alert(loc_id);
			id = loc_id;
		}
		
		var results = $('#history');
		var item = $(document.createElement('div'));
		item.attr('class','result');
		
		$.ajax({
				type: "POST",
				data: {
					number: loc_id,
					parent: id,
					count: counter
				},
				url: "checkinScript.php"
			}).done(function(response) {
				item.html(response);
				item.show("fast");
				results.append(item);
		});
	}
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
			if(isset($checkinResult) && $checkinResult == 1){
				echo "</br><div class=\"success\">Successfully checked in!</div>";}
			elseif(isset($checkinResult) && $checkinResult == -1){
				echo "</br><div class=\"error\">Failed to check in</div>";}
		?>
		<h1>Check In</h1>
		<form action="checkin.php" method="post">
		<div id="toggle"><a>(hide/show)</a></div>
		<div id="kony">
			Kony 2012
		</div>
		<label for="loc">Location</label></br>
		<div class="loc">
			<select name="loc_0" id="location0" onchange="changeFunc();">
				<?php 
					//$location = new Location(getLocationId($ROOT_LOCATION));
					//echo $location;
					checkInDropDown(); 
				?>
			</select>
		</div>
		<div id="history">
			<span id="result" >	</span>
		</div>
		<input name="depth" id="depth" type="hidden" value="0">
		</br></br><input type="submit" id="checkz" name="checkin" value="Check in"></br></br></br>
		</form>
	</div>
</div>
</div>
</body>
</html>