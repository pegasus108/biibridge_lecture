<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;

		$db->assign('info_no_list', $this->info_no_list);

		$db->begin();
		$db->statement('admin/admin/info/sql/display.sql');
		$db->commit();


		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>