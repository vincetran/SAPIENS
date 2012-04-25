<?php
require_once("../db/db.php");
/*
How to get a User Object:
	User::login(username, password);
	User::register($user, $pass, $fname, $lname, $email, $pnumber, $cps);
	User::resume();

Interface:
	$user.checkin(Location obj);
	$user.sendENS(Location obj);
	$user.subscribe(Location obj);
*/
class User{
	public $username, $userId, $first, $last, $email, $phone, $provider, $lastLogin;
	private function __construct($user){
		$db = connectDb();
		$sql = "SELECT user_id, user_firstname, user_lastname, user_email, user_cell_phone, user_cell_email, user_login_name FROM users WHERE user_login_name=?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('s', $user);
		$stmt->execute();
		$stmt->bind_result($this->userId, $this->first, $this->last, $this->email, $this->phone, $this->provider, $this->username);
		$stmt->fetch();
		$db->close();
	}
	public function checkin($location){
		// TODO: Use the location object to update the backend database with this information.
	}
	public function sendENS($location){
		// TODO: Use location to send ENS alert from that (also check if it is possible!)
	}
	public function getSubs(){
		$db = connectDb();
		$sql = "SELECT sub_id, loc_id, loc_name, loc_description, min_severity_web, min_severity_email, min_severity_text 
			from locations natural join 
			(select sub_id, loc_id, min_severity_web, min_severity_email, min_severity_text 
				from subscriptions natural join users where users.user_id=?) as a";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('s', $this->userId);
		$stmt->execute();
		$stmt->bind_result($subId, $locId, $locName, $locDescription, $minWeb, $minEmail, $minText);
		while($stmt->fetch()){
			echo "<form action=\"subscriptions.php\" method=\"post\">";
			echo "<tr>\n";
			echo "<td>".$locName."</td>\n<td>".$locDescription."</td>\n<td>".$minWeb.
			"</td>\n<td>".$minEmail."</td>\n<td>".$minText."</td>\n";
			echo "<td><input type=\"submit\" class=\"butt_input\" name=\"unsub\" value=\"Unsubscribe\">";
			echo "<input type=\"hidden\" name=\"loc_id\" value=\"".$locId."\">";
			echo "</tr>";
			echo "</form>";
		}
	}
	/*
	///////////////////////////////////////////////////////////////////////////////////////////
	BELOW THIS LINE IS WAYS TO CREATE THE USER OBJECT~~~~~~~~~~~~~~~~~~~~~~~
	///////////////////////////////////////////////////////////////////////////////////////////
	*/


	/*
		Register: Takes a username, password, first name, last name, email, phone, and cellphone provider
		Return: User (onSuccess)
				-1	(onDuplicateUsername)

	*/
	public static function register($user, $pass, $fname, $lname, $email, $pnumber, $cps){
		$db = connectDb();
		$sql = "SELECT user_id FROM users WHERE user_login_name=?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('s', $user);
		$stmt->execute();
		if($stmt->fetch())
		{
			$db->close();
			return -1;
		}
		else
		{
			$cps = intval($cps);
			$sql = "SELECT cp_template FROM cell_providers WHERE cp_id=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('i', $cps);
			$stmt->execute();
			$stmt->bind_result($cpt);
			// TODO: Should we make a check on this to make sure it returns a valid Cell phone number
			$stmt->fetch();
			$stmt->close();
			
			$sql = "INSERT INTO users(user_firstname, user_lastname, user_login_name, user_login_pass, user_email, user_cell_phone, user_cell_provider, user_cell_email) VALUES(?,?,?,?,?,?,?,?)";
			if($stmt = $db->prepare($sql))
			{
				$phone_email = $pnumber.$cpt;
				$stmt->bind_param('ssssssis', $fname, $lname, $user, $pass, $email, $pnumber, $cps, $phone_email);
				$stmt->execute();
				$db->close();
				setcookie('ID_SAPIENS', $user, time()+3600);
				return new User($user);
			}
			else
			{
				//Debug shit
				echo "FName: ".$fname."\n";
				echo "LName: ".$lname."\n";
				echo "User: ".$user."\n";
				echo "Pass: ".$pass."\n";
				echo "Email: ".$email."\n";
				echo "PNumber: ".$pnumber."\n";
				echo "Cps: ".$cps."\n";
				echo "PNumber: ".$pnumber.$cpt."\n";
			}
		}
	}


	/*
		FUNCTION: login
		Params: username  -- used to set cookie and check user/pw combo
				password  -- herp.
		Return: User (onSuccess)
				-1 (on no valid username)
				-2 (on not valid user+pw)
	*/
	public static function login($user, $pass){
		$db = connectDb();
		$sql = "SELECT user_id FROM users WHERE user_login_name=?";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('s', $user);
		$stmt->execute();
		if($stmt->fetch()){
			$stmt->close();
			$sql = "SELECT user_id FROM users WHERE user_login_name=? and user_login_pass=?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('ss', $user, $pass);
			$stmt->execute();
			if($stmt->fetch())
			{
				setcookie('ID_SAPIENS', $user, time()+3600);
				$db->close();
				return new User($user);
			}
			else
			{
				$db->close();
				return -2;
			}
		}
		else{
			$db->close();
			return -1;
		}
	}

	public static function resume(){
		if($_COOKIE && isset($_COOKIE['ID_SAPIENS']) && User::checkUser($_COOKIE['ID_SAPIENS'])){
			return new User($_COOKIE['ID_SAPIENS']);
		}
		else{
			return null;
		}
	}

	/*
		FUNCTION: checkUser
		PARAMS: username
		RETURN: True if username is in database, else false.
	*/
	public static function checkUser($username){
		$db = connectDb();
		$stmt = $db->prepare("SELECT user_id FROM users WHERE user_login_name=?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		if($stmt->fetch())
		{
			$db->close();
			return TRUE;
		}
		else
		{
			$db->close();
			return FALSE;
		}
	}

	public function logout(){
		setcookie('ID_SAPIENS', $username, time()-3600);
		return 1;
	}
}

?>