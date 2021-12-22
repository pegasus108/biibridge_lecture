<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	var $rootCategory = '1';
	function execute() {
		$listString = join($this->company_category_no_list,',');
		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/company/category/sql/delete_order_depth.sql');
		$tree = $db->buildTree($result, 'company_category_no');
		$deleteList = $tree;

		$db->assign('deleteCategoryList', $deleteList);

		$db->begin();
		$db->statement('admin/publisher/company/category/sql/delete.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>