<?php
namespace server;

require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/User.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/UserData.php');

use data\User;
use data\UserData;

$username = $_POST['username'];
$password = $_POST['password'];
$type = $_POST['type'];
$type = trim ($type);

define("TYPE_WEB", "web");
define("TYPE_APPS", "apps");

$db = new UserData();

$htmlPage = "http://localhost/server/login.html";

try {
	$curUser = $db->getUser($username);
} catch (DataException $ex) {
	$response["success"] = 0;
	$response["message"] = $ex->getMessage();	
	
	if (strcmp($type, TYPE_WEB) == 0){
		// wait for 5 seconds
		// header("refresh: 5; Location: " . $htmlPage);
		echo "go back " . $htmlPage . " \n";
	} 
	
	echo (json_encode($response));
}

if ($curUser == NULL){
	$response["success"] = 0;
	$response["message"] = "No such user!!";
	
	if (strcmp($type, TYPE_WEB) == 0){
		// wait for 5 seconds
		// header("Location: " . $htmlPage);
		echo "go back " . $htmlPage . " \n";
	} 
	
	echo (json_encode($response));
} elseif ($curUser->isPasswordMatch($password)){
	$response["success"] = 1;
	$response["message"] = "Login successful!";
	
	if (strcmp($type, TYPE_WEB) == 0){
		// wait for 5 seconds
		// header("Location: " . $htmlPage);
		echo "go back " . $htmlPage . " \n";
	} 
	
	echo (json_encode($response));
} else {
	$response["success"] = 0;
	$response["message"] = "Invalid Credentials!";
	
	if (strcmp($type, TYPE_WEB) == 0){
		// wait for 5 seconds
		// header("Location: " . $htmlPage);
		echo "go back " . $htmlPage . " \n";
	} 
	
	echo (json_encode($response));
}


?>