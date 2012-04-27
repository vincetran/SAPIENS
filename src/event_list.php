<?php
include("../lib/EventManager.php");
$user = User::resume();
$info = "";
if(!$user){
	header("Location: login.php");
}
else{
	$event = new EventManager($user);
	$list = 0;
	if(!$_GET || !$_GET['type'] || $_GET['type'] == 1){
		$list = $event->getFullList();
	}
	else{
		$list = $event->getList();
	}
	$info .="<div id='box'>";
	foreach($list as $item){
		$info .= "<div class='location'>";
		$info .= "<h2>" . $item["location"]["name"] . "</h2>";
		foreach($item["events"] as $event){
			$info .= "<div class='data". $event['severity']."'>" . $event['description'] . "</div>";
		}
		$info .= "</div>";
	}
	$info .= "</div>";
}
?>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAPIENS - Home</title>
<link rel="stylesheet" type="text/css" href="../public/css/sapiens.css?1232" />
<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Chau+Philomene+One' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
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
<div id="navbar2">
	<a href="event_list.php">All Events</a>
	<a href="event_list.php?type=2">Events Since Last Login</a>

</div>
<?php
	echo $info;
 ?>
</div>
</div>
</body>
</html>