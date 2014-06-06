<?php
	namespace server;
	
	error_reporting(E_ALL);

	$rootFile = $_SERVER['DOCUMENT_ROOT'];
	
	$dot = ".";

	$filename = $_FILES["uploaded_file"]["name"];
	$filetype = $_FILES["uploaded_file"]["type"];
	$filesize = $_FILES["uploaded_file"]["size"];
	$filetemp = $_FILES["uploaded_file"]["tmp_name"];
	$fileerror = $_FILES['uploaded_file']['error'];
	
    $file_path = "/upload/";
    $file_path = $rootFile . $file_path . basename( $_FILES['uploaded_file']['name']);
    
    echo $file_path . "<br>";
    echo "Upload: " . $filename . "<br>";
    echo "Type: " . $filetype . "<br>";
    echo "Size: " . ($filesize / 1024) . " kB <br>";
    echo "Stored in: " . $filetemp . "<br>";
    
    $maxFileSize = 1024000; 
    
    if ($filesize > $maxFileSize) {
    	echo "File size too big! File size should be at most {$maxFileSize}/1024 kB";
    	return;
    } elseif ($fileerror > 0) {
    	echo "Error: " . $fileerror . "<br>";
    	return;
    } elseif (file_exists($file_path)) {
    	$rand = 0;
    	$file_temp_path = $file_path;
    	
    	$fileDots = explode($dot , $file_path);
    	$dotsNum = count($fileDots) - 1;
    	$fileExtension = $dot . $fileDots[$dotsNum];
    	
    	while (file_exists($file_temp_path)){
    		$file_temp_path = str_replace ($fileExtension , $rand . $fileExtension , $file_path);
    		$rand++;
    	}
    	$file_path = $file_temp_path;
    } 
    
    if (move_uploaded_file($filetemp , $file_path)) {
        echo "success";
    } else {
        echo "fail";
    }
    
 ?>