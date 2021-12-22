<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());

		/* banner list */
		$result = $db->statement('admin/publisher/banner/big/sql/list.sql');

		$tree = $db->buildTree($result, 'banner_big_no');
		$this->bannerList = $tree;
	}

}
?>