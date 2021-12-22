<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/publisher/core/book/netshop.php');

class NetshopAction extends Action {

	function execute() {

		$this->publisher_no = $_SESSION['publisher_no'];

		parent::execute();

		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		//デザイン取得
		$result = $db->statement('admin/publisher/book/sql/publisher.sql');
		$row = $db->fetch_assoc($result);
		$design = $row['design'];

		//本番
		return '/publisher/' . $design . '/book/netshop';
	}

	function getSqlPath() {
		return 'publisher/core/';
	}

}
?>