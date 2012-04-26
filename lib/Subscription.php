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

		$dynamicIds = array();
		$dynSubs = array();
		$staticSubs = array();

		$db = connectDb();
		$sql = "SELECT user_id FROM users WHERE last_loc_id=? AND 
				last_loc_checkin_ts	> DATE_SUB(NOW(), INTERVAL 2 HOUR)";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('i', $this->locationId);
		$stmt->execute();
		$stmt->bind_result($userId);
		while($stmt->fetch()){
			array_push($dynamicIds, $userId);
			//echo "Push to Dynamic: ".$userId;
		}
		$stmt->close();

		foreach($dynamicIds as $userIdFound){
			//echo "in foreach</br>";
			$sql = "SELECT user_id, min_severity_web, min_severity_email, min_severity_text 
			FROM dynamic_subscriptions WHERE user_id=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('i', $userIdFound);
			$stmt->execute();
			$stmt->bind_result($userIdFound, $webLevel, $emailLevel, $txtLevel);
			while($stmt->fetch()){
				$dynSubs[$userIdFound] = array($webLevel, $emailLevel, $txtLevel);
				//echo "dynamic ".$userIdFound;
			}
			$stmt->close();
		}
		
		//Subs
		$sql = "SELECT user_id, min_severity_web, min_severity_email, min_severity_text 
			FROM subscriptions WHERE loc_id=?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('i', $this->locationId);
		$stmt->execute();
		$stmt->bind_result($userId2, $webLevel, $emailLevel, $txtLevel);
		while($stmt->fetch()){
			$staticSubs[$userId2] = array($webLevel, $emailLevel, $txtLevel);
			//echo "<br/>Static UID = $userId2 WL = $webLevel EL = $emailLevel TL = $txtLevel<br/>";
		}

		if(count($dynSubs) == 0)
		{
			foreach(array_keys($staticSubs) as $key){
				array_push($this->userIds, array($key, $staticSubs[$key][0],$staticSubs[$key][1],$staticSubs[$key][2]));
			}
		}
		else{
			foreach(array_keys($dynSubs) as $key){
				if(array_key_exists($key, $staticSubs)){
					//get min
					if($dynSubs[$key][0] > $staticSubs[$key][0]){
						$webLevel = $staticSubs[$key][0];
					}
					else{
						$webLevel = $dynSubs[$key][0];
					}
					if($dynSubs[$key][1] > $staticSubs[$key][1]){
						$emailLevel = $staticSubs[$key][1];
					}
					else{
						$emailLevel = $dynSubs[$key][1];
					}
					if($dynSubs[$key][2] > $staticSubs[$key][2]){
						$txtLevel = $staticSubs[$key][2];
					}
					else{
						$txtLevel = $dynSubs[$key][2];
					}
					array_push($this->userIds, array($key, $webLevel,$emailLevel,$txtLevel));
					unset($staticSubs[$key]);
				}
				else{
					//echo "<br/>dynamic subs = sizeof($dynSubs[$key]) where key is $key</br>";
					array_push($this->userIds, array($key, $dynSubs[$key][0],$dynSubs[$key][1],$dynSubs[$key][2]));
				}
			}
			foreach(array_keys($staticSubs) as $key){
				//echo "In foreach Static, Key: ".$key;
				array_push($this->userIds, array($key, $staticSubs[$key][0],$staticSubs[$key][1],$staticSubs[$key][2]));
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

			//echo "<br/><br/>Notify: UID = $userId web = $web email = $email text = $text<br/>";

			$db = connectDb();
			$sql = "SELECT event_severity, loc_description, event_description FROM events NATURAL JOIN locations WHERE 	event_id=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('i', $eventId);
			$stmt->execute();
			$stmt->bind_result($eventSeverity, $eventLocation, $eventDesc);
			$stmt->fetch();
			$stmt->close();

			//echo "<br/>After first Db Connect: ES = $eventSeverity EL = $eventLocation ED = $eventDesc<br/>";

			$sql = "SELECT user_email, user_cell_email FROM users WHERE user_id=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('i', $userId);
			$stmt->execute();
			$stmt->bind_result($userEmail, $userCell);
			$stmt->fetch();
			$stmt->close();

			//echo "<br/>After second DB connect UE = $userEmail UC = $userCell<br/>";

			//echo "If (($email != 4) && ($email <= $eventSeverity))";
			if(($email != 4) && ($email <= $eventSeverity))
			{
				//echo "Emailing user...";
				$subject = "SAPIENS Alert";
				$body = "Warning, \nThere has been an event at ".$eventLocation." for the following reason: ".$eventDesc;
				$body .= "\nPlease evacuate the building and alert others when it is safe to do so.";
				$headers = "From: admin@sapiens.com\r\n" .
				    "X-Mailer: php";
				mail($userEmail, $subject, $body, $headers);			
			}
			if(($text != 4) && ($text <= $eventSeverity))
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
		//echo "Current user".$user->userId;
		//echo "</br>Number of users: ".count($this->userIds);
		for($i=0; $i< count($this->userIds); $i++){
			//echo "ID: ".$this->userIds[$i][0];
			if(($user->userId == $this->userIds[$i][0]))
			{
				//echo "Return TRUE";
				return TRUE;
			}
		}
		//echo "Return FALSE";
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
				//echo "In if";
				array_splice($this->userIds, $i, 1);
				$db = connectDb();
				$sql = "DELETE FROM subscriptions WHERE user_id=? AND loc_id=?";
				$stmt = $db->prepare($sql);
				$stmt->bind_param('ii', $user->userId, $this->locationId);
				$stmt->execute();
				return TRUE;
			}
		}
		//echo "Failed";
		return FALSE;
	}	
}

?>