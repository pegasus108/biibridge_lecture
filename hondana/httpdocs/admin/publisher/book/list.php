<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Paginate.php');

class Action extends AuthAction {

	function init() {
		parent::init();

		if(!(isset($_REQUEST['search_submit']) || isset($_REQUEST['page']) || isset($_REQUEST['order']))){
			$this->clearProperties();
		}
		$this->__defaultPage = 1;
		$this->__defaultAmount = 10;
		$this->__defaultPageAmount = 5;
		$this->defaultOrder = 'book_date_desc';

		$this->jpoSyncTime = "6";
	}

	function prepare() {
		if (!$this->page || isset($_REQUEST['amount'])) {
			$this->page = $this->__defaultPage;
		}
		if (!isset($this->amount)) {
			$this->amount = $this->__defaultAmount;
		}
		if(!isset($this->order)) {
			$this->order = $this->defaultOrder;
		}
	}

	function execute() {
		$oldIsbn = $this->search_isbn;
		$this->search_isbn = str_replace('-','',$this->search_isbn);

		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());

		if ($this->amount) {
			$db->assign('limit', $this->amount);
			$db->assign('offset', ($this->page - 1) * $this->amount);
		}

		/*list */

		$result = $db->statement('admin/publisher/book/sql/publisher.sql');
		$row = $db->fetch_assoc($result);
		$this->publisher = $row;

		$result = $db->statement('admin/publisher/book/sql/label_list.sql');
		$tree = $db->buildTree($result, 'label_no');
		$this->labelList = $tree;

		$result = $db->statement('admin/publisher/book/sql/genre_list.sql');
		$tree = $db->buildTree($result, 'genre_no');
		$this->genreList = $tree;

		$result = $db->statement('admin/publisher/book/sql/series_list.sql');
		$tree = $db->buildTree($result, 'series_no');
		$this->seriesList = $tree;

		$result = $db->statement('admin/publisher/book/sql/stock_status_list.sql');
		$tree = $db->buildTree($result, 'stock_status_no');
		$this->stockStatusList = $tree;

		$jpoSyncTime = $this->makeJpoSyncTime($this->jpoSyncTime);
		$db->assign('jpoSyncTime', $jpoSyncTime);

		/* book list */
		if( $this->search_submit || 1){
			// 検索サイト分岐処理
			if($_SESSION['role_no'] == 3) {
				// アカウント1
				$this->sites = null;
				$this->sites[] = 1;
			} elseif($_SESSION['role_no'] == 4) {
				// アカウント2
				$this->sites = null;
				$this->sites[] = 2;
			} elseif($_SESSION['role_no'] == 5) {
				// アカウント3
				$this->sites = null;
				$this->sites[] = 3;
			}
			$db->assign('sites',$this->sites);

			$result = $db->statement('admin/publisher/book/sql/list.sql');
			$tree = $db->buildTree($result, 'book_no');
			$this->bookList = $tree;
			if(!empty($tree)) {
				$db->assign('bookNoList', array_keys($tree));
			}

			$result = $db->statement('admin/publisher/book/sql/list_total.sql');
			$row = $db->fetch_assoc($result);
			$total = $row['total'];
			if ($total < ($this->page - 1) * $this->amount) {
				$this->page = ceil($total / $this->amount);
				$db->assign('offset', ($this->page - 1) * $this->amount);
			}
			$paginate = new Paginate();
			$this->pageInfo = $paginate->getPageInfo($this->page, $total, $this->amount, $this->__defaultPageAmount);

			/* book join list */
			$result = $db->statement('admin/publisher/book/sql/book_label_list.sql');
			$tree = $db->buildTree($result, 'book_label_no');
			$this->bookLabelList = $tree;

			$result = $db->statement('admin/publisher/book/sql/book_genre_list.sql');
			$tree = $db->buildTree($result, 'book_genre_no');
			$this->bookGenreList = $tree;

			$result = $db->statement('admin/publisher/book/sql/book_series_list.sql');
			$tree = $db->buildTree($result, 'book_series_no');
			$this->bookSeriesList = $tree;

			/* 著者を表示しない為、不要
			$result = $db->statement('admin/publisher/book/sql/opus_list.sql');
			$tree = $db->buildTree($result, 'opus_no');
			$this->opusList = $tree;
			*/
		}

		$this->search_isbn = $oldIsbn;
	}

	function makeJpoSyncTime($time){
		$targetDate = time();
		$returnDate="";
		if(date("G") >= $time){
			$targetDate += 60 * 60 * 24;
			$returnDate = date("Y-m-d 06:00:00",$targetDate);

		}else{
			$targetDate += 60 * 60 * 24;
			$returnDate = date("Y-m-d 06:00:00");

		}

		return $returnDate;

	}

}
?>