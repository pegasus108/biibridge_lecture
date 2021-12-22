<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());

		$result = $db->statement('admin/publisher/system/sql/list.sql');

		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

		/* banner list */
		$result = $db->statement('admin/publisher/system/netshop/sql/list.sql');

		$tree = $db->buildTree($result, 'netshop_no');
		$this->netshopList = $tree;
	}

}
?>