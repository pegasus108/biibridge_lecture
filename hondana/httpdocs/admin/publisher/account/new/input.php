<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {

		if($this->back) {
			$this->back = false;
			return;
		}

		$db =& $this->_db;
		$db->assign('publisher_account_no', $this->publisher_account_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/account/sql/role_list.sql');
		$tree = $db->buildTree($result, 'role_no');
		$this->role = $tree;

	}
}
?>