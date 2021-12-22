<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function init() {
		parent::init();

		if($_REQUEST['new_entry']) {
			$this->clearProperties();

			$this->target_label = $_REQUEST['target_label'];

			$this->__controller->redirectToURL('./');
			return false;
		}
	}
}
?>