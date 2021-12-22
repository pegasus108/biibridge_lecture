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

		$this->defaultOrder = 'public_date_desc';
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

		/* company category list */
		if( !$this->search_submit ){
			$result = $db->statement('admin/publisher/company/sql/category_list.sql');
			$tree = $db->buildTree($result, 'company_category_no');
			$this->companyCategoryList = $tree;
		}

		/* company list */
		if( $this->search_submit || 1){
			$result = $db->statement('admin/publisher/company/sql/list.sql');

			$tree = $db->buildTree($result, 'company_no');
			$this->companyList = $tree;

			//facility
			$result = $db->statement('admin/publisher/company/sql/list_total.sql');
			$row = $db->fetch_assoc($result);
			$total = $row['total'];
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