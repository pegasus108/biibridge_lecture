<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$book_no = $this->book_no;

//		$this->release_date_1 = mb_convert_kana($this->release_date_1,'N');

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$db->statement('admin/publisher/book/linkage/commissioner/sql/insert.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete&book_no='.$book_no);

		return false;
	}
}
?>