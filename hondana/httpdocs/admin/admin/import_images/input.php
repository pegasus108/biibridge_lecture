<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function execute() {

		$db =& $this->_db;

		$result = $db->statement('admin/admin/import_images/sql/publisher_list.sql');
		$tree = $db->buildTree($result, 'publisher_no');
		$this->publisherList = $tree;
	}

}
?>