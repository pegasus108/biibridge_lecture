<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/DatabaseAction.php');

class Action extends DatabaseAction {

	function execute(){
		// 認証チェック
		if(!$this->auth()) {
				$this->__controller->redirectToURL('error.html');
				exit();
		}

		return false;
	}

	function auth() {
		if($_SESSION['loginid'] && $_SESSION['pass']) {
			$db =& $this->_db;

			$db->assign('id', $_SESSION['loginid']);
			$db->assign('pass', $_SESSION['pass']);
			$result = $db->statement('admin/sql/auth.sql');

			if($db->fetch_assoc($result)) {
				return true;
			}
		}
		return false;
	}
}
?>