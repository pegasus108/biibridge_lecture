<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if(empty($_REQUEST['storeList'])) {
			$_SESSION['mess'] = "並び替えできませんでした。";
			$this->__controller->redirectToURL('.');
			return false;
		}

		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('storeList', $this->storeList);
		$db->assign('publisher_ebookstores_no', $this->publisher_ebookstores_no);

		$db->begin();
		$db->statement('admin/publisher/system/ebookstores/sql/update_order.sql');
		$db->commit();
		$this->__controller->redirectToURL('.');

		return false;
	}
}
?>