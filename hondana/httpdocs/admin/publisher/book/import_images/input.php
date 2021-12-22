<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function execute() {
		// 書影一括取込機能が有効になっていない場合はTOPへリダイレクト
		if(empty($this->publisher['import_images'])) {
			$this->__controller->redirectToURL('/admin/publisher/');
		}
	}
}
?>