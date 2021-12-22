<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init() {
		parent::init();

		$this->noCartStockList = array( 5, 6 );
	}

	function execute() {
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());


		$db->begin();
		$db->statement('admin/publisher/book/sql/cart.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>