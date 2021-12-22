<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if(!$this->news_no){
			$this->__controller->redirectToURL('../');
		}
		$db =& $this->_db;
		$db->assign('news_no', $this->news_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);


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

		/* news category list */
		if(!empty($this->publisher['news_category_edit'])) {
			$this->news_category_list = null;
			// 複数登録可能の場合
			$result = $db->statement('admin/publisher/news/sql/category_multiple_list.sql');
			$tree = $db->buildTree($result, 'news_category_no');
			$this->newsCategoryList = $tree;
			$this->oldNewsCategoryList = $tree;
		} else {
			$result = $db->statement('admin/publisher/news/sql/category_list.sql');
			$tree = $db->buildTree($result, 'news_category_no');
			$this->newsCategoryList = $tree;
		}

		/* news relate list */
		$result = $db->statement('admin/publisher/news/sql/news_relate_list.sql');
		$tree = $db->buildTree($result, 'book_no');
		$this->oldNewsRelateList = $tree;
		$this->viewOldNewsRelateList = $tree;

		$result = $db->statement('admin/publisher/news/sql/news.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

		// WYSIWYG入力フィールド内 ルートパス → フルパスへ変換して表示 (管理画面から画像が参照できるように)
		$this->setFullpath(array('value'),$_SESSION['publisher_url']);
	}
}
?>