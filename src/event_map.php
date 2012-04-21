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
    <title>Map..</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">
    var map;
    function initialize() {
      var myLatlng = new google.maps.LatLng(40.4412298, -79.95494);
      var myOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    }
      
    function placeMarker(location) {
      var marker = new google.maps.Marker({
          position: location, 
          map: map
      });
      map.setCenter(location);
      return marker;
    }
    function setUpInfoPane(text, marker){
        var infowindow = new google.maps.InfoWindow({
            content: text
        });
        
        google.maps.event.addListener(marker, 'click', function(event) {
            infowindow.open(map,marker);
        });   
    }
    function placeEvent(data){
  
        var location = new google.maps.LatLng(data['lat'],data['long']);
        var mark = placeMarker(location);
        var fullText= '<h2>' + data['name'] + '</h2>' +  '<h4>How has the Long War effected you?</h4><p class="innerbubble">' + data['question'] + '</p>' +
        '<h4>Why did you mark this place?</h4><p class="innerbubble">'+ data['sent'] + '</p>';
        setUpInfoPane(fullText, mark);
    }
    
    
  /*  $(document).ready(function(){
        $.post('allMarkers.php', {}, function(data){
            if(data){
                var i;
                for(i = 0; i < data.length; i++){
                    placeEvent(data[i]);
                }
            }
        }, 'json');
    });*/
    </script>
</head>

<body onload="initialize()">

  <div id ="title">SAPI<span style="color:#4CE11C;">ENS</span></div>
  <div id="container"> 
    <div id="map_canvas" style="width:100%; height:400px; margin: auto; margin-top: 20px; margin-bottom: 20px; border: 1px solid #CBC9C9;"></div>            
  </div>                
</body>
</html>
