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
		if(!$this->genre_no_list){
			$this->__controller->redirectToURL('../');
		}

		$listString = join($this->genre_no_list,',');

		$db =& $this->_db;
		$db->assign('listString', $listString);
		$db->assign('genre_no_list', $this->genre_no_list);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/genre/sql/delete_list.sql');
		$tree = $db->buildTree($result, 'genre_no');
		$this->deleteList = $tree;

		$this->genre_no_list = array();
		foreach($this->deleteList as $key => $val){
			$this->genre_no_list[] = $val['genre_no'];
		}
		$listString = join($this->genre_no_list,',');
		$db->assign('listString', $listString);
		$db->assign('genre_no_list', $this->genre_no_list);

		$result = $db->statement('admin/publisher/book/genre/sql/has_child.sql');
		$tree = $db->buildTree($result, 'genre_no');
		$this->hasChildList = $tree;

		$result = $db->statement('admin/publisher/book/genre/sql/use_book.sql');
		$tree = $db->buildTree($result, 'genre_no');
		$this->useBookList = $tree;

		$this->delete = false;
		if(count($this->hasChildList) == 0 || 1){
			$this->delete = true;
		}
	}
}
?>