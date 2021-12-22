<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('book_no_list', $this->book_no_list);

		/* recommend count */
		$result = $db->statement('admin/publisher/book/sql/other_list_recommend_count.sql');
		$row = $db->fetch_assoc($result);
		$this->recommend_count = $row['recommend_count'] + count($this->book_no_list);

		if ($this->recommend_count > 10) {
			$this->setSimpleMessage("おすすめに設定される書誌が10冊を超えるため、処理を中断しました。");
			$this->__controller->redirectToURL('../');
			return false;
		}

		if (count($this->book_no_list)) {
			$db->begin();
			$db->statement('admin/publisher/book/sql/recommend_status.sql');
			$db->commit();
		}

		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>