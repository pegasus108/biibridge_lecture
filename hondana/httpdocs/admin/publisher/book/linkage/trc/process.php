<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$book_no = $this->book_no;

		if ( !mb_ereg_match('^[0-9]{2}$', $this->release_date_1_day) ) {
			$this->release_date_1_day = mb_convert_kana($this->release_date_1_day,'N');
		}

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$db->statement('admin/publisher/book/linkage/trc/sql/insert.sql');
		$db->commit();


		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete&book_no='.$book_no);

		return false;
	}
}
?>