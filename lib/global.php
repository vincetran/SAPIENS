<?php
include("../db/db.php");

function getCPSDropDown(){
	$db = connectDb();
	$sql = "SELECT cp_id, cp_name FROM cell_providers";
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($cp_id, $cp_name);
	while($stmt->fetch())
	{
		echo "\t<option value=\"".$cp_id."\">".$cp_name."</option>\n";
	}
}

function register($user, $pass, $fname, $lname, $email, $cps){
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
		$sql = "SELECT cp_template FROM cell_providers WHERE cp_id=?";
		$stmt = $dp->prepare($sql);
		$stmt->bind_param('i', $sps);
		$stmt->execute();
		$stmt->bind_result($cpt);
		$sql = "INSERT INTO users(user_login_name, user_login_pass, user_firstname, user_lastname, user_email, user_cell_provider, user_cell_email) VALUES(?,?,?,?,?,?,?)";
		$stmt = $db->prepare($sql);
		$stmt->bind_param('sssssis',$user, $pass, $fname, $lname, $email, $cps, $cps.$cpt);
		$stmt->execute();
		$db->close();
		setcookie('ID_a3', $user, time()+3600);
	}
}

?>