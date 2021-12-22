<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		// WYSIWYG入力フィールド内 フルパス → ルートパスへ変換して保存 (SSLページでエラーにならないように)
		$this->unsetFullpath(array('value'),$_SESSION['publisher_url']);

		if(!empty($this->publisher['news_category_edit'])) {
			// お知らせカテゴリ複数登録可能の場合
			if(empty($this->news_category_no)) {
				// 1つめのカテゴリを設定
				$this->news_category_no = $this->news_category_list[0];
			}
		}

		$db =& $this->_db;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$db->statement('admin/publisher/news/sql/insert.sql');
		$db->commit();


		/**
		 * 公開ステータスが「公開」で、公開日（public_date）が未来の場合に、キャッシュ削除のスケジュールを>追加
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
