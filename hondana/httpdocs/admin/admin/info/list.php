<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Paginate.php');

class Action extends AuthAction {

	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());

		$result = $db->statement('admin/admin/info/sql/list.sql');

		$tree = $db->buildTree($result, 'info_no');
		$this->infoList = $tree;
	}

}
?>