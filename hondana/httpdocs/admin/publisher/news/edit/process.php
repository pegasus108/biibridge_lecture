<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
//		$_REQUEST['debug'] = true;

		$this->deleteList = array();
		$this->insertList = array();
		$this->updateList = array();

		// 変更・削除する関連書籍をチェック
		foreach($this->oldNewsRelateList as $oldkey => $oldNewsRelate){
			$add = true;
			foreach($this->news_relate_list as $key => $newsRelate){
				if($oldNewsRelate['book_no'] == $newsRelate){
					$add = false;
					if(empty($oldNewsRelate['order']) || $oldkey != $key) {
						$b = array();
						$b['id'] = $newsRelate;
						$b['order'] = $key + 1;
						array_push($this->updateList, $b);
					}
					break;
				}
			}
			if($add){
				array_push($this->deleteList, $oldNewsRelate['news_relate_no']);
			}
		}

		// 追加する関連書籍をチェック
		foreach($this->news_relate_list as $key => $newsRelate){
			$add = true;
			foreach($this->oldNewsRelateList as $oldNewsRelate){
				if($newsRelate == $oldNewsRelate['book_no']){
					$add = false;
					break;
				}
			}
			if($add){
				$b = array();
				$b['id'] = $newsRelate;
				$b['order'] = $key + 1;
				array_push($this->insertList, $b);
			}
		}

		// WYSIWYG入力フィールド内 フルパス → ルートパスへ変換して保存 (SSLページでエラーにならないように)
		$this->unsetFullpath(array('value'),$_SESSION['publisher_url']);

		// お知らせカテゴリ複数登録可能の場合
		if(!empty($this->publisher['news_category_edit'])) {
			$this->deleteNewsCategoryList = array();
			$this->insertNewsCategoryList = array();

			// 削除するカテゴリの確認
			foreach($this->oldNewsCategoryList as $oldNewsCategory){
				$delete = true;
				foreach($this->news_category_list as $v){
					if($oldNewsCategory['news_category_no'] == $v){
						$delete = false;
						break;
					}
				}
				if($delete){
					array_push($this->deleteNewsCategoryList, $oldNewsCategory['news_news_category_no']);
				}
			}

			// 追加するカテゴリの確認
			foreach($this->news_category_list as $v){
				$add = true;
				foreach($this->oldNewsCategoryList as $oldNewsCategory){
					if($v == $oldNewsCategory['news_category_no']){
						$add = false;
						break;
					}
				}
				if($add){
					array_push($this->insertNewsCategoryList, $v);
				}
			}

			if(empty($this->news_category_no)) {
				// 1つめのカテゴリを設定
				$this->news_category_no = $this->news_category_list[0];
			}
		}

		$db =& $this->_db;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->begin();
		$db->statement('admin/publisher/news/sql/update.sql');
		$db->statement('admin/publisher/news/sql/update_news_relate.sql');
		if(!empty($this->publisher['news_category_edit'])) {
			// お知らせカテゴリ複数登録可能の場合
			$db->statement('admin/publisher/news/sql/update_news_category.sql');
		}
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
