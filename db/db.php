<?php
function connectDb()
{
	$db = new mysqli('localhost', 'root', '128411', 'sapiens');
	if(!$db){
		die('Could not connect');
	}
	return $db;
}
?>