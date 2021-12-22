<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('publisher_payment_no_list', $this->payment_option_no_list);


		$db->begin();
		$db->statement('admin/publisher/system/payment_option/sql/display_none.sql');
		$db->commit();


		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>