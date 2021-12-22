<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AdminController.php');

class Controller extends AdminController {
	function init() {
		parent::init();

		$this->__defaultAction = 'input';
		$this->__defaultActionFile = $_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php';
		$this->__defaultActionClass = 'AuthAction';
	}
}

$controller = new Controller();
$controller->run();
?>