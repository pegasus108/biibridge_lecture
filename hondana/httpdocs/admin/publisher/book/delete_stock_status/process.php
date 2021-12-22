<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	var $rootCategory = '1';
	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());
		$db->assign('stock_status_no', $this->add_stock_status_no);

		$db->begin();
		$db->statement('admin/publisher/book/sql/delete_stock_status.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>