<?php

namespace test;

$rootFile = $_SERVER ['DOCUMENT_ROOT'];

require_once ($rootFile . '/server/initial.php');
require_once ('/PHPUnit/Autoload.php');

use data\User;
use PHPUnit_Framework_TestCase;

class TestUser extends PHPUnit_Framework_TestCase {
	private function isArraySame($arrayA, $arrayB) {
		if (!is_array($arrayA) || !is_array($arrayB)){
			return false;
		}
		
		sort ( $arrayA );
		sort ( $arrayB );
		
		return $arrayA == $arrayB;
	}
	
	public function testConstruct() {
		$name = "Lynnette";
		$pass = "18101993";
		$phone = "90529098";
		$userDetails = array (
				$name,
				$pass,
				$phone 
		);
		$curUser = new User ( $name, $pass, $phone );
		$allInfo = $curUser->getAllInfo ();
		$this->assertTrue(isArraySame($userDetails, $allInfo));
	}
}
?>