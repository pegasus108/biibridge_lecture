<?php
require_once('AbstractAction.php');

class DefaultAction extends AbstractAction {
	function __construct(&$controller) {
		AbstractAction::__construct($controller);
	}

	function init() {
		AbstractAction::init();
	}

	function prepare() {
	}

	function execute() {
		return null;
	}

	function error(&$condition) {
		if ($condition) {
			return $condition;
		}
		else {
			$this->__controller->redirectToAction($this->__controller->__defaultAction);
			return false;
		}
	}

	function dispose() {
	}

	function validate() {
		return true;
	}

	function decision($condition = null) {
		if ($condition === true) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>