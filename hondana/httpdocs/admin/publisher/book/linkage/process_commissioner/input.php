<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
	}

	function preapare(){
		if(!$this->book_no_list){
			$this->__controller->redirectToURL('../');
		}
	}
	function execute() {
		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/linkage/sql/linkage_commissioner_list.sql');
		$tree = $db->buildTree($result, 'book_no');
		$this->bookList = $tree;

		$result = $db->statement('admin/publisher/book/linkage/sql/linkage_commissioner_status.sql');
		$tree = $db->buildTree($result, 'book_no');
		$this->statusList = $tree;

		$this->total = count($this->bookList);

		$result = $db->statement('admin/publisher/book/linkage/sql/publisher.sql');
		$row = $db->fetch_assoc($result);
		$this->publisher = $row;

		$this->processFlag = false;
		if($this->total && $this->publisher['linkage_person_mail'])
			$this->processFlag = true;
	}
}
?>