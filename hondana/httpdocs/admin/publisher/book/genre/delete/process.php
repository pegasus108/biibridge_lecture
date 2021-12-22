<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	var $rootGenre = '1';
	function execute() {
		$listString = join($this->genre_no_list,',');
		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/genre/sql/delete_order_depth.sql');
		$tree = $db->buildTree($result, 'genre_no');
		$deleteList = $tree;

		$db->assign('deleteGenreList', $deleteList);

		$db->begin();
		$db->statement('admin/publisher/book/genre/sql/delete.sql');
		$db->commit();

		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>