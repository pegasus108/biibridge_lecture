<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
		$this->publicList = array();
	}
	function execute() {
		if(!$this->publisher_account_no_list){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->publisher_account_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/account/sql/publisher_account_list.sql');
		$tree = $db->buildTree($result, 'publisher_account_no');
		$this->deleteList = $tree;

		$result = $db->statement('admin/publisher/account/sql/use_book.sql');
		$tree = $db->buildTree($result, 'publisher_account_no');
		$this->useBookList = $tree;
/*
		$result = $db->statement('admin/publisher/account/sql/public_status.sql');
		$tree = $db->buildTree($result, 'publisher_account_no');
		$this->publicList = $tree;
*/
		$this->delete = true;
	}
}
?>