<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		if(in_array(-1 ,$this->publisher_ebookstores_no)) {
			// レコードが無いものがあれば まず登録を行う
			$db->assign('storeList', $this->storeList);
			$db->assign('publisher_ebookstores_no', $this->publisher_ebookstores_no);

			$db->begin();
			$db->statement('admin/publisher/system/ebookstores/sql/update_order.sql');
			$db->commit();
		}

		$db->assign('ebookstore_no_list', $this->ebookstore_no_list);

		$db->begin();
		$db->statement('admin/publisher/system/ebookstores/sql/display.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>