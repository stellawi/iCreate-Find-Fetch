<?php

namespace data;

class Item {
	private $_itemname;
	private $_itemtype;
	private $_venue;
	private $_date;
	private $_time;
	private $_insertdatetime;
	private $_username;
	private $_photopath;
	private $_isActive;
	
	public function __construct($itemname, $itemtype, $venue, $date, $time, $username, $photopath, $insertDateTime, $isActive) {
		$this->_itemname = $itemname;
		$this->_itemtype = $itemtype;
		$this->_venue = $venue;
		$this->_date = $date;
		$this->_time = $time;
		$this->_insertdatetime = $insertDateTime;
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
				$this->_insertdatetime,
				$this->_username,
				$this->_photopath,
				$this->_isActive
		);
		return $allInfo;
	}
	
	public function getItemName(){
		return $this->_itemname;
	}
	
	public function getItemType(){
		return $this->_itemtype;
	}
	
	public function getVenue(){
		return $this->_venue;
	}
	
	public function getDate(){
		return $this->_date;
	}
	
	public function getTime(){
		return $this->_time;
	}
	
	public function getInsertDateTime(){
		return $this->_insertdatetime;
	}
	
	public function getUserName(){
		return $this->_username;
	}
	
	public function getPhotoPath(){
		return $this->_photopath;
	}
	
	public function isItemActive(){
		return $this->_isActive;
	}
}
?>