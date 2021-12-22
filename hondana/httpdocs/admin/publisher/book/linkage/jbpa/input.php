<?php
require_once('../../../../../../libdb/AuthAction.php');

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
			'data_type_1',
			'isbn_1',
			'category_1',
			'name_1',
			'kana_1',
			'preliminary_1',
			'sub_1',
			'version_1',
			'preliminary_2',
			'series_1',
			'series_kana_1',
			'preliminary_3',
			'author_1',
			'author_type_1',
			'author_kana_1',
			'author_2',
			'author_type_2',
			'author_kana_2',
			'author_3',
			'author_type_3',
			'author_kana_3',
			'book_date_1',
			'release_date_1',
			'book_size_2',
			'page_1',
			'set_code_1',
			'price_1',
			'price_change_date_1',
			'price_special_1',
			'price_special_policy_1',
			'preliminary_4',
			'preliminary_5',
			'publisher_1',
			'publisher_2',
			'preliminary_6',
			'out_status_1',
			'out_date_1',
			'preliminary_7',
			'trade_code_1'
		);

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		/* series list */
		$result = $db->statement('admin/publisher/book/linkage/jbpa/sql/series_list.sql');
		$tree = $db->buildTree($result, 'series_no');
		$this->seriesList = $tree;

		/* author list */
		$result = $db->statement('admin/publisher/book/linkage/jbpa/sql/author_list.sql');
		$tree = $db->buildTree($result, 'author_no');
		$this->authorList = $tree;

		/* author type list */
		$result = $db->statement('admin/publisher/book/linkage/jbpa/sql/author_type_list.sql');
		$tree = $db->buildTree($result, 'author_type_no');
		$this->author_typeList = $tree;

		$result = $db->statement('admin/publisher/book/linkage/jbpa/sql/book.sql');
		$row = $db->fetch_assoc($result);
		$this->book = $row;

		/* build new */
		$result = $db->statement('admin/publisher/book/linkage/jbpa/sql/oldBook.sql');
		$row = $db->fetch_assoc($result);
//		$row['preliminary_5'] = split(',',$row['preliminary_5']);
		$this->setProperties($row);

		// 書籍データ
		$result = $db->statement('admin/publisher/book/linkage/jbpa/sql/myBook.sql');
		$row = $db->fetch_assoc($result);
		$row = $this->nullClear($row);
		$myBook = $row;

		// データ連携用データ
		$result = $db->statement('admin/publisher/book/linkage/jbpa/sql/shareBookField.sql');
		$tree = $db->buildTree($result, array('table_id','field_id'));
		$lastFieldList = $tree;

		$shareBook = array();
		foreach($lastFieldList as $key => $tbl){
			$db->assign('fieldList', array_keys($tbl));
			$db->assign('table_id', $key);
			$result = $db->statement('admin/publisher/book/linkage/jbpa/sql/shareBook.sql');
			$row = $db->fetch_assoc($result);
//			$row['preliminary_5'] = split(',',$row['preliminary_5']);
			$row = $this->nullClear($row);
			$shareBook = array_merge($shareBook, $row);
		}

		$this->shareBook = $shareBook; // データ連携用データ
		$this->myBook = $myBook; // 書籍データ
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