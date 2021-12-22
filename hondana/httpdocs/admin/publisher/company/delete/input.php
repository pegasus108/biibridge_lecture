<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
		$this->publicList = array();
	}
	function execute() {
		if(!$this->company_no_list){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->company_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/company/sql/company_list.sql');
		$tree = $db->buildTree($result, 'company_no');
		$this->deleteList = $tree;

		$result = $db->statement('admin/publisher/company/sql/public_status.sql');
		$tree = $db->buildTree($result, 'company_no');
		$this->publicList = $tree;

	}
}
?>