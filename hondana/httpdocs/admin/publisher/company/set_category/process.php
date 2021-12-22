<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	var $rootCategory = '1';
	function execute() {
		$listString = join($this->company_no_list,',');
		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('company_no_list', $this->company_no_list);
		$db->assign('company_category_no', $this->add_category_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$db->statement('admin/publisher/company/sql/set_category.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>