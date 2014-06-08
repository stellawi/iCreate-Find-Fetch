<?php

namespace data;

class Item {
	private $_itemname;
	private $_itemtype;
	private $_venue;
	private $_date;
	private $_time;
	private $_username;
	private $_photopath;
	private $_isActive;
	
	public function __construct($itemname, $itemtype, $venue, $date, $time, $username, $photopath, $isActive) {
		$this->_itemname = $itemname;
		$this->_itemtype = $itemtype;
		$this->_venue = $venue;
		$this->_date = $date;
		$this->_time = $time;
		$this->_username = $username;
		$this->_photopath = $photopath;
		$this->_isActive = $isActive;
	}
	
	public function getAllInfo() {
		$allInfo = array (
				$this->_itemname,
				$this->_itemtype,
				$this->_venue,
				$this->_date,
				$this->_time,
				$this->_username,
				$this->_photopath,
				$this->_isActive
		);
		return $allInfo;
	}
}
?>