<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
		$this->hasChildList = array();
		$this->useNewsList = array();
	}
	function execute() {
		if(!$this->company_category_no_list){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->company_category_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('company_category_no_list', $this->company_category_no_list);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/company/category/sql/delete_list.sql');
		$tree = $db->buildTree($result, 'company_category_no');
		$this->deleteList = $tree;

		$this->company_category_no_list = array();
		foreach($this->deleteList as $key => $val){
			$this->company_category_no_list[] = $val['company_category_no'];
		}
		$listString = join($this->company_category_no_list,',');
		$db->assign('listString', $listString);
		$db->assign('company_category_no_list', $this->company_category_no_list);

		$result = $db->statement('admin/publisher/company/category/sql/has_child.sql');
		$tree = $db->buildTree($result, 'company_category_no');
		$this->hasChildList = $tree;

		$result = $db->statement('admin/publisher/company/category/sql/use_company.sql');
		$tree = $db->buildTree($result, 'company_category_no');
		$this->useNewsList = $tree;

		$this->delete = false;
		if(count($this->hasChildList) == 0 && count($this->useNewsList) == 0 || 1){
			$this->delete = true;
		}
	}
}
?>