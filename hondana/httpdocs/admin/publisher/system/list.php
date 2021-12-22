<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

    var $sqlPath = 'admin/publisher/system/sql/';

	function execute() {

		$db =& $this->_db;
		$db->setSqlDirectoryPath($this->sqlPath);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$row = $db->statementFetch('list');
		$row["ga_password"] = preg_replace("/./u", "*", $row["ga_password"]);
		$this->setProperties($row);

		$this->noticeFields = $db->statementTree("book_field_list");

		if(!empty($row['logo'])) {
			$this->image = 'images/custom/' . $row['logo'];
		} elseif(file_exists($_SERVER['DOCUMENT_ROOT'] . '/publisher/' . $_SESSION['id'] . '/images/custom/logo.gif')) {
			$this->image = 'images/custom/logo.gif';
		} else {
			$this->image = null;
		}

	}

}
?>