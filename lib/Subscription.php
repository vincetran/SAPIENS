<?php
require_once("../db/db.php");
/*
How to get a Subscription Object:
	Subscription::subscribe($user_id, $loc_id, $min_severity_web, $min_severity_email, $min_severity_text);
Interface:

*/
class Subscription{
	private $userIds, $locationId;

	private function __construct($location){

	}

	public function notify($num){

	}

	public function add($user, $webLevel, $emailLevel, $txtLevel){

	}

	public function check($user){
		
	}
	public function remove($user){

	}

	/*
	///////////////////////////////////////////////////////////////////////////////////////////
	BELOW THIS LINE IS WAYS TO CREATE THE SUBSCRIPTION OBJECT~~~~~~~~~~~~~~~~~~~~~~~
	///////////////////////////////////////////////////////////////////////////////////////////
	*/
	
	/*
		FUNCTION: subscribe
		Params: user_id  -- used to assign subscription to a user
				loc_id -- user to mark subscription for ENS events from location
				min_severity_web -- the minimum severity an event must have before contacting via this method
				min_severity_email -- the minimum severity an event must have before contacting via this method
				min_severity_text -- the minimum severity an event must have before contacting via this method
		Return: Subscription (onSuccess)
				-1 (on invalid user)
				-2 (on invalid location)
				-3 (on invalid severity)
	*/
	public static function subscribe($user_id, $loc_id, $min_severity_web, $min_severity_email, $min_severity_text){
		$db = connectDb();
		$sql = "SELECT user_login_name FROM user WHERE user_id=?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$stmt->close();
			$sql = "SELECT loc_name FROM locations WHERE loc_id=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('i', $loc_id);
			$stmt->execute();
			if($stmt->fetch())
			{
				$stmt->close();
				if(is_numeric($severity) && $severity >= 1 && $severity <= 3){
					$sql = "INSERT INTO subscriptions(user_id, loc_id, min_severity_web, min_severity_email, min_severity_text) VALUES(?,?,?)";
					$stmt = $db->prepare($sql);
					$stmt->bind_param('iiiii', $user_id, $loc_id, $min_severity_web, $min_severity_email, $min_severity_text);
					$stmt->execute();
					$db->close();
					return new Subscription($user_id, $loc_id, $min_severity_web, $min_severity_email, $min_severity_text);
				}
				else{
					return -3;
				}
			}
			else
			{
				$db->close();
				return -2;
			}
		}
		else{
			return -1;
		}
	}
	
}

?>