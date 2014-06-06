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
$confirmPassword = $_POST['confirm_password'];
$type = $_POST['type'];
$log = LogManager::getInstance();

define("TYPE_WEB", "web");
define("TYPE_APPS", "apps");

$db = new UserData();

$htmlPage = "http://localhost/server/register.html";

try {
	$isExisted = $db->hasUser($username);
} catch (DataException $ex) {
	$response["success"] = 0;
	$response["message"] = $ex->getMessage();
	
	if (strcmp($type, TYPE_WEB) == 0) {
		echo "go back " . $htmlPage . " \n";
	}
	$log->enterLog($ex->getMessage());
	return;
}

if (strcmp($password, $confirmPassword) != 0) {
	$response["success"] = 0;
	$response["message"] = "Password different with Confirm Password";
	
	if (strcmp($type, TYPE_WEB) == 0){
		echo "go back " . $htmlPage . " \n";
	}
	
	echo (json_encode($response));
} elseif ($isExisted) {
	$response["success"] = 0;
	$response["message"] = "User already existed!";
	
	if (strcmp($type, TYPE_WEB) == 0){
		echo "go back " . $htmlPage . " \n";
	}
	
	echo (json_encode($response));
} else {
	$curUser = new User($username, $password);
	
	try {
		$db->saveUserToDatabase($curUser);
	} catch (DataException $ex) {
		$response["success"] = 0;
		$response["message"] = $ex->getMessage();
		
		if (strcmp($type, TYPE_WEB) == 0) {
			echo "go back " . $htmlPage . " \n";
		}
		$log->enterLog($ex->getMessage());
		return;
	}
	
	
	$response["success"] = 1;
	$response["message"] = "Register successful!";
	$_SESSION['authuser'] = 1;
	
	echo (json_encode($response));
}
	

?>