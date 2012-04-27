<?php
require_once("../db/db.php");
require_once("../lib/Location.php");
require_once("../lib/User.php");
//[{'location':{'name':SHIT, 'lat': 132, 'long':23}, 'events':[{severity:1, description:"fuck", timestamp: 134325}]}]
/*
How to get an Event Object:
	Event::createEvent($loc_id, $severity, $description)
	
Interface:
	$event.getSubscriptions(Subscription obj);
	$event.getDynamicSubscriptions(User obj);
*/
class EventManager{
	private $locations, $user;
	public function __construct($user){
		$this->user = $user;
		$db = connectDb();
		$sql = "SELECT loc_id from subscriptions where user_id = ?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param("i", $user->userId);
		$stmt->execute();
		$stmt->bind_result($locationz);
		while($stmt->fetch()){
			$loca = new Location($locationz);
			$this->locations[] = $loca;
		}
		$db->close();
	}
	private function getEventsAtLocation($user, $location, $time = -1, $isDynamic = false){
		$eventsArray = array();
		$stmt = 0;
		if($isDynamic){
			$sql = "SELECT  events.event_description, events.event_ts, events.event_severity from events join (SELECT last_loc_id as loc_id, min_severity_web from users natural join dynamic_subscriptions where user_id = ?) as loc on loc.loc_id = events.loc_id join (select * from subscriptions where user_id = ?) as loc2 on loc2.loc_id = loc.loc_id where (events.event_severity>= loc.min_severity_web or events.event_severity >= loc2.min_severity_web) and events.event_severity <4";
			$db = connectDb();
			if($time != -1){
				$sql .= " and events.event_ts >= ?";
				$stmt = $db->prepare($sql);
				$stmt->bind_param('iis', $user->userId, $user->userId, $time);
			}
			else{
				$stmt = $db->prepare($sql);
				$stmt->bind_param('ii', $user->userId, $user->userId);
			}			
		}
		else{
			$sql = "SELECT events.event_description, events.event_ts, events.event_severity from events join (SELECT loc_id, min_severity_web from subscriptions where user_id = ? and loc_id = ?) as loc on loc.loc_id = events.loc_id where events.event_severity >= loc.min_severity_web  and loc.min_severity_web < 4";		
		
			$db = connectDb();
			if($time != -1){
				$sql .= " and events.event_ts >= ?";
				$stmt = $db->prepare($sql);
				$stmt->bind_param('iis', $user->userId, $location->id, $time);
			}
			else{
				$stmt = $db->prepare($sql);
				$stmt->bind_param('ii', $user->userId, $location->id);
			}
		}
		$stmt->execute();
		$stmt->bind_result($description, $timestamp, $severity);
		while($stmt->fetch()){
			$eventsArray[] = array("description"=>$description, "severity"=>$severity, "timestamp"=>$timestamp);
		}
		$db->close();
		return $eventsArray;
	}
	private function isLocationInList($id, $locList){
		foreach($locList as $loc){
			if($loc->id == $id){
				return true;
			}
		}
		return false;
	}
	private function getDynamicSubEvents($userId){
		$eventsArray = array();
		$sql = "SELECT events.event_description, events.event_ts, events.event_severity from events join (SELECT last_loc_id as loc_id, min_severity_web, last_login_ts from users natural join dynamic_subscriptions where user_id = ?) as loc on loc.loc_id = events.loc_id where events.event_severity >= loc.min_severity_web  and loc.min_severity_web < 4 and events.event_ts >= loc.last_login_ts";
		$db = connectDb();
		$stmt = $db->prepare($sql);
		$stmt->bind_param('i', $userId);
		$stmt->execute();
		$stmt->bind_result($description, $timestamp, $severity);
		while($stmt->fetch()){
			$eventsArray[] = array("description"=>$description, "severity"=>$severity, "timestamp"=>$timestamp);
		}
		$db->close();
		return $eventsArray;

	}
	public function getFullList(){
		$objs = array();
		foreach($this->locations as $loca){
			if($this->user->lastLocation && $this->user->lastLocation != $loca->id){
				$locationObj = array("name"=>$loca->name, "lat"=>$loca->lat, "long"=>$loca->long);
				$eventObj = $this->getEventsAtLocation($this->user, $loca);
				$bigObj = array("location"=>$locationObj, "events"=>$eventObj);
				$objs[] = $bigObj;
			}
			else{
				$locationObj = array("name"=>$loca->name, "lat"=>$loca->lat, "long"=>$loca->long);
				$eventObj = $this->getEventsAtLocation($this->user, $loca, -1, true);
				$bigObj = array("location"=>$locationObj, "events"=>$eventObj);
				$objs[] = $bigObj;
			}

		}
		if($this->user->lastLocation && !$this->isLocationInList($this->user->lastLocation, $this->locations)){
			$eventObj = $this->getDynamicSubEvents($this->user->userId);
			$location = new Location($this->user->lastLocation);
			$locationObj = array("name"=>$location->name, "lat"=>$location->lat, "long"=>$location->long);
			$bigObj = array("location"=>$locationObj, "events"=>$eventObj);
			$objs[] = $bigObj;
		}
		return $objs;
	}

	public function getList(){
		$objs = array();
		$time = -1;
		if($this->user->lastLogin){
			$time = $this->user->lastLogin;
		}
		foreach($this->locations as $loca){
			$locationObj = array("name"=>$loca->name, "lat"=>$loca->lat, "long"=>$loca->long);
			$eventObj = 0;
			if($this->user->lastLocation && $this->user->lastLocation != $loca->id){
				$eventObj = $this->getEventsAtLocation($this->user, $loca, $time);				
			}
			else{
				$eventObj = $this->getEventsAtLocation($this->user, $loca, $time, true);

			}

			$bigObj = array("location"=>$locationObj, "events"=>$eventObj);
			$objs[] = $bigObj;
		}
		if($this->user->lastLocation && !$this->isLocationInList($this->user->lastLocation, $this->locations)){
			$eventObj = $this->getDynamicSubEvents($this->user->userId);
			$location = new Location($this->user->lastLocation);
			$locationObj = array("name"=>$location->name, "lat"=>$location->lat, "long"=>$location->long);
			$bigObj = array("location"=>$locationObj, "events"=>$eventObj);
			$objs[] = $bigObj;
		}
		return $objs;
	}
	
	/*
	SELECT events.event_description, events.event_ts, events.event_severity from events join (SELECT loc_id, min_severity_web from subscriptions where user_id = 4 and loc_id = 5) as loc on loc.loc_id = events.loc_id where  events.event_severity >= loc.min_severity_web  and loc.min_severity_web < 4
	////////
	///////////////////////////////////////////////////////////////////////////////////
	BELOW THIS LINE IS WAYS TO CREATE THE EVENT OBJECT~~~~~~~~~~~~~~~~~~~~~~~
	///////////////////////////////////////////////////////////////////////////////////////////
	*/
	
	/*

	SELECT  events.event_description, events.event_ts, events.event_severity from events join (SELECT last_loc_id as loc_id, min_severity_web from users natural join dynamic_subscriptions where user_id = ?) as loc on loc.loc_id = events.loc_id join (select * from subscriptions where user_id = ?) as loc2 on loc2.loc_id = loc.loc_id where (events.event_severity>= loc.min_severity_web or events.event_severity >= loc2.min_severity_web) and events.event_severity <4

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