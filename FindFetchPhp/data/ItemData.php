<?php

namespace data;

use data\Item;
use data\DataManager;
use exception\DataException;

class ItemData{
	private $_dataManagerInstance = NULL;

	public function __construct(){
		self::initializeItemData();
	}

	private function initializeItemData(){
		$this->_dataManagerInstance = new DataManager();
	}
}

?>