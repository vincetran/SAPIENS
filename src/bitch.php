<?php
include("../lib/Location.php");
$loc = new Location(1);
$arr = array();
recLookup($loc, $arr);
function recLookup($location, &$arr){
	array_push($arr, $location);
	foreach($location->getChildren() as $child){
		recLookup($child, $arr);
	}
	return $arr;
}
echo json_encode($arr);
?>