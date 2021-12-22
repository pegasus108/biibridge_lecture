<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Paginate.php');

class Action extends AuthAction {

    var $sqlPath = 'admin/admin/publisher/sql/';

	function init() {
		parent::init();

		$this->__defaultPage = 1;
		$this->__defaultAmount = 10;
		$this->__defaultPageAmount = 5;

		$this->defaultOrder = 'c_stamp_desc';
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
		$db->setSqlDirectoryPath($this->sqlPath);
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign($this->getProperties());

		if ($this->amount) {
			$db->assign('limit', $this->amount);
			$db->assign('offset', ($this->page - 1) * $this->amount);
		}

		/* publisher list */
		if($this->search_submit || 1){
			$this->publisherList = $db->statementTree('list', 'publisher_no');

			//facility
			$row = $db->statementFetch('list_total');
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