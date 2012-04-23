<?php
include("../lib/User.php");
if(!User::resume()){
	header("Location:login.php");
}
?>

<!DOCTYPE html>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

<html>
<head>
	<link rel="stylesheet" type="text/css" href="../public/css/sapiens.css" />
	<link href='http://fonts.googleapis.com/css?family=Chau+Philomene+One' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'>
   <link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>

    <title>Map..</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">
    var map;
    var iconWindows = []
    var MAX_ALERT = "http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_sleft|glyphish_zap|FF2823";
    var HIGH_ALERT = "http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_sleft|glyphish_gear|FFF82F";
    var MEDIUM_ALERT = "http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_sleft|glyphish_gear|6AFF1A";
    var LOW_ALERT = "http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_sleft|glyphish_gear|0069FF";
    function initialize() {
      var iconWindows = []
      var myLatlng = new google.maps.LatLng(40.4412298, -79.95494);
      var myOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    }
      
    function placeMarker(location) {
      var cafeIcon = new google.maps.MarkerImage(LOW_ALERT);
      var marker = new google.maps.Marker({
          position: location, 
          map: map,
          icon: cafeIcon
      });
      map.setCenter(location);
      return marker;
    }
    function setUpInfoPane(text, marker){
        var infowindow = new google.maps.InfoWindow({
            content: text
        });
        iconWindows.push(infowindow);
        google.maps.event.addListener(marker, 'click', function(event) {
            for(var i = 0; i < iconWindows.length; i++){
              iconWindows[i].close();
            }
            infowindow.open(map,marker);
        });   
    }
    
    function placeEvent(data){
        var location = new google.maps.LatLng(data.lat,data.long);
        var mark = placeMarker(location);
        var fullText= '<div class="scrollbar-container"><div class="inner">'+data.name+'</div></div>';
        setUpInfoPane(fullText, mark);
    }
    
    
  $(document).ready(function(){
                initialize();
                $.post("bitch.php", function(data){
                  var i = 0;
                  for(i = 0; i < data.length; i++){
                    placeEvent(data[i]);                 
                  }
                }, 'json')
                
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

    <div id="map_canvas" style="width:100%; height:400px; margin: auto; margin-top: 20px; margin-bottom: 20px; border: 1px solid #CBC9C9;"></div>            
  </div>                
</body>
</html>
