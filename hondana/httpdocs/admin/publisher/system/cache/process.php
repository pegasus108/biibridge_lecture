<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$this->removeCache();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>