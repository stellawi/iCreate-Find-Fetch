<?php
namespace server;

$rootFile = $_SERVER['DOCUMENT_ROOT'];

require_once ($rootFile . '/server/initial.php');

use data\UploadedFile;
	
$dot = ".";

$filename = $_FILES["uploaded_file"]["name"];
$filetype = $_FILES["uploaded_file"]["type"];
$filesize = $_FILES["uploaded_file"]["size"];
$filetemp = $_FILES["uploaded_file"]["tmp_name"];
$fileerror = $_FILES['uploaded_file']['error'];

$curFile = new UploadedFile($filename, $filetype, $filesize, $filetemp, $fileerror);

echo "Upload: " . $filename . "<br>";
echo "Type: " . $filetype . "<br>";
echo "Size: " . ($filesize / 1024) . " kB <br>";
echo "Stored in: " . $filetemp . "<br>";
    
$response = $curFile->saveToDirectory();
echo (json_encode($response));

?>