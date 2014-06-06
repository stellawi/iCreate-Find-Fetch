<?php
namespace data;

class User {
	private $_name;
	private $_password;
	private $_phonenumber;
	
	public function __construct($username, $userpassword, $phonenumber){
		$this->_name = $username;
		$this->_password = $userpassword;
		$this->_phonenumber = $phonenumber;
	}
	
	public function getAllInfo(){
		$allInfo = array($this->_name, $this->_password, $this->_phonenumber);
		return $allInfo;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function isPasswordMatch($userpassword){
		return $this->_password == $userpassword;
	}
	
	public function getPhoneNumber(){
		return $this->_phonenumber;
	}
}
?>