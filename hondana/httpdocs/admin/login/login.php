<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/DatabaseAction.php');

class Action extends DatabaseAction {
	function execute() {
		$_SESSION['loginid'] = $this->id;
		$_SESSION['pass'] = $this->pass;
		$_SESSION['publisher_no'] = $this->authData['publisher_no'];
		$_SESSION['role_no'] = $this->authData['role_no'];

		$db =& $this->_db;
		$db->assign('publisher_no', $this->authData['publisher_no']);
		$result = $db->statement('admin/publisher/sql/publisher.sql');
		$publisher = $db->fetch_assoc($result);

		// HONDANA EC ステータスをSessionへ保存
		$_SESSION['publisher_hondanaec'] = $publisher['hondanaec_status'];

		$_SESSION['id'] = $publisher['id'];

		if($this->authData['role_no']==1) {
			$this->__controller->redirectToURL('../admin/');
		} else {

			$_SESSION['publisher_name'] = $publisher['name'];
			$_SESSION['publisher_cart'] = $publisher['cart'];

			if($publisher['url']) {
				$_SESSION['publisher_url'] = $publisher['url'];
			} else {
				$_SESSION['publisher_url'] = 'https://'.$_SERVER['HTTP_HOST'].'/';
			}

			if($_SESSION['auth_before']) {
				$this->__controller->redirectToURL($_SESSION['auth_before']);
			} else {
				$this->__controller->redirectToURL('../publisher/');
			}
		}

		return false;
		unset($_SESSION['id']);
		unset($_SESSION['pass']);
	}

	function validate() {
		$this->errors = array();

		if (!$this->id) {
			$this->errors['id'] = 'IDを入力してください。';
		} else {
			$this->id = mb_strtolower($this->id);
		}
		if (!$this->pass) {
			$this->errors['pass'] = 'パスワードを入力してください。';
		}


		if ($this->id && $this->pass) {

			$db =& $this->_db;

			$db->assign('id', $this->id);
			$db->assign('pass', $this->pass);
			$result = $db->statement('admin/sql/auth.sql');

			if($authData = $db->fetch_assoc($result)) {
				$this->authData = $authData;
			} else {
				$this->errors['id'] = 'IDかパスワードに誤りがあります。';
			}
		}

		if (count($this->errors)) {
			return 'input';
		}

		return true;
	}

}
?>