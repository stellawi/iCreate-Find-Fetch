<?php
namespace data;

$rootFile = $_SERVER['DOCUMENT_ROOT'];

//require_once ($rootFile . '/common/LogManager.php');

use common\LogManager;
use common\FileInteractor;
use exception\DataException;
use PDO;
use PDOException;

class DataManager {
	private $_logTyper = NULL;
	private $_username = NULL;
	private $_password = NULL;
	private $_host = NULL;
	private $_dbname = NULL;
	private $_charset = NULL;
	private $_db = NULL;
	const FILE_NAME = "DataAccess.config";
	const PARAM_NAME = "username";
	const PARAM_PASS = "password";
	const PARAM_HOST = "host";
	const PARAM_BASE = "dbname";
	const PARAM_CHARSET = "charset";
	const EQUAL = "=";
	const QUESTION_MARK = "?";
	const BRACKET_OPEN = "(";
	const BRACKET_CLOSE = ")";
	const COMA = ",";
	const SPACE = " ";
	
	public function __construct(){
		self::initializeDataManager();
	}
	
	private function initializeDataManager(){
		$this->_logTyper = LogManager::getInstance();
		self::getDataAccess();
		self::loadDatabase();
	}
	
	private function getDataAccess(){
		global $rootFile;
		$mode = "r";
		$src = $rootFile . "/data/" . self::FILE_NAME;
		
		$lines = FileInteractor::interactWithFile($mode, $src);
		if (is_array($lines)){
			foreach ($lines as &$buffer) {
				$delimiter = "=";
				$tokens = explode($delimiter, $buffer);
			
				if (isset ($tokens[0])) {
					$trimmed[0] = trim($tokens[0]);
				} else {
					continue;
				}
			
				if (isset ($tokens[1])) {
					$trimmed[1] = trim($tokens[1]);
				} else {
					continue;
				}
			
				if (strcmp(self::PARAM_NAME, $trimmed[0]) == 0) {
					$this->_username = $trimmed[1];
				} elseif (strcmp(self::PARAM_PASS, $trimmed[0]) == 0) {
					$this->_password = $trimmed[1];
				} elseif (strcmp(self::PARAM_BASE, $trimmed[0]) == 0) {
					$this->_dbname = $trimmed[1];
				} elseif (strcmp(self::PARAM_HOST, $trimmed[0]) == 0) {
					$this->_host = $trimmed[1];
				} elseif (strcmp(self::PARAM_CHARSET, $trimmed[0] == 0)) {
					$this->_charset = $trimmed[1];
				} else {
					//do nothing
				}
			}
			
			unset ($buffer);
		} else {
			echo print_r($lines);
			die ("Loading access error");
		}
		
	}

	private function loadDatabase(){
		// UTF-8 is a character encoding scheme that allows you to conveniently store
		// a wide varienty of special characters, like ¢ or €, in your database.
		// By passing the following $options array to the database connection code we
		// are telling the MySQL server that we want to communicate with it using UTF-8
		// See Wikipedia for more information on UTF-8:
		// http://en.wikipedia.org/wiki/UTF-8
		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $this->_charset);
		 
		// A try/catch statement is a common method of error handling in object oriented code.
		// First, PHP executes the code within the try block.  If at any time it encounters an
		// error while executing that code, it stops immediately and jumps down to the
		// catch block.  For more detailed information on exceptions and try/catch blocks:
		// http://us2.php.net/manual/en/language.exceptions.php
		try {
			// This statement opens a connection to your database using the PDO library
			// PDO is designed to provide a flexible interface between PHP and many
			// different types of database servers.  For more information on PDO:
			// http://us2.php.net/manual/en/class.pdo.php
			$db = new PDO("mysql:host={$this->_host};dbname={$this->_dbname};charset={$this->_charset}", $this->_username, $this->_password, $options);
		} catch (PDOException $ex){
			$errorMessage = "Failed to connect to the database: ";
			$this->_logTyper->enterLog($errorMessage);
			die ($errorMessage);
		}
		 
		// This statement configures PDO to throw an exception when it encounters
		// an error.  This allows us to use try/catch blocks to trap database errors.
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 
		// This statement configures PDO to return database rows from your database using an associative
		// array.  This means the array will have string indexes, where the string value
		// represents the name of the column in your database.
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		 
		self::setDatabaseAs($db);
		
		self::undoMagicQuote();
		 
