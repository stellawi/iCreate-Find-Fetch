<?php

namespace test;

$rootFile = $_SERVER ['DOCUMENT_ROOT'];

require_once ($rootFile . '/data/User.php');
require_once ('/PHPUnit/Autoload.php');

use PHPUnit_Framework_TestCase;
use data\User;

class UserTest extends PHPUnit_Framework_TestCase {
	private function isArraySame($arrayA, $arrayB) {
		if (! is_array ( $arrayA ) || ! is_array ( $arrayB )) {
			return false;
		}
		
		sort ( $arrayA );
		sort ( $arrayB );
		
		return $arrayA == $arrayB;
	}
	
	
	public function testUserClass() {
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
		
		$this->assertTrue ( self::isArraySame ( $userDetails, $allInfo ) );
		$this->assertEquals($name, $curUser->getName());
		$this->assertEquals($phone, $curUser->getPhoneNumber());
		$this->assertTrue($curUser->isPasswordMatch($pass));		
	}
}

?>