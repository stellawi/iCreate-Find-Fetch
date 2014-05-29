<?php
namespace common;

require '/common/Autoload.php';

use common\FileInteractor;

class LogManager {
	private $_destEmail = NULL;
	private $_destDirectory = NULL;
	private $_logManagerInstance = NULL;
	const FILE_CONFIG_LOG = "/common/LogDestination.config";
	const PARAM_EMAIL = "destEmail";
	const PARAM_DIR = "destDirectory";
	
	private function __construct(){
		self::initializeLogManager();
	}
	
	private function initializeLogManager(){
		$mode = "r";
		$lines = FileInteractor::interactWithFile($mode, self::FILE_CONFIG_LOG);
		
		foreach ($lines as &$buffer) {
			$delimiter = "=";
			$tokens = explode($delimiter, $buffer);
				
			if ($tokens[0] != NULL) {
				$trimmed[0] = trim($tokens[0]);
			} else {
				continue;
			}
				
			if ($tokens[1] != NULL) {
				$trimmed[1] = trim($tokens[1]);
			} else {
				continue;
			}
				
			if (strcmp(self::PARAM_EMAIL, $trimmed[0]) == 0) {
				$this->_destEmail = $trimmed[1];
			} elseif (strcmp(self::PARAM_DIR, $trimmed[0]) == 0) {
				$this->_destDirectory = $trimmed[1];
			} else {
				//do nothing
			}
		}
		
		unset ($buffer);
	}

	public static function getInstance(){
		if ($this->_logManagerInstance == NULL) {
			$this->_logManagerInstance = new LogManager();
		}
		
		return $this->_logManagerInstance;
	}

	public function enterLog($message){
		$isEmailSent = error_log($message, 1, $this->_destEmail);
		$isSavedToDirectory = error_log($message, 3, $this->_destDirectory);
		return $isEmailSent || $isSavedToDirectory;
	}
}
?>