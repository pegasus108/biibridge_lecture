<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

    var $sqlPath = 'admin/publisher/system/sql/';

	function execute() {

		if($this->back) {
			$this->back = null;
			return;
		}

		$db =& $this->_db;
		$db->setSqlDirectoryPath($this->sqlPath);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$row = $db->statementFetch('list');
		$this->setProperties($row);

		$this->noticeFields = $db->statementTree("book_field_list");

		if(!empty($row['logo'])) {
			$this->image = 'images/custom/' . $row['logo'];
		} else {
			$this->image = null;
		}

	}

}
?>