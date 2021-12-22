<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('banner_big_no', $this->banner_big_no);

		$db->begin();
		if($this->order == "up"){
			$db->statement('admin/publisher/banner/big/sql/sort_up.sql');
		}elseif($this->order == "down"){
			$db->statement('admin/publisher/banner/big/sql/sort_down.sql');
		}
		$db->commit();


		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>