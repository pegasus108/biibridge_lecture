<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		// WYSIWYG入力フィールド内 フルパス → ルートパスへ変換して保存 (SSLページでエラーにならないように)
		$this->unsetFullpath(array('value'),$_SESSION['publisher_url']);

		$db =& $this->_db;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$db->statement('admin/publisher/company/sql/insert.sql');
		$db->commit();

		/**
		 * 公開ステータスが「公開」で、公開日（public_date）が未来の場合に、キャッシュ削除のスケジュールを追加
		 */
		if($this->public_status == 1 && strtotime($this->public_date) > time()) {
			$this->removeCacheSchedule($this->public_date);
		}

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>
