<?php
namespace test;
		
$rootFile = $_SERVER['DOCUMENT_ROOT'];

require_once ($rootFile . '/server/initial.php');
//require_once ($rootFile . '/common/LogManager.php');
	
use common\LogManager;
	
$message = "bla";
	
$log = LogManager::getInstance();
$log->enterLog($message);
?>