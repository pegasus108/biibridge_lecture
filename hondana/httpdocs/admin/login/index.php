<?php
// ini_set('display_errors', 'on');
// error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AdminController.php');

class Controller extends AdminController {
	function init() {
		parent::init();

		$this->__defaultAction = 'input';
	}
}

$controller = new Controller();
$controller->run();

unset($_SESSION['error_session_timeout']);
?>