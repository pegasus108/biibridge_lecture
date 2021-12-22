<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		if($this->target_category == NULL){
			$this->root = true;
		} else {
			$this->root = false;
		}
	}

	function execute() {
		$db =& $this->_db;
		$db->assign($this->getProperties());

		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$db->statement('admin/publisher/news/category/sql/insert.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>