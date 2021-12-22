<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if($this->back) {
			$this->back = false;
			return;
		}

		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('genre_no', $this->genre_no);
		$result = $db->statement('admin/publisher/book/genre/sql/item.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

	}
}
?>