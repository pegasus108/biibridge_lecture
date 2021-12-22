<?php
ini_set('display_errors', 'on');
// error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE);

ini_set('memory_limit', '150M');
//ini_set('post_max_size', '150M');
//ini_set('upload_max_filesize', '150M');

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