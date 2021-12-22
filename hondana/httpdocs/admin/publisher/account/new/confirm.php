<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	function validate() {

		$this->errors = array();

		if (!$this->name) {
			$this->errors['name'] = 'アカウント名の記入をご確認ください。';
		}

		if (!$this->id) {
			$this->errors['id'] = 'ログインIDをご確認ください。';
		}elseif(!$this->isId($this->id)){
			$this->errors['id'] = 'ログインIDは半角英数にてご記入下さい。';
		}

		if (empty($this->password)) {
			$this->errors['password'] = 'パスワードの記入をご確認ください。';
		}elseif(!$this->isId($this->password)){
			$this->errors['password'] = 'パスワードは半角英数にてご記入下さい。';
		}

		if (!$this->role_no) {
			$this->errors['role_no'] = '権限の選択をご確認ください。';
		}

		if(count($this->errors)) {
			return 'input';
		}


		return true;
	}
}
?>