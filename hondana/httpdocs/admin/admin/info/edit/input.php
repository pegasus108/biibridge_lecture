<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if(!$this->info_no){
			$this->__controller->redirectToURL('../');
		}

		$db =& $this->_db;
		$db->assign('info_no', $this->info_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		if($this->back) {
			$this->back = false;
			return;
		}

		$result = $db->statement('admin/admin/info/sql/info.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);
	}
}
?>