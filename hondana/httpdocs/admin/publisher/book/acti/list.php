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
		if(isset($_REQUEST['book_no'])){
			$this->search_book_no = $_REQUEST['book_no'];
		}else{
			$this->search_book_no = NULL;
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

		$result = $db->statement('admin/publisher/book/sql/genre_list.sql');
		$tree = $db->buildTree($result, 'genre_no');
		$this->genreList = $tree;

		$result = $db->statement('admin/publisher/book/sql/series_list.sql');
		$tree = $db->buildTree($result, 'series_no');
		$this->seriesList = $tree;

		$result = $db->statement('admin/publisher/book/sql/label_list.sql');
		$tree = $db->buildTree($result, 'label_no');
		$this->labelList = $tree;

		$result = $db->statement('admin/publisher/book/sql/stock_status_list.sql');
		$tree = $db->buildTree($result, 'stock_status_no');
		$this->stockStatusList = $tree;

		$result = $db->statement('admin/publisher/book/linkage/sql/linkage_list.sql');
		$tree = $db->buildTree($result, 'linkage_no');
		$linkageMaster = $tree;
		$this->linkageList = $tree;

		$db->assign('linkageList', $this->linkageList);

		/* book list */
		if( $this->search_submit || 1){
			$result = $db->statement('admin/publisher/book/linkage/sql/list.sql');
			$tree = $db->buildTree($result, 'book_no');
			$this->bookList = $tree;

			foreach($tree as $key => $value) {
				$actibook = sprintf('%s/publisher/%s/files/actibook/%s', $_SERVER['DOCUMENT_ROOT'], $_SESSION['id'], $key);
				if(file_exists($actibook)) {
					$this->bookList[$key]['actibook'] = true;
				}
			}

			$result = $db->statement('admin/publisher/book/linkage/sql/list_total.sql');
			$row = $db->fetch_assoc($result);
			$total = $row['total'];
			if ($total < ($this->page - 1) * $this->amount) {
				$this->page = ceil($total / $this->amount);
				$db->assign('offset', ($this->page - 1) * $this->amount);
			}
			$paginate = new Paginate();
			$this->pageInfo = $paginate->getPageInfo($this->page, $total, $this->amount, $this->__defaultPageAmount);
		}

		$this->search_isbn = $oldIsbn;
	}
}
?>