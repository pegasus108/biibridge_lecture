<?php
//ini_set('display_errors', 'on');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AdminController.php');

class Controller extends AdminController {
	function init() {
		parent::init();

		$this->__defaultAction = 'list';
		$this->__defaultActionFile = APP_ROOT . '/AuthAction.php';
		$this->__defaultActionClass = 'AuthAction';
	}
}

$controller = new Controller();
$controller->run();
?>