<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init() {
		parent::init();

		$this->addMaxDepth = '1';
	}

	function execute() {
		//$addMaxDepth = '1';

		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('addMaxDepth', $this->addMaxDepth);
		$result = $db->statement('admin/publisher/company/category/sql/list.sql');

		$tree = $db->buildTree($result, 'company_category_no');
		$this->companyCategoryList = $tree;
	}

}
?>