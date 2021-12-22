<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
		$this->publicList = array();
	}
	function execute() {
		if(!$this->news_no_list){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->news_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/news/sql/news_list.sql');
		$tree = $db->buildTree($result, 'news_no');
		$this->deleteList = $tree;

		$result = $db->statement('admin/publisher/news/sql/public_status.sql');
		$tree = $db->buildTree($result, 'news_no');
		$this->publicList = $tree;

	}
}
?>