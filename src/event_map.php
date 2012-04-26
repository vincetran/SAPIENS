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
	<link rel="stylesheet" type="text/css" href="../public/css/sapiens.css?432" />
	<link href='http://fonts.googleapis.com/css?family=Chau+Philomene+One' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'>
   <link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'> 
<link href='http://fonts.googleapis.com/css?family=Averia+Libre' rel='stylesheet' type='text/css'>
    <title>Map..</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">
    var map;
    var iconWindows = []
    var colors = ["http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin|glyphish_gear|555555",
    "http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin|glyphish_location|6AFF1A",
     "http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin|glyphish_bow-and-arrow|FFF82F",
     "http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin|glyphish_zap|FF2823" ];

    function initialize() {
      var iconWindows = []
      var markerMap={}
      var myLatlng = new google.maps.LatLng(40.4412298, -79.95494);
      var myOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    }
      
    function placeMarker(location,colorId) {
      var cafeIcon = new google.maps.MarkerImage(colors[colorId]);
      var marker = new google.maps.Marker({
          position: location, 
          map: map,
          icon: cafeIcon,
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
    
    function placeEvent(data, fullText, color){
        var location = new google.maps.LatLng(data.location.lat,data.location.long);
        var mark = placeMarker(location,color);
        setUpInfoPane(fullText, mark);
    }
    function setText(elem, currentText){
      var text = '<div class="scrollbarcontainer"><div class="type">'+elem.location.name+'</div>';
      text = currentText + text;
      for(var i = 0; i < elem.events.length; i++){
        text += ('<div class="data'+ elem.events[i].severity+'">'+elem.events[i].description+'</div>');
      }
      text+= '</div>';
      return text;
    }
    function getIcon(elem){
      var currentValue = 0;
      for(var i = 0; i < elem.events.length; i++){
        if(elem.events[i].severity > currentValue){
          currentValue = elem.events[i].severity;
        }
      }
      return parseInt(currentValue);
    }
    function getMarkers(text){
      initialize();
      $.post("test.php", {"type": text}, function(data){
        for(i = 0; i < data.length; i++){
          if(map[''+data[i].location.lat+''+data[i].location.long]){
            mapCell = map[''+data[i].location.lat+''+data[i].location.long].text
            map[''+data[i].location.lat+''+data[i].location.long].text = setText(data[i], mapCell);
            if(getIcon(data[i]) > map[''+data[i].location.lat+''+data[i].location.long].icon){
               map[''+data[i].location.lat+''+data[i].location.long].icon = getIcon(data[i])
            }
          }
          else{
            map[''+data[i].location.lat+''+data[i].location.long] =  {text:setText(data[i], ''), icon:getIcon(data[i])};
          }
          placeEvent(data[i], map[''+data[i].location.lat+''+data[i].location.long].text, map[''+data[i].location.lat+''+data[i].location.long].icon);                 
        }
      }, 'json')
    }
  $(document).ready(function(){
                initialize();
                getMarkers(2);
                $("#time").click(function(){
                  getMarkers(1);
                });
                $("#all").click(function(){
                  getMarkers(2);
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

    <div id="map_canvas" style="width:100%; height:400px; margin: auto; margin-top: 20px; margin-bottom: 20px; border: 1px solid #CBC9C9;"></div>            
    <button id="time" value="1">Get Events from Last Login</button>
    <button id="all" value="2">Get All Events</button>
  </div>                
</body>
</html>
