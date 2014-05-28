<?php
namespace common;

class FileInteractor {
	public static function interactWithFile($mode, $src) {
		$mode = "r";
		$handle = @fopen(FILE_CONFIG_LOG, $mode);
		if ($handle) {
			$length = 4096;
			$lineNo = 0;
			while (($buffer = fgets($handle, $length)) !== false) {
				$lines[$lineNo] = $buffer;
				$lineNo++;
			}
			if (!feof($handle)) {
				die("Error: unexpected fgets() fail\n");
			}
			fclose($handle);
		}
		
		return $lines;
	}
}
?>