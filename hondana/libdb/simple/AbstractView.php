<?php
require_once('Debug.php');

class AbstractView extends Debug {
	var $__prefix = null;
	var $__suffix = null;
	var $__action = null;
	var $__renderer = null;

	function __construct(&$action) {
		$this->__action =& $action;

		Debug::__construct();
	}

	/**
	* Fetch view.
	* @access public
	* @return string
	*/
	function fetch() {
		//Please implement.
	}

	/**
	* Display view.
	* @access public
	*/
	function display() {
		//Please implement.
	}
}
?>