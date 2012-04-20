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

function register($user, $pass, $fname, $lname, $email, $pnumber, $cps){
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
		$stmt->fetch();
		
		$sql = "INSERT INTO users(user_firstname, user_lastname, user_login_name, user_login_pass, user_email, user_cell_phone, user_cell_provider, user_cell_email) VALUES(?,?,?,?,?,?,?,?)";
		if($stmt = $db->prepare($sql))
		{
			//$stmt2->bind_param('ssssssis', $fname, $lname, $user, $pass, $email, $pnumber, $cps, $pnumber.$cpt);
			$stmt->bind_param('ssssssis', 'vincent', 'tran', 'vince', 'lol', 'freedom1378@gmail.com', '2152005593', 2, '2152005594@txt.att.net');
			$stmt->execute();
			$db->close();
			setcookie('ID_SAPIENS', $user, time()+3600);
		}
		else
		{
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

function login($user, $pass){
	$db = connectDb();
	$sql = "SELECT user_id FROM users WHERE user_login_name=? and user_login_pass=?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param('ss', $user, $pass);
	$stmt->execute();
	if($stmt->fetch())
	{
		setcookie('ID_a3', $user, time()+3600);
		$db->close();
		return 1;
	}
	else
	{
		$db->close();
		return -1;
	}
}

?>