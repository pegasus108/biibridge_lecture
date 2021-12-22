<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if(empty($_REQUEST['bookNoList'])) {
			$_SESSION['mess'] = "並び替えできませんでした。";
			$this->__controller->redirectToURL('.');
			return false;
		}

		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('bookNoList', $this->bookNoList);

		$db->begin();
		$db->statement('admin/publisher/book/new_sort/sql/update.sql');
		$db->commit();

		$_SESSION['mess'] = "並び替えが完了しました。";
		$this->__controller->redirectToURL('.');

		return false;
	}
}
?>