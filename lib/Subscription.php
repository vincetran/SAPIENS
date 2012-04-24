<?php
require_once("../db/db.php");
/*
How to get a Subscription Object:
	Subscription::subscribe($user_id, $loc_id, $min_severity_web, $min_severity_email, $min_severity_text);
Interface:

*/
class Subscription{
	private $userIds, $locationId;

	public function __construct($location){
		$this->userIds = array();
		$this->locationId = $location->id;
		$db = connectDb();
		$sql = "SELECT user_id, min_severity_web, min_severity_email, min_severity_text FROM subscriptions WHERE loc_id=?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('i', $this->locationId);
		$stmt->execute();
		$stmt->bind_result($userId, $webLevel, $emailLevel, $txtLevel);
		while($stmt->fetch()){
			array_push($this->userIds, array($userId, $webLevel, $emailLevel, $txtLevel));
		}
		$db->close();
	}

	public function notify($num){
		
	}

	/*
		FUNCTION: add
		Params: user  -- user object
				min_severity_web -- the minimum severity an event must have before contacting via this method
				min_severity_email -- the minimum severity an event must have before contacting via this method
				min_severity_text -- the minimum severity an event must have before contacting via this method
		Return:  1 (on success)
				-1 (on invalid input values)
				-2 (on user is already subscribed)
	*/
	public function add($user, $webLevel, $emailLevel, $txtLevel){
		if($this->check($user)){
			return -2;
		}
		$db = connectDb();
		if(!$stmt = $db->prepare("INSERT INTO subscriptions(user_id, loc_id, min_severity_web, min_severity_email, min_severity_text) VALUES(?,?,?,?,?)")){
			$db->close();
			return -1;
		}
		if(!$stmt->bind_param('iiiii', $user->userId, $this->locationId, $webLevel, $emailLevel, $txtLevel)){
			$db->close();
			return -1;
		}
		if(!$stmt->execute()){
			$db->close();
			return -1;
		}
		array_push($this->userIds, array($user->userId, $webLevel, $emailLevel, $txtLevel));
		$db->close();
		return 1;
	}

	/*
		FUNCTION: check
		Params: user  -- user object
		Return: TRUE   (if found in array)
				FALSE  (if not found in array)
	*/
	public function check($user){
		for($i=0; $i< count($this->userIds); $i++){
			if(in_array($user->userId, $this->userIds[$i]))
			{
				return TRUE;
			}
		}
		return FALSE;
	}

	/*
		FUNCTION: remove
		Params: user  -- user object
		Return:  1 (on success)
				-1 (on invalid input values)
				-2 (on user is already subscribed)
	*/
	public function remove($user){
		for($i=0; $i< count($this->userIds); $i++){
			if($this->userIds[$i] == $user->userId)
			{
				array_splice($this->userIds, $i, 1);
				$db = connectDb();
				$sql = "DELETE FROM subscriptions WHERE user_id=? AND loc_id=?";
				$stmt = $db->prepare($sql);
				$stmt->bind_param('ii', $user->userId, $this->locationId);
				$stmt->execute();
				break;
			}
		}
	}	
}

?>