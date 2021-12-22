<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init() {
		parent::init();

		$this->addMaxDepth = '2';
	}

	function execute() {
		//$addMaxDepth = '1';

		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('addMaxDepth', $this->addMaxDepth);
		$result = $db->statement('admin/publisher/book/label/sql/list.sql');

		$tree = $db->buildTree($result, 'label_no');
		$this->labelList = array_values($tree);

		unset($this->errors);
		if (!empty($_SESSION['mess'])) {
			$this->errors['sort'] = $_SESSION['mess'];
			unset($_SESSION['mess']);
		}
	}

}
?>