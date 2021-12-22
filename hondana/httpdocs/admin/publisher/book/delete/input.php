<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
		$this->publicList = array();
	}
	function execute() {
		if(!$this->book_no_list){
			$this->__controller->redirectToURL('../');
		}

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/sql/book_list.sql');
		$tree = $db->buildTree($result, 'book_no');
		$this->deleteList = $tree;

		$result = $db->statement('admin/publisher/book/sql/use_relate.sql');
		$tree = $db->buildTree($result, 'book_rerate_no');
		$this->useRelateList = $tree;

		$result = $db->statement('admin/publisher/book/sql/use_news.sql');
		$tree = $db->buildTree($result, 'news_relete_no');
		$this->useNewsList = $tree;

	}
}
?>