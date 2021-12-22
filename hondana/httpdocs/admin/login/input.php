<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/DatabaseAction.php');

class Action extends DatabaseAction {
	function execute() {
		if($_SESSION['id'] && $_SESSION['pass']) {
			$this->__controller->redirectToURL('./?action=login');

			return false;
		}
	}
}
?>