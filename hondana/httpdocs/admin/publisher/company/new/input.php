<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		/* company category list */
		$result = $db->statement('admin/publisher/company/sql/category_list.sql');
		$tree = $db->buildTree($result, 'company_category_no');
		$this->companyCategoryList = $tree;

		if($this->back) {
			$this->back = false;
			return;
		}

		$result = $db->statement('admin/publisher/company/sql/current_timestamp.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);
	}
}
?>