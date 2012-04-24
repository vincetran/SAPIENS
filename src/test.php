<?php
include("../lib/EventManager.php");
$rawr = new EventManager(User::resume());
echo json_encode($rawr->getFullList());
?>