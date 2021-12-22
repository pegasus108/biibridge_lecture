<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function validate() {

		if( !isset($_REQUEST['news_category_no']) ){
			$this->news_category_no = NULL;
		}
		if( !isset($_REQUEST['navi_display']) ){
			$this->navi_display = '0';
		}
		if(!empty($_REQUEST['news_relate_list']) && !count($_REQUEST['news_relate_list']) ){
			$this->news_relate_list = NULL;
			$this->oldNewsRelateList = NULL;
		}

		$db =& $this->_db;
		$db->assign('news_no', $this->news_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		/* news category list */
		$result = $db->statement('admin/publisher/news/sql/category_list.sql');
		$tree = $db->buildTree($result, 'news_category_no');
		$this->newsCategoryList = $tree;

		// 関連書籍情報 再取得
		if(!empty($this->news_relate_list)) {
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

		$publicDateString = $this->public_year.$this->public_month.$this->public_day.
			$this->public_hour.$this->public_minute.$this->public_second;

		$this->public_date = $this->public_year.'-'.$this->public_month.'-'.$this->public_day.' '.
			$this->public_hour.':'.$this->public_minute.':'.$this->public_second;

		$this->errors = array();

		if (!$this->name) {
			$this->errors['name'] = 'タイトルの記入をご確認ください。';
		}

		if(!empty($this->publisher['news_category_edit'])) {
			// 複数登録可能の場合
			if (empty($this->news_category_list)) {
				$this->errors['category'] = 'カテゴリの選択をご確認ください。';
			} else {
				// お知らせカテゴリのカテゴリ名取得 (確認画面表示用)
				$db->assign('news_category_list', implode(",", $this->news_category_list));
				$result = $db->statement('admin/publisher/news/sql/category_list_in.sql');
				$this->news_category_list_in = $db->buildTree($result);
			}
		} else {
			if (!$this->news_category_no) {
				$this->errors['category'] = 'カテゴリの選択をご確認ください。';
			}
		}

		if (!$this->value) {
			$this->errors['value'] = '内容の記入をご確認ください。';
		}
		/*if (!$this->public_status) {
			$this->errors['public_status'] = '公開の状態の選択をご確認ください。';
		}*/
		if (!$publicDateString) {
		}elseif(!$this->isDateTime($this->public_date)){
			$this->errors['public_date'] = '公開日時の記入をご確認ください。';
		}elseif(!$this->isDate($this->public_year , $this->public_month , $this->public_day)){
			$this->errors['public_date'] = '公開日付が正しくありません。ご確認ください。';
		}


		if(count($this->errors)) {
			return 'input';
		}else if($publicDateString == ''){
			$this->public_date ='0000-00-00 00:00:00';
		}


		return true;
	}
}
?>