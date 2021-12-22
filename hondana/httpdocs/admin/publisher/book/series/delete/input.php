<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
		$this->hasChildList = array();
		$this->useBookList = array();
	}
	function execute() {
		if(!$this->series_no_list){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->series_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('series_no_list', $this->series_no_list);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/series/sql/delete_list.sql');
		$tree = $db->buildTree($result, 'series_no');
		$this->deleteList = $tree;

		$this->series_no_list = array();
		foreach($this->deleteList as $key => $val){
			$this->series_no_list[] = $val['series_no'];
		}
		$listString = join($this->series_no_list,',');
		$db->assign('listString', $listString);
		$db->assign('series_no_list', $this->series_no_list);

		$result = $db->statement('admin/publisher/book/series/sql/has_child.sql');
		$tree = $db->buildTree($result, 'series_no');
		$this->hasChildList = $tree;

		$result = $db->statement('admin/publisher/book/series/sql/use_book.sql');
		$tree = $db->buildTree($result, 'series_no');
		$this->useBookList = $tree;

		$this->delete = false;
		if(count($this->hasChildList) == 0 || 1){
			$this->delete = true;
		}
	}
}
?>