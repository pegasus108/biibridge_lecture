<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/publisher/book/list.php');
class listAction extends Action {

	function execute() {
		parent::execute();
		$db =& $this->_db;
		$result = $db->statement('admin/publisher/book/sql/book_format.sql');
		$this->bookFormat = $db->buildTree($result, 'id');
	}
}
?>