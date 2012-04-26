<?php
	require_once("../lib/global.php");
	require_once("../lib/User.php");
	require_once("../lib/Location.php");
	require_once("../lib/Subscription.php");	

	$location = new Location($_POST["number"]);
	if($location->hasChildren()){
?>
	<select name="loc_<?php echo $_POST["count"]; ?>" id="location<?php echo $_POST["parent"]; ?>" onchange="changeFunc();">
		<?php 
			checkInDropDown($location); 
		?>
	</select>
<?php } ?>