<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if(!$this->author_no){
			$this->__controller->redirectToURL('../');
		}

		if($this->back) {
			$this->back = false;
			return;
		}

		$db =& $this->_db;
		$db->assign('author_no', $this->author_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/author/sql/author.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);
	}
}
?>