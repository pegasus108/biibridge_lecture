<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

    var $sqlPath = 'admin/admin/publisher/sql/';

	function execute() {

		if(!empty($this->back)) {
			$this->back = null;

			return;
		}
	}
}
?>