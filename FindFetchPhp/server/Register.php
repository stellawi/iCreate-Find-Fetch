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
$confirmPassword = $_POST['confirm_password'];
$type = $_POST['type'];

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
		return;
	}
	
	session_start();
	
	$response["success"] = 1;
	$response["message"] = "Register successful!";
	
	echo (json_encode($response));
}
	

?>