<?php
require_once("../db/db.php");

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
function severityDropDown(){
	for($i=1; $i<5; $i++){
		echo "\t<option value=\"".$i."\">".$i."</option>\n";
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