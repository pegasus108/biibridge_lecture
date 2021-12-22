<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
		$this->publicList = array();
	}
	function execute() {
		if(!$this->banner_no_list){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->banner_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/banner/sql/banner_list.sql');
		$tree = $db->buildTree($result, 'banner_no');
		$this->deleteList = $tree;

		$result = $db->statement('admin/publisher/banner/sql/public_status.sql');
		$tree = $db->buildTree($result, 'banner_no');
		$this->publicList = $tree;

	}
}
?>