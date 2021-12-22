<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	var $rootCategory = '1';
	function execute() {
		$db =& $this->_db;
		$db->assign($this->getProperties());

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('category_name', $this->name);
		$db->assign('news_category_no', $this->news_category_no);

		$db->begin();
		$db->statement('admin/publisher/news/category/sql/update.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>