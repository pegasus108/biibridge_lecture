<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	var $rootseries = '1';
	function execute() {
		$listString = join($this->series_no_list,',');
		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/series/sql/delete_order_depth.sql');
		$tree = $db->buildTree($result, 'series_no');
		$deleteList = $tree;

		$db->assign('deleteseriesList', $deleteList);

		$db->begin();
		$db->statement('admin/publisher/book/series/sql/delete.sql');
		$db->commit();

		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>