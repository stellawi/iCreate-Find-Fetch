<?php
namespace server;

error_reporting(E_ALL);

$rootFile = $_SERVER['DOCUMENT_ROOT'];

require_once ($rootFile . '/data/User.php');
require_once ($rootFile . '/data/UserData.php');
require_once ($rootFile . '/exception/DataException.php');
require_once ($rootFile . '/common/LogManager.php');

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