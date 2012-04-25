<?php
function connectDb()
{
	$db = new mysqli('localhost', 'root', '', 'sapiens');
	if(!$db){
		die('Could not connect');
	}
	return $db;
}
?>