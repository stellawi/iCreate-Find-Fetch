<?php
namespace exception;

require_once ($_SERVER['DOCUMENT_ROOT'] . '/exception/FindFetchException.php');

use exception\FindFetchException;

class DataException extends FindFetchException{
}
?>