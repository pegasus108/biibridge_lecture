<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
	}
	function execute() {
		if(!$this->news_no_list || !$this->add_category_no){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->news_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('news_category_no', $this->add_category_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		// news 取得
		$result = $db->statement('admin/publisher/news/sql/news_list.sql');
		$tree = $db->buildTree($result, 'news_no');
		$this->setCategoryList = $tree;

		// 公開されているnews取得
		$result = $db->statement('admin/publisher/news/sql/public_status.sql');
		$tree = $db->buildTree($result, 'news_no');
		$this->publicList = $tree;

		// お知らせカテゴリ取得
		$result = $db->statement('admin/publisher/news/sql/category.sql');
		$row = $db->fetch_assoc($result);
		$this->categoryName = $row['name'];

		$check_set_category = null;
		if(!empty($this->publisher['news_category_edit'])) {
			// カテゴリ複数登録可能の場合
			$result = $db->statement('admin/publisher/news/set_category/sql/check_set_category.sql');
			$check_set_category = $db->buildTree($result, 'news_no');
			$in_news = array_keys($this->setCategoryList);
			foreach ($check_set_category as $k => $v) {
				if(in_array($k,$in_news)) {
					// 既にカテゴリが設定されているNEWSは除外
					unset($this->setCategoryList[$k]);
				}
			}
		}
		$this->check_set_category = $check_set_category;
	}
}
?>