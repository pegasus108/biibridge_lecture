<?php
require_once('Debug.php');

class SmallSession extends Debug {
	var $key = null;

	function __construct($key) {
		$this->key = $key;

		Debug::__construct();

		if (!session_id()) {
			session_start();
		}
	}

	/**
	* Save values to session.
	* @access public
	*/
	function save($hash) {
		$_SESSION[$this->key] = $hash;
	}

	/**
	* Load values from session.
	* @access public
	* @return array
	*/
	function load() {
		if (!isset($_SESSION[$this->key])) {
			return NULL;
		}

		$hash = $_SESSION[$this->key];
		return $hash;
	}

	/**
	* Clear session.
	* @access public
	*/
	function clear() {
		unset($_SESSION[$this->key]);
	}
}
?>