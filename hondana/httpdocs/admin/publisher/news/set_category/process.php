<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	var $rootCategory = '1';
	function execute() {
		$listString = join($this->news_no_list,',');
		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('news_no_list', $this->news_no_list);
		$db->assign('news_category_no', $this->add_category_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		if(!empty($this->publisher['news_category_edit'])) {
			// カテゴリ複数登録可能の場合
			$db->statement('admin/publisher/news/set_category/sql/set_category_multiple.sql');
		} else {
			$db->statement('admin/publisher/news/sql/set_category.sql');
		}
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>