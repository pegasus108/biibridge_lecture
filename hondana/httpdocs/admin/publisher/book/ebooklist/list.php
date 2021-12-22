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

		$this->defaultOrder = 'created_desc';
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
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());

		if ($this->amount) {
			$db->assign('limit', $this->amount);
			$db->assign('offset', ($this->page - 1) * $this->amount);
		}

		/* yondemill list */
		if( $this->search_submit || 1){
			/* publisher */
			$result = $db->statement('admin/publisher/book/sql/publisher.sql');
			$row = $db->fetch_assoc($result);
			$this->publisher = $row;

			// 試し読み一覧取得
			$result = $db->statement('admin/publisher/book/ebooklist/sql/get_yondemill_list.sql');
			$bookList = $db->buildTree($result);
			$this->bookList = $bookList;

			// 試し読み総数取得
			$result = $db->statement('admin/publisher/book/sql/list_total.sql');
			$row = $db->fetch_assoc($result);
			$total = $row['total'];

			// ページネーター設定
			if ($total < ($this->page - 1) * $this->amount) {
				$this->page = ceil($total / $this->amount);
				$db->assign('offset', ($this->page - 1) * $this->amount);
			}
			$paginate = new Paginate();
			$this->pageInfo = $paginate->getPageInfo($this->page, $total, $this->amount, $this->__defaultPageAmount);
		}
	}

}
?>