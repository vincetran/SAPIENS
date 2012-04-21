<?php 
require_once("../db/db.php");
class Location{
	private $parentId, $childrenId;
	public $id, $lat, $long, $name, $description;
	public function __construct($id){
		$db = connectDb();
		$stmt = $db->prepare("SELECT * from locations where loc_id=?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($this->id, $this->name, $this->description, $this->lat, $this->long, $this->parentId);
		$stmt->fetch();
		$stmt->close();
		########################################

		$stmt = $db->prepare("SELECT loc_id from locations where parent_loc_id=?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($child);
		$this->childrenId = array();
		while($stmt->fetch()){
			$this->childrenId[] = $child;
		}
		$stmt->bind_param('i', $id);
		//assert((!stmt->fetch()));
		$db->close();
	}
	public function getParent(){
		if($this->parentId){
			return new Location($parentId);
		}
		else{
			return null;
		}
	}
	public function getChildren(){
		$children = array();
		foreach($this->childrenId as $child){
			$locChild = new Location($child);
			$children[] = $locChild;
		}
		return $children;
	}
	public function __tostring(){
		return "$this->name";
	}
}
$loc = new Location(1);
recLookup($loc);
//print_r($loc->getChildren());
function recLookup($location){
	echo "$location <br>";
	foreach($location->getChildren() as $child){
		recLookup($child);
	}
}
?>