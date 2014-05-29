<?php
namespace server;

require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/User.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/UserData.php');

use data\User;
use data\UserData;

$username = $_POST['username'];
$password = $_POST['password'];
$type = $_POST['type'];

define("TYPE_WEB", "web");
define("TYPE_APPS", "apps");

$db = new UserData();

$htmlPage = "http://localhost/FindFetchPhp/webui/login.html";

try {
	$curUser = $db->getUser($username);
} catch (DataException $ex) {
	$response["success"] = 0;
	$response["message"] = $ex->getMessage();
	die(json_encode($response));
	
	
	if (strcmp($type, TYPE_WEB) == 0){
		// wait for 2 seconds
		usleep(2000000);
		
		header("Location: " . $htmlPage);
	} 
}

if ($curUser->isPasswordMatch($password)){
	$response["success"] = 1;
	$response["message"] = "Login successful!";
	die(json_encode($response));
	
	if (strcmp($type, TYPE_WEB) == 0){
		// wait for 2 seconds
		usleep(2000000);
		
		header("Location: " . $htmlPage);
	} 
} else {
	$response["success"] = 0;
	$response["message"] = "Invalid Credentials!";
	die(json_encode($response));
	
	if (strcmp($type, TYPE_WEB) == 0){
		// wait for 2 seconds
		usleep(2000000);
		
		header("Location: " . $htmlPage);
	} 
}


?>