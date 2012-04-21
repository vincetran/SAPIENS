<?php
/*
How to get a User Object:
	Login(username, password)
	Register(ShitTonOfInfo)
	Resume(SomeCookie/ServerSession)

What can a User do?
	Checkin
	Send an ENS alert?
	Subscribe to a locations ENS shit.
*/
class User{
	private function __construct(){

	}
	public function checkin($location){
		// TODO: Use the location object to update the backend database with this information.
	}
	public function sendENS($location){
		// TODO: Use location to send ENS alert from that (also check if it is possible!)
	}
	public function subscribe($location){
		// TODO: Send database the subscription information for le location
	}
}

?>