<?php
namespace exception;

error_reporting(E_ALL);

require_once ($_SERVER['DOCUMENT_ROOT'] . '/exception/FindFetchException.php');

use exception\FindFetchException;

class DataException extends FindFetchException{
}
?>