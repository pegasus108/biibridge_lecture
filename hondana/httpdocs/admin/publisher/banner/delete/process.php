<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
//		$_REQUEST['debug'] = true;
//		$listString = join($this->banner_no_list,',');
		$db =& $this->_db;
		$db->assign('banner_no_list', $this->banner_no_list);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		foreach($this->deleteList as $row){
			unlink($_SERVER['DOCUMENT_ROOT'] . $row['image']);
		}
		$db->statement('admin/publisher/banner/sql/delete.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>