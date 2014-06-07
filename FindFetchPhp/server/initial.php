<?php

namespace server;

$rootFile = $_SERVER ['DOCUMENT_ROOT'];
class ClassAutoloader {
	public function __construct() {
		spl_autoload_register ( array (
				$this,
				'loader' 
		) );
	}
	
	private function loader($className) {
		global $rootFile;		
		
		$className = str_replace ( "\\", "/", $className );
		$className = $rootFile . "/" . $className . '.php';
		
		
		echo $className . "\n";
		
		if (! file_exists ( $className )) {
			return;
		}
		
		require_once ($className);
	}
}

$autoloader = new ClassAutoloader ();

error_reporting ( E_ALL );
session_start ();

?>