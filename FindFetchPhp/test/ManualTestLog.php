<?php
namespace test;
	
error_reporting(E_ALL);
	
$rootFile = $_SERVER['DOCUMENT_ROOT'];
require_once ($rootFile . '/common/LogManager.php');
	
use common\LogManager;
	
$message = "bla";
	
$log = LogManager::getInstance();
$log->enterLog($message);
?>