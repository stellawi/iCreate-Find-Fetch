<?php
namespace server;

$rootFile = $_SERVER['DOCUMENT_ROOT'];

require_once ($rootFile . '/data/User.php');
require_once ($rootFile . '/data/UserData.php');
require_once ($rootFile . '/exception/DataException.php');

use data\UserData;
use data\User;
use exception\DataException;

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
		echo "go back " . $htmlPage . " \n";
	} 
	
	echo (json_encode($response));
}

if ($curUser == NULL){
	$response["success"] = 0;
	$response["message"] = "No such user!!";
	
	if (strcmp($type, TYPE_WEB) == 0){
		echo "go back " . $htmlPage . " \n";
	} 
	
	echo (json_encode($response));
} elseif ($curUser->isPasswordMatch($password)){
	session_start();
	
	$response["success"] = 1;
	$response["message"] = "Login successful!";
	
	echo (json_encode($response));
} else {
	$response["success"] = 0;
	$response["message"] = "Invalid Credentials!";
	
	if (strcmp($type, TYPE_WEB) == 0){
		echo "go back " . $htmlPage . " \n";
	} 
	
	echo (json_encode($response));
}


?>