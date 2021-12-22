<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

    var $sqlPath = 'admin/publisher/system/sql/';

	function execute() {
		$db =& $this->_db;
        $db->setSqlDirectoryPath($this->sqlPath);
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$db->statement('update');
		$db->commit();

		$_SESSION['pass'] = $this->pass;
		$cart = $this->cart;

		$this->clearProperties();
		$this->cart = $cart;
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>