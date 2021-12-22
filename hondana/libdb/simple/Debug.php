<?php
class Debug {
	var $__debugName = null;

	function __construct() {
		$this->init();
	}

	function init() {
		$this->__debugName = 'debug';
	}

	/**
	* Get debug mode.
	* @access public
	* @return bool
	*/
	function getDebugMode() {
		return $_REQUEST[$this->__debugName] ?? null;
	}

	/**
	* print_r for HTML.
	* @access public
	*/
	function print_r($var) {
		print('<pre style="text-align: left;">');
		print_r($var);
		print('</pre>');
	}

	/**
	* var_dump for HTML.
	* @access public
	*/
	function var_dump($var) {
		print('<pre style="text-align: left;">');
		var_dump($var);
		print('</pre>');
	}

	/**
	* print_r for HTML only at debugging.
	* @access public
	*/
	function print_rOnlyDebug($var, $mode = null) {
		$condition = $this->decision($mode);
		if ($condition) {
			$this->print_r($var);
		}
	}

	/**
	* var_dump for HTML only at debugging.
	* @access public
	*/
	function var_dumpOnlyDebug($var, $mode = null) {
		$condition = $this->decision($mode);
		if ($condition) {
			$this->var_dump($var);
		}
	}

	/**
	* Decision debug.
	* @access private
	* @return bool
	*/
	function decision($mode = null) {
		$currentMode = $this->getDebugMode();

		if ($mode) {
			if ($mode == $currentMode) {
				return true;
			}
		}
		elseif (isset($currentMode)) {
			return true;
		}

		return false;
	}
}
