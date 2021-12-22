<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		/* news category list */
		$result = $db->statement('admin/publisher/news/sql/category_list.sql');
		$tree = $db->buildTree($result, 'news_category_no');
		$this->newsCategoryList = $tree;

		if($this->back) {
			// 関連書籍情報 再取得
			if(empty($this->bookList) && !empty($this->news_relate_list)) {
				// 関連書籍 書籍情報を取得
				$db->assign('booknolist',implode(",", $this->news_relate_list));
				$result = $db->statement('admin/publisher/news/sql/get_relate_book.sql');
				$bookviewlist = $db->buildTree($result, 'book_no');
				$bookarraylist = array();
				// 関連書籍 並び順反映
				foreach ($this->news_relate_list as $k => $v) {
					$bookarraylist[] = $bookviewlist[$v];
				}
				$this->bookList = $bookarraylist;
			}

			if(!empty($this->publisher['news_category_edit'])) {
				// お知らせカテゴリ複数登録可能の場合
				if($this->news_category_list) {
					$db->assign('news_category_list', implode(",", $this->news_category_list));
					$result = $db->statement('admin/publisher/news/sql/category_list_in.sql');
					$this->news_category_list_in = $db->buildTree($result);
				}
			}

			$this->back = false;
			return;
		}

		$result = $db->statement('admin/publisher/news/sql/current_timestamp.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);
	}
}
?>