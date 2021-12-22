<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function init(){
		parent::init();

		$this->deleteList = array();
	}
	function execute() {
		if(!$this->book_no_list || !$this->add_genre_no){
			$this->__controller->redirectToURL('../');
		}


		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('genre_no', $this->add_genre_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/sql/book_list.sql');
		$tree = $db->buildTree($result, 'book_no');
		$this->bookList = $tree;

		$result = $db->statement('admin/publisher/book/sql/public_status.sql');
		$tree = $db->buildTree($result, 'book_no');
		$this->publicList = $tree;

		$result = $db->statement('admin/publisher/book/sql/genre.sql');
		$row = $db->fetch_assoc($result);
		$this->genreName = $row['name'];
	}
}
?>