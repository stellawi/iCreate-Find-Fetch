<?php
namespace common;

require '/common/Autoload.php';

use common\FileInteractor;

class LogManager {
	private $_destEmail = null;
	private $_destDirectory = null;
	private $_logManagerInstance = null;
	const FILE_CONFIG_LOG = "/common/LogDestination.config";
	const PARAM_EMAIL = "destEmail";
	const PARAM_DIR = "destDirectory";
	
	private function __construct(){
		initializeLogManager();
	}
	
	private function initializeLogManager(){
		$mode = "r";
		$lines = FileInteractor::interactWithFile($mode, FILE_CONFIG_LOG);
		
		foreach ($lines as &$buffer) {
			$delimiter = "=";
			$tokens = explode($delimiter, $buffer);
				
			if ($tokens[0] != null) {
				$trimmed[0] = trim($tokens[0]);
			} else {
				continue;
			}
				
			if ($tokens[1] != null) {
				$trimmed[1] = trim($tokens[1]);
			} else {
				continue;
			}
				
			if (strcmp(PARAM_EMAIL, $trimmed[0]) == 0) {
				$_destEmail = $trimmed[1];
			} elseif (strcmp(PARAM_DIR, $trimmed[0]) == 0) {
				$_destDirectory = $trimmed[1];
			} else {
				//do nothing
			}
		}
		
		unset ($buffer);
	}

	public static function getInstance(){
		if ($_logManagerInstance == null) {
			$_logManagerInstance = new LogManager();
		}
		
		return $_logManagerInstance;
	}

	public function enterLog($message){
		$isEmailSent = error_log($message, 1, $_destEmail);
		$isSavedToDirectory = error_log($message, 3, $_destDirectory);
		return $isEmailSent || $isSavedToDirectory;
	}
}
?>