<?php
require_once("../db/db.php");
/*
How to get an Event Object:
	Event::createEvent($loc_id, $severity, $description);
	
Interface:
	$event.getSubscriptions(Subscription obj);
	$event.getDynamicSubscriptions(User obj);
*/
class Event{
	public $loc_id, $severity, $description;
	private function __construct($loc_id, $severity, $description){
		$this->loc_id = $loc_id;
		$this->severity = $severity;
		$this->description = $description;
	}
	public function getSubscriptions($subscription){
		//TODO: Use loc_id to match subscriptions for users with a subscription for that location
	}
	public function getDynamicSubscriptions($user){
		//TODO: Use last_loc_id of user and last_loc_checkin_ts of user to dynamically match relevant subscriptions
	}
	
	/*
	///////////////////////////////////////////////////////////////////////////////////////////
	BELOW THIS LINE IS WAYS TO CREATE THE EVENT OBJECT~~~~~~~~~~~~~~~~~~~~~~~
	///////////////////////////////////////////////////////////////////////////////////////////
	*/
	
	/*
		FUNCTION: createEvent
		Params: loc_id  -- used to check for relevant subscriptions and to make dynamic subscriptions
				severity -- defcon X
				description -- de·scrip·tion/di'skripSH?n/ Noun: A spoken or written representation or account of a person, object, or event: "people who had seen him were able to give a description".
		Return: Event (onSuccess)
				-1 (on invalid location)
				-2 (on invalid severity)
				-3 (on improperly formatted description)
	*/
	public static function createEvent($loc_id, $severity, $description){
		$db = connectDb();
		$sql = "SELECT loc_name FROM locations WHERE loc_id=?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('i', $loc_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$stmt->close();
			if(is_numeric($severity) && $severity >= 1 && $severity <= 3){
				if(is_string($description) && (strlen($description) < 121)){
					$sql = "INSERT INTO events(loc_id, event_severity, event_description) VALUES(?,?,?)";
					$stmt = $db->prepare($sql);
					$stmt->bind_param('iis', $loc_id, $severity, $description);
					$stmt->execute();
					$db->close();
					return new Event($loc_id, $severity, $description);
				}
				else{
					return -3;
				}
			}
			else{
				return -2;
			}
		}
		else
		{
			$db->close();
			return -1;
		}
	}
	
}

?>