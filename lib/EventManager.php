<?php
require_once("../db/db.php");
/*
How to get an Event Object:
	Event::createEvent($loc_id, $severity, $description);
	
Interface:
	$event.getSubscriptions(Subscription obj);
	$event.getDynamicSubscriptions(User obj);
*/
class EventManager{
	public function __construct($user){
		
	}

	public function getFullList(){

	}

	public function getList(){
		
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
				description -- 
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
					return 1;
					//return new Event($loc_id, $severity, $description);
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