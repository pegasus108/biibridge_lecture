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
		$db->assign('label_no', $this->label_no);
		$result = $db->statement('admin/publisher/book/label/sql/item.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

	}
}
?>