<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
		$this->publicList = array();
	}
	function execute() {
		if(!$this->author_no_list){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->author_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/author/sql/author_list.sql');
		$tree = $db->buildTree($result, 'author_no');
		$this->deleteList = $tree;

		$result = $db->statement('admin/publisher/author/sql/use_book.sql');
		$tree = $db->buildTree($result, 'author_no');
		$this->useBookList = $tree;

		if($this->publisher["jpo"] && !empty($this->useBookList)) {
			$result = $db->statement('admin/publisher/author/sql/use_jpo_book.sql');
			$tree = $db->buildTree($result);
			$this->useJpoBookList = $tree;
		} else {
			unset($this->useJpoBookList);
		}
/*
		$result = $db->statement('admin/publisher/author/sql/public_status.sql');
		$tree = $db->buildTree($result, 'author_no');
		$this->publicList = $tree;
*/
		$this->delete = false;
		if(!count($this->useBookList)||1){
			$this->delete = true;
		}
	}
}
?>