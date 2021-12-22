<?php
// ini_set('display_errors', 'on');
// error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AdminController.php');

class Controller extends AdminController {
	function init() {
		parent::init();
		$this->__defaultAction = 'top';
		$this->__defaultActionFile = $_SERVER['DOCUMENT_ROOT'] . '/../libdb/DatabaseAction.php';
		$this->__defaultActionClass = 'DatabaseAction';
	}
}
$controller = new Controller();
$controller->run();
?>