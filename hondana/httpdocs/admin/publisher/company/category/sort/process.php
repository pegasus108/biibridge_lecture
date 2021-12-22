<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('company_category_no', $this->company_category_no);

		$db->begin();
		if($this->order == "up"){
			$db->statement('admin/publisher/company/category/sql/sort_up.sql');
		}elseif($this->order == "down"){
			$db->statement('admin/publisher/company/category/sql/sort_down.sql');
		}
		$db->commit();


		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>