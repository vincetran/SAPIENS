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
        var location = new google.maps.LatLng(40.4412298,-79.95594);
        var mark = placeMarker(location);
        var fullText= '<div class="scrollbar-container"><div class="inner">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent viverra porta magna ut malesuada. Pellentesque sem nisi, volutpat nec dictum nec, aliquam in ipsum. Nullam ac libero ut justo pretium iaculis. Donec porta, mi quis scelerisque egestas, tortor risus sagittis enim, sit amet rutrum sapien orci quis nisi. Curabitur quis magna in neque tincidunt consectetur. Integer tempor fringilla magna et interdum. Vivamus quis erat at risus adipiscing feugiat nec non risus. Sed neque diam, dignissim et cursus volutpat, volutpat non nibh. Mauris eu nibh enim. Morbi eu tellus ligula, sed pharetra dui.Maecenas faucibus tempus laoreet. Sed cursus, ligula a sodales pharetra, arcu elit varius sem, ut tempus justo erat vitae nunc. Integer quis lacus ac nibh posuere pharetra. Morbi dapibus, arcu ut volutpat dignissim, turpis elit scelerisque sapien, sed bibendum neque mauris a odio. Praesent vel ipsum ac urna iaculis mollis. Quisque in sapien ipsum. Curabitur eget mattis risus. Praesent in purus justo. Nunc ac porta elit. Nulla ac augue non tortor cursus dignissim id sed odio.Donec tristique ipsum sed neque scelerisque quis aliquam lectus semper. Suspendisse et massa a diam aliquet laoreet eget at tellus. Fusce magna arcu, porttitor sit amet elementum eget, varius in magna. Suspendisse potenti. Proin vitae ipsum sed leo bibendum lacinia fermentum ut nibh. Quisque eget lacus massa, ac feugiat neque. Suspendisse potenti. Proin malesuada tristique ante, bibendum dictum metus interdum in. Mauris sed arcu non nibh facilisis porta. Quisque mi nibh, convallis vitae pharetra sit amet, venenatis et neque. Maecenas elementum rhoncus arcu at sodales.Proin ornare porta lorem, ac consectetur tellus venenatis auctor. Nulla auctor, augue non semper varius, enim nibh adipiscing enim, vitae ornare neque ante id velit. Sed nec ullamcorper enim. Nulla lorem est, rutrum id venenatis sed, viverra tincidunt magna. Proin at enim in ligula commodo dictum vel a nibh. Phasellus eget enim nisi, at accumsan erat. Donec sem tortor, ornare sed varius sed, tempus id libero. Nunc sit amet neque nisi, id vestibulum ligula. Fusce in varius nisl. Maecenas tristique, leo et tincidunt posuere, nisi leo fermentum ipsum, sed dignissim erat elit eu eros. Aenean quis nisi ut nulla posuere cursus. Aliquam porttitor porta enim ut egestas. Fusce ornare tempus consequat.Sed ut lacinia enim. Fusce a imperdiet mi. Sed eu lorem ac metus auctor interdum. Nulla ornare hendrerit auctor. Aliquam ultricies orci non purus cursus et consectetur sapien euismod. Aenean est mi, suscipit vitae mattis ullamcorper, feugiat ac purus. Sed libero massa, tempor ac tristique nec, euismod vitae ipsum. In vehicula pretium pharetra. Donec tincidunt interdum faucibus. Aliquam erat volutpat. Sed aliquam ornare tristique. Vestibulum at cursus sapien. Praesent eleifend euismod consectetur. Sed gravida orci in risus molestie iaculis eget eget purus.</div></div>';
        setUpInfoPane(fullText, mark);
    }
    
    
  $(document).ready(function(){
                initialize();
                data = {'lat':40.4412298, 'long':-79.95494};
                placeEvent(data)
                
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
