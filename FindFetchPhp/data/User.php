<?php
namespace data;

class User {
	private $name;
	private $password;
	private $isNus;
	
	public function __construct($username, $userpassword, $isNusUser){
		$this->name = $username;
		$this->password = $userpassword;
		$this->isNus = $isNusUser;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function isPasswordMatch($userpassword){
		return $this->password == $userpassword;
	}
	
	public function isNusId(){
		return $this->isNus;
	}
}
?>