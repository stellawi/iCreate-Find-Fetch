<?php
namespace common;

$rootFile = $_SERVER['DOCUMENT_ROOT'];

require_once ($rootFile . '/common/FileInteractor.php');

use common\FileInteractor;

class LogManager {
	private $_destEmail = NULL;
	private $_destDirectory = NULL;
	private static $_logManagerInstance = NULL;
	const FILE_NAME = "LogDestination.config";
	const PARAM_EMAIL = "destEmail";
	const PARAM_DIR = "destDirectory";
	
	private function __construct(){
		self::initializeLogManager();
	}
	
	private function initializeLogManager(){
		global $rootFile;
		$mode = "r";
		$src = $rootFile . "/common/" . self::FILE_NAME;
		
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
					
				if (strcmp(self::PARAM_EMAIL, $trimmed[0]) == 0) {
					$this->_destEmail = $trimmed[1];
				} elseif (strcmp(self::PARAM_DIR, $trimmed[0]) == 0) {
					$this->_destDirectory = $rootFile . $trimmed[1];
				} else {
					//do nothing
				}
			}
		
			unset ($buffer);
		} else {
			$errorMsg = "can't access LogFile";
			die($errorMsg);
		}
		
		$this->_destDirectory .= self::getLogFileName();
	}
	
	private function getLogFileName(){
		$today = date("Ymd");//yyyymmdd
		
		$prefix = "/FindFetch";
		$extension = ".log";
		
		$filename = $prefix . $today . $extension;
		
		return $filename;
	}

	public static function getInstance(){
		if (self::$_logManagerInstance == NULL) {
			self::$_logManagerInstance = new LogManager();
		}
		
		return self::$_logManagerInstance;
	}

	public function enterLog($message){
		$isEmailSent = error_log($message, 1, $this->_destEmail);
		$isSavedToDirectory = error_log($message, 3, $this->_destDirectory);
		return $isEmailSent || $isSavedToDirectory;
	}
}
?>