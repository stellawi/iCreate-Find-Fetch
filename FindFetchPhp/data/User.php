<?php
namespace data;

class User {
	private $_name;
	private $_password;
	
	public function __construct($username, $userpassword, $isNusUser){
		$this->_name = $username;
		$this->_password = $userpassword;
	}
	
	public function getAllInfo(){
		$allInfo = array($this->_name, $this->_password, $this->_isNus);
		return $allInfo;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function isPasswordMatch($userpassword){
		return $this->_password == $userpassword;
	}
}
?>