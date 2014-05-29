<?php
namespace data;

require '/common/Autoload.php';

use data\User;
use data\DataManager;
use exception\DataException;

class UserData{
	private $dataManagerInstance = NULL;
	
	const USER_DATA_TABLE = "users";
	const PARAM_NAME = "USERNAME";
	const PARAM_PASS = "PASSWORD";
	const PARAM_ISNUS = "ISNUS";
	
	public function __construct(){
		self::initializeUserData();
	}
	
	private function initializeUserData(){
		$this->dataManagerInstance = new DataManager();
	}

	/**
	 * 
	 * @param String $username
	 * @throws DataException
	 * @return \data\User|NULL
	 */
	public function getUser($username){
		$param = self::PARAM_NAME;
		
		try {
			$curUserData = $this->dataManagerInstance->retrieveSingleData($username, $param, self::USER_DATA_TABLE);
		} catch (DataException $ex){
			throw $ex;
		}
		
		if ($curUserData){
			$name = $curUserData[self::PARAM_NAME];
			$pass = $curUserData[self::PARAM_PASS];
			$isNus = $curUserData[self::PARAM_ISNUS];
			
			$curUser = new User($name, $pass, $isNus);
			
			return $curUser;
		} else {
			return NULL;
		}
	}

	/**
	 * 
	 * @param String $username
	 * @throws DataException
	 * @return boolean
	 */
	public function hasUser($username){
		try {
			$curUser = self::getUser($username);
		} catch (DataException $ex){
			throw $ex;
		}
		
		if (curUser == NULL){
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 
	 * @param \data\User $curUser
	 * @throws DataException
	 */
	public function saveUserToDatabase($curUser){
		$dataParams = array(self::PARAM_NAME, self::PARAM_PASS, self::PARAM_ISNUS);
		$dataValues = $curUser->getAllInfo();
		$dest = self::USER_DATA_TABLE;
		
		try {
			$this->dataManagerInstance->insertData($dataParams, $dataValues, $dest);
		} catch (DataException $ex){
			throw $ex;
		}
	}
	
	public function closeUserData(){
		$this->dataManagerInstance->closeDatabase();
	}
}
?>