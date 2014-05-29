<?php
namespace data;

class User {
	private $_name;
	private $_password;
	private $_isNus;
	
	public function __construct($username, $userpassword, $isNusUser){
		$this->_name = $username;
		$this->_password = $userpassword;
		$this->_isNus = $isNusUser;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function isPasswordMatch($userpassword){
		return $this->_password == $userpassword;
	}
	
	public function isNusId(){
		return $this->_isNus;
	}
}
?>