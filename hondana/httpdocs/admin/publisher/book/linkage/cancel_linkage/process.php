<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;
		if(isset($_REQUEST['book_no_list']) && isset($_REQUEST['cancel_linkage_id'])){

			$db->assign('publisher_no', $_SESSION['publisher_no']);
			$db->assign($this->getProperties());

			$db->begin();
			$db->statement('admin/publisher/book/linkage/sql/cancel.sql');
			$db->commit();

			$this->clearProperties();

		}

		$this->__controller->redirectToURL('../?inherit=1');

		return false;

	}
}
?>