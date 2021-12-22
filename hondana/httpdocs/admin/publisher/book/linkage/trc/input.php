<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {

		if($this->back == true){
			$this->back = false;
			return;
		}

		$book_no = $this->book_no;
		$this->clearProperties();

		$this->book_no = $book_no;
		$this->fieldList = array(
			'name_1', 'kana_1',
			'sub_1',
			'series_1',
			'volume_1',
			'author_1', 'author_2',
			'author_kana_1', 'author_kana_2',
			'author_note_1', 'author_note_2',
			'isbn_1',
			'price_1',
			'genre_1', 'genre_2',
			'book_size_1',
			'page_1',
			'content_1',
			'version_release_1',
			'price_special_1', 'price_special_policy_1',
			'release_date_1',
			'magazine_code_1',
			'publisher_2',
			'publisher_kana_2',
			'issuer_1',
			'issuer_kana_1',
			'sub_kana_1',
			'circulation_1',
			'typist_1',
			'typist_tel_1',
			'type_date_1',
			'explain_1',
			'distribution_type_1',
			'order_status_1',
			'target_1',
			'rubi_status_1',
			'note_1',
			'win_info_1',
			'reader_page_status_1',
			'reader_page_1',
			'unaccompanied_status_1',
			'by_format_1',
			'by_obi_1',
			'representative_editor_1',
			'representative_comment_1',
			'conflicts_1',
			'appendices_status_1',
			'appendices_type_1',
			'appendices_other_1',
			'appendices_loan_1',
			'appendix_1'
		);

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		/* series list */
		$result = $db->statement('admin/publisher/book/linkage/trc/sql/series_list.sql');
		$tree = $db->buildTree($result, 'series_no');
		$this->seriesList = $tree;

		/* author list */
		$result = $db->statement('admin/publisher/book/linkage/trc/sql/author_list.sql');
		$tree = $db->buildTree($result, 'author_no');
		$this->authorList = $tree;

		$result = $db->statement('admin/publisher/book/linkage/trc/sql/book.sql');
		$row = $db->fetch_assoc($result);
		$this->book = $row;

		/* build new */
		$result = $db->statement('admin/publisher/book/linkage/trc/sql/oldBook.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

		$result = $db->statement('admin/publisher/book/linkage/trc/sql/myBook.sql');
		$row = $db->fetch_assoc($result);
		$row = $this->nullClear($row);
		$myBook = $row;

		$result = $db->statement('admin/publisher/book/linkage/trc/sql/shareBookField.sql');
		$tree = $db->buildTree($result, array('table_id','field_id'));
		$lastFieldList = $tree;

		$shareBook = array();
		foreach($lastFieldList as $key => $tbl){
			$db->assign('fieldList', array_keys($tbl));
			$db->assign('table_id', $key);
			$result = $db->statement('admin/publisher/book/linkage/trc/sql/shareBook.sql');
			$row = $db->fetch_assoc($result);
			$row = $this->nullClear($row);
			$shareBook = array_merge($shareBook, $row);
		}

		$this->shareBook = $shareBook;
		$this->myBook = $myBook;
		$this->new = array_merge($shareBook,$myBook);
	}
	function nullClear($array){/*
		foreach($array as $k => $v){
			if($v == '')
				unset($array[$k]);
			if(is_array($v))
				$array[$k] = $this->nullClear($v);
		}*/
		return $array;
	}

}
?>