<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$result = $db->statement('admin/publisher/book/new_sort/sql/list.sql');

		$this->newBookList = $db->buildTree($result);

		unset($this->errors);
		if (!empty($_SESSION['mess'])) {
			$this->errors['sort'] = $_SESSION['mess'];
			unset($_SESSION['mess']);
		}
	}
}
?>