<?php
namespace server;

$rootFile = $_SERVER['DOCUMENT_ROOT'];

require_once ($rootFile . '/server/initial.php');

use data\UserData;
use data\User;
use exception\DataException;
use common\LogManager;

$username = $_POST['username'];
$password = $_POST['password'];
$type = $_POST['type'];
$type = trim ($type);
$log = LogManager::getInstance();

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
	$log->enterLog($ex->getMessage());
	echo (json_encode($response));
	return;
}

if ($curUser == NULL){
	$response["success"] = 0;
	$response["message"] = "No such user!!";
	
	if (strcmp($type, TYPE_WEB) == 0){
		echo "go back " . $htmlPage . " \n";
	} 
	
	echo (json_encode($response));
} elseif ($curUser->isPasswordMatch($password)){
	
	$response["success"] = 1;
	$response["message"] = "Login successful!";
	$_SESSION['authuser'] = 1;
	$_SESSION['user'] = $curUser;
	
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