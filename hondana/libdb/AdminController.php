<?php
require_once(dirname(__FILE__) . '/simple/DefaultController.php');
require_once(dirname(__FILE__) . '/simple/DefaultView.php');
require_once(dirname(__FILE__) . '/simple/Database.php');

require_once('AdminView.php');
require_once('setAdminSession.php');

class AdminController extends DefaultController {
	function __construct() {
		DefaultController::__construct();
	}

	function init() {
		DefaultController::init();

		$this->__viewClass = 'AdminView';
	}
}
?>