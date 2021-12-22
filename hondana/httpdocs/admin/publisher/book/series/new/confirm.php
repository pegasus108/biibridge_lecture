<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function validate() {
		$this->errors = array();

		if (!$this->name) {
			$this->errors['name'] = 'シリーズ名の記入をご確認ください。';
		}

		if(count($this->errors)) {
			return 'input';
		}

		return true;
	}
}
?>