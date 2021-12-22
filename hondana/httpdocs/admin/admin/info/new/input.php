<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/admin/info/sql/current_timestamp.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);
	}
}
?>