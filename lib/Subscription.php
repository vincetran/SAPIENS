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
		//Dynamic
		$sql = "SELECT user_id FROM users WHERE last_loc_id IS NOT NULL AND 
				last_loc_checkin_ts	> DATE_SUB(NOW(), INTERVAL 2 HOUR)";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$stmt->bind_result($userId);
		while($stmt->fetch()){
			array_push($temp1, $userId);
		}
		$stmt->close();
		foreach($temp1 as $userId){
			$sql = "SELECT user_id, min_severity_web, min_severity_email, min_severity_text FROM dynamic_subscriptions WHERE user_id=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('i', $userId);
			$stmt->execute();
			$stmt->bind_result($userId, $webLevel, $emailLevel, $txtLevel);
			while($stmt->fetch()){
				$temp2[$userId] = array($webLevel, $emailLevel, $txtLevel);
				//array_push($this->userIds, array($userId, $webLevel, $emailLevel, $txtLevel));
			}
		}
		$stmt->close();
		
		//Subs
		$sql = "SELECT user_id, min_severity_web, min_severity_email, min_severity_text FROM subscriptions WHERE loc_id=?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('i', $this->locationId);
		$stmt->execute();
		$stmt->bind_result($userId, $webLevel, $emailLevel, $txtLevel);
		while($stmt->fetch()){
			$temp3[$userId] = array($webLevel, $emailLevel, $txtLevel);
			//array_push($this->userIds, array($userId, $webLevel, $emailLevel, $txtLevel));
		}
		foreach(array_keys($temp2) as $key){
			if(array_key_exists($key, $temp3)){
				//get min
				if($temp2[$key][0] > $temp3[$key][0]){
					$webLevel = $temp3[$key][0];
				}
				else{
					$webLevel = $temp2[$key][0];
				}
				if($temp2[$key][1] > $temp3[$key][1]){
					$emailLevel = $temp3[$key][1];
				}
				else{
					$emailLevel = $temp2[$key][1];
				}
				if($temp2[$key][2] > $temp3[$key][2]){
					$txtLevel = $temp3[$key][2];
				}
				else{
					$txtLevel = $temp2[$key][2];
				}
				array_push($this->userIds, array($key, $webLevel,$emailLevel,$txtLevel));
			}
			else{
				array_push($this->userIds, array($key, $temp3[$key][0],$temp3[$key][1],$temp3[$key][2]));
			}
		}
				
		$db->close();
	}

	public function notify($eventId){
		for($i=0;$i< count($this->userIds); $i++) {
			$userId = $this->userIds[$i][0];
			$web = $this->userIds[$i][1];
			$email = $this->userIds[$i][2];
			$text = $this->userIds[$i][3];

			$db = connectDb();
			$sql = "SELECT event_severity, loc_description, event_description FROM events NATURAL JOIN locations WHERE event_id=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('i', $eventId);
			$stmt->execute();
			$stmt->bind_result($eventSeverity, $eventLocation, $eventDesc);
			$stmt->fetch();
			$stmt->close();

			$sql = "SELECT user_email, user_cell_email FROM users WHERE user_id=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('i', $userId);
			$stmt->execute();
			$stmt->bind_result($userEmail, $userCell);
			$stmt->fetch();
			$stmt->close();

			if($email != 4 && $email < $eventSeverity)
			{
				$subject = "SAPIENS Alert";
				$body = "Warning, \nThere has been an event at ".$eventLocation." for the following reason: ".$eventDesc;
				$body .= "\nPlease evacuate the building and alert others when it is safe to do so.";
				$headers = "From: admin@sapiens.com\r\n" .
				    "X-Mailer: php";
				mail($userEmail, $subject, $body, $headers);			
			}
			if($text != 4 && $text < $eventSeverity)
			{
				$subject = "SAPIENS Alert";
				$body = "Warning, \nThere has been an event at ".$eventLocation." for the following reason: ".$eventDesc;
				$body .= "\nPlease evacuate the building and alert others when it is safe to do so.";
				$headers = "From: admin@sapiens.com\r\n" .
				    "X-Mailer: php";
				mail($userCell, $subject, $body, $headers);
			}
		}
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
		Return:  TRUE (on success)
				FALSE (on failure)
	*/
	public function remove($user){
		for($i=0; $i< count($this->userIds); $i++){
			if($this->userIds[$i][0] == $user->userId)
			{
				array_splice($this->userIds, $i, 1);
				$db = connectDb();
				$sql = "DELETE FROM subscriptions WHERE user_id=? AND loc_id=?";
				$stmt = $db->prepare($sql);
				$stmt->bind_param('ii', $user->userId, $this->locationId);
				$stmt->execute();
				return TRUE;
			}
		}
		return FALSE;
	}	
}

?>