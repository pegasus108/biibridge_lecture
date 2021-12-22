<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if($this->back) {
			$this->back = false;

			return;
		}

		$db =& $this->_db;

		$db->assign('banner_big_no', $this->banner_big_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$result = $db->statement('admin/publisher/banner/big/sql/item.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

	}
}
?>