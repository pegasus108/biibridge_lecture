<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('banner_big_no_list', $this->banner_big_no_list);


		$db->begin();
		$db->statement('admin/publisher/banner/big/sql/display.sql');
		$db->commit();


		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>