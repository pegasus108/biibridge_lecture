<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());
		// 出版社情報取得
		$result = $db->statement('admin/publisher/system/sql/list.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

		// 電子書籍書店リスト
		$db->assign("publisher_no",$_SESSION['publisher_no']);
		$db->assign("book_no","");
		$result = $db->statement('admin/publisher/system/ebookstores/sql/list.sql');
		$this->ebookstoreList = $db->buildTree($result, 'id');
	}
}
?>