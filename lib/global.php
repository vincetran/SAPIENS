<?php
require_once("../db/db.php");

$ROOT_LOCATION = "Pittsburgh";

function getCPSDropDown(){
	$db = connectDb();
	$sql = "SELECT cp_id, cp_name FROM cell_providers";
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($cp_id, $cp_name);
	while($stmt->fetch())
	{
		echo "\t<option value=\"".$cp_id."\">".$cp_name."</option>\n";
	}
}
/* 
	There are 3 different options
	Option 0: $input is null and is used for subscription addition
	Option 1: $input has the value that the dropdown should default to
	Option 2: $input is the max value. Used for Adding an event without a level of 4
*/
function severityDropDown($option, $input){
	if(!isset($input)){
		$input = 5;
	}
	if($option==0 || $option==2)
	{
		$array = array('Non Critical', 'Critical', 'Very Critical', 'Not Applicable');
		for($i=1; $i<$input; $i++){
			echo "\t<option value=\"".$i."\">".$i." - ".$array[$i-1]."</option>\n";
		}
	}
	elseif($option==1)
	{
		$array = array('Non Critical', 'Critical', 'Very Critical', 'Not Applicable');
		for($i=1; $i<5; $i++){
			if($i == $input){
				echo "\t<option value=\"".$i."\" selected=\"selected\">".$i." - ".$array[$i-1]."</option>\n";
			}
			else{
				echo "\t<option value=\"".$i."\">".$i." - ".$array[$i-1]."</option>\n";
			}
		}
	}
}
function checkInDropDown($location){
	echo "\t<option value=\"0\">- None Specified -</option>\n";
	if(!isset($location)){
		global $ROOT_LOCATION;
		$location = new Location(getLocationId($ROOT_LOCATION));
		echo "\t<option value=\"".$location->getId()."\">".$location."</option>\n";
		return;
	}
	$children = $location->getChildren();
	
	for($i=1; $i<sizeof($children)+1; $i++){
		echo "\t<option value=\"".getLocationId($children[$i-1])."\">".$children[$i-1]."</option>\n";
	}
}
function getLocationId($name){
	$db = connectDb();
	$stmt = $db->prepare("SELECT loc_id from locations where loc_name=?");
	$stmt->bind_param('s', $name);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();
	$db->close();
	return $id;
}
?>