		// This tells the web browser that your content is encoded using UTF-8
		// and that it should submit content back to you using UTF-8
		header('Content-Type: text/html; charset=utf-8');
	}
	
	private function setDatabaseAs($db) {
		$this->_db = $db;
	}
	
	private function undoMagicQuote(){
		// This block of code is used to undo magic quotes.  Magic quotes are a terrible
		// feature that was removed from PHP as of PHP 5.4.  However, older installations
		// of PHP may still have magic quotes enabled and this code is necessary to
		// prevent them from causing problems.  For more information on magic quotes:
		// http://php.net/manual/en/security.magicquotes.php
		if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
		{
			function undo_magic_quotes_gpc(&$array)
			{
				foreach($array as &$value)
				{
					if(is_array($value))
					{
						undo_magic_quotes_gpc($value);
					}
					else
					{
						$value = stripslashes($value);
					}
				}
			}
		
			undo_magic_quotes_gpc($_POST);
			undo_magic_quotes_gpc($_GET);
			undo_magic_quotes_gpc($_COOKIE);
		}
	}
	
	/**
	 * 
	 * @param string $data
	 * @param string $param
	 * @param string $src
	 * @throws DataException
	 * @return array
	 */
	public function retrieveSingleData($data, $param, $src){
		$src = trim($src);
		$param = trim($param);
		
		$query = "Select * FROM " . $src . " where " . $param . " = ?";
		
		$queryExec = array ($data);
		
		try {
			$stmt = self::executeFlexiQuery($query, $queryExec);
		} catch (DataException $ex){
			throw $ex;
		}
		
		$row = $stmt->fetch();
		
		return $row;
	}
	
	/**
	 * 
	 * @param string $src
	 * @throws DataException
	 * @return array
	 */
	public function retrieveAllDataFrom($src){
		$src = trim($src);
		
		$query = "Select * FROM " . $src;
		
		try {
			$stmt = self::executeQuery($query);
		} catch (DataException $ex){
			throw $ex;
		}
		
		$rows = $stmt->fetchAll();
		
		return $rows;
	}
	
	/**
	 * 
	 * @param array $dataParams
	 * @param array $dataValues
	 * @param string $dest
	 * @throws DataException
	 */
	public function insertData($dataParams, $dataValues, $dest){
		$dest = trim($dest);
		
		$query = self::getInsertQueryString($dest, $dataParams);

		try {
			$stmt = self::executeFlexiQuery($query, $dataValues);
		} catch (DataException $ex){
			throw $ex;
		}
	}

	/**
	 * 
	 * @param String $data
	 * @param String $param
	 * @param String $src
	 * @throws DataException
	 */
	public function deleteData($data, $param, $src){
		$src = trim($src);
		$param = trim($param);
		
		$query = "DELETE FROM " . $src . " where " . $param . " = ?";
		
		$queryExec = array ($data);
		
		try {
			$stmt = self::executeFlexiQuery($query, $queryExec);
		} catch (DataException $ex){
			throw $ex;
		}
	}
	
	/**
	 * 
	 * @param array $data
	 * @param String $mainParam
	 * @param array $otherParams
	 * @param String $src
	 * @throws DataException
	 */
	public function updateData($data, $mainParam, $otherParams, $src){
		$src = trim($src);
		
		$query = self::getUpdateQueryString($mainParam, $otherParams, $src);
		
		$queryExec = $data;
		
		try {
			$stmt = self::executeFlexiQuery($query, $queryExec);
		} catch (DataException $ex){
			throw $ex;
		}
	}
	
	private function getUpdateQueryString($mainParam, $otherParams, $src){
		$queryString = "UPDATE " . $src . self::SPACE;
		
		$setString = "SET ";
		
		$count = count($otherParams);
		for ($i = 0; $i < $count; $i++) {
			$curParam = trim($otherParams[$i]);
			
			$isLast = $i == ( $count - 1 );
			
			$curParam .= self::EQUAL . self::QUESTION_MARK;
			
			if (!isLast){
				$curParam .= self::COMA . self::SPACE;
			}
			
			$setString .= $curParam;
		}
		
		$mainParam = trim($mainParam);
		$whereString = "WHERE " . $mainParam . self::EQUAL . self::QUESTION_MARK;
		
		$queryString .= $setString . self::SPACE . $whereString;
		
		return $queryString;
	}
	
	private function getInsertQueryString($dest, $dataParams){
		$count = 0;
		$queryParams = self::BRACKET_OPEN;
		$queryValues = self::BRACKET_OPEN;
		foreach ($dataParams as $dataParam){
			$dataParam = trim($dataParam);
			$dataValue = self::QUESTION_MARK;
			if ($count === 0){
				$count++;
			} else {
				$dataParam = self::COMA . self::SPACE . $dataParam;
				$dataValue = self::COMA . self::SPACE . $dataValue;
			}
			
			$queryParams .= $dataParam;
			$queryValues .= $dataValue;
		}
		$queryParams .= self::BRACKET_CLOSE;
		$queryValues .= self::BRACKET_CLOSE;
		unset ($dataParam);
		
		$queryString = 'INSERT INTO ' . $dest . self::SPACE . $queryParams . ' VALUES ' . $queryValues;
		return $queryString;
	} 
	
	private function executeQuery($query){
		$queryExec = NULL;
		try {
			$stmt = executeFlexiQuery($query, $queryExec);
		} catch (DataException $ex){
			throw $ex;
		}
		return $stmt;
	}
	
	private function executeFlexiQuery($query, $queryExec){
		try {
			$stmt   = $this->_db->prepare($query);
			
			if ($queryExec != NULL){
				$result = $stmt->execute($queryExec);
			} else {
				$result = $stmt->execute();
			}
		}
		catch (PDOException $ex) {
			$errorMessage = "Database Error!";
			$this->_logTyper->enterLog($errorMessage);
			throw new DataException($errorMessage);
		}
		return $stmt;
	}
	
	public function closeDatabase(){
		$_db = NULL;
	}
}
?>