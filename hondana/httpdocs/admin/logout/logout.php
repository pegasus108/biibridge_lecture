<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends DefaultAction {
	function execute() {
		$this->clearProperties();
		session_unset();
		$this->__controller->redirectToURL('/admin/');
	}
}
?>