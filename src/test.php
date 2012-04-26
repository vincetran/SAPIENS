<?php
include("../lib/EventManager.php");
$rawr = new EventManager(User::resume());
if($_POST['type'] == 1){
echo json_encode($rawr->getList());	
}
else{
echo json_encode($rawr->getFullList());	
}
?>