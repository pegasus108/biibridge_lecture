<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	var $rootCategory = '1';
	function execute() {
		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		foreach($this->deleteList as $row){
			unlink($_SERVER['DOCUMENT_ROOT'].$row['image']);
		}
		$db->statement('admin/publisher/account/sql/delete.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>