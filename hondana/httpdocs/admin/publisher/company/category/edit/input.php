<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if($this->back) {
			$this->back = false;
			return;
		}

		$db =& $this->_db;

		$db->assign('company_category_no', $this->company_category_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$result = $db->statement('admin/publisher/company/category/sql/item.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

	}
}
?>