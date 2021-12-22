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
			'data_type_2',
			'send_type_1',
			'source_type_1',
			'source_code_1',
			'to_type_1',
			'to_code_1',
			'c_stamp_1',
			'record_type_1',
			'cancell_type_1',
			'category_code_1',
			'trade_code_1',
			'trade_code_branch_1',
			'issuer_1',
			'issuer_kana_1',
			'publisher_2',
			'publisher_kana_2',
			'handling_company_1',
			'handling_type_1',
			'series_1',
			'series_kana_1',
			'series_volume_1',
			'sub_series_1',
			'sub_series_kana_1',
			'sub_series_volume_1',
			'total_volume_1',
			'total_other_volume_1',
			'distribution_count_1',
			'name_1',
			'kana_1',
			'volume_2',
			'sub_1',
			'sub_kana_1',
			'sub_volume_1',
			'end_1',
			'present_volume_1',
			'author_1',
			'author_kana_1',
			'author_type_1',
			'author_2',
			'author_kana_2',
			'author_type_2',
			'author_3',
			'author_kana_3',
			'author_type_3',
			'content_1',
			'content_2',
			'preliminary_5',
			'book_size_2',
			'book_size_3',
			'page_1',
			'bound_1',
			'release_date_1',
			'return_date_1',
			'notation_price_1',
			'price_1',
			'price_tax_1',
			'price_special_1',
			'price_special_tax_1',
			'price_special_policy_1',
			'distribution_type_1',
			'distribut_1',
			'isbn_1',
			'category_1',
			'magazine_code_2',
			'magazine_code_1',
			'adult_1',
			'pre_order_1',
			'order_status_1',
			'circulation_1',
			'fix_1',
			'typist_1',
			'typist_tel_1',
			'type_date_1',
			'edit_time_stamp_1',
			'win_info_1'
		);

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		/* series list */
		$result = $db->statement('admin/publisher/book/linkage/commissioner/sql/series_list.sql');
		$tree = $db->buildTree($result, 'series_no');
		foreach($tree as $k => $v)
			$tree[$k]['kana'] = $this->getHKana($v['kana']);
		$this->seriesList = $tree;

		/* author list */
		$result = $db->statement('admin/publisher/book/linkage/commissioner/sql/author_list.sql');
		$tree = $db->buildTree($result, 'author_no');
		foreach($tree as $k => $v)
			$tree[$k]['kana'] = $this->getHKana($v['kana']);
		$this->authorList = $tree;

		/* author type list */
		$result = $db->statement('admin/publisher/book/linkage/commissioner/sql/author_type_list.sql');
		$tree = $db->buildTree($result, 'author_type_no');
		$this->author_typeList = $tree;

		$result = $db->statement('admin/publisher/book/linkage/commissioner/sql/book.sql');
		$row = $db->fetch_assoc($result);
		$this->book = $row;

		/* build new */
		$result = $db->statement('admin/publisher/book/linkage/commissioner/sql/oldBook.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

		$result = $db->statement('admin/publisher/book/linkage/commissioner/sql/myBook.sql');
		$row = $db->fetch_assoc($result);
		$row = $this->nullClear($row);
		$myBook = $row;

		$result = $db->statement('admin/publisher/book/linkage/commissioner/sql/shareBookField.sql');
		$tree = $db->buildTree($result, array('table_id','field_id'));
		$lastFieldList = $tree;

		$shareBook = array();
		foreach($lastFieldList as $key => $tbl){
			$db->assign('fieldList', array_keys($tbl));
			$db->assign('table_id', $key);
			$result = $db->statement('admin/publisher/book/linkage/commissioner/sql/shareBook.sql');
			$row = $db->fetch_assoc($result);
			$row = $this->nullClear($row);
			$shareBook = array_merge($shareBook, $row);
		}

		$this->shareBook = $shareBook;
		$this->myBook = $myBook;
		$new = array_merge($shareBook,$myBook);

		if(preg_match('/x/u',$new['book_size_2'])){
//			$new['book_size_3_l'] = preg_replace('/^([0-9]+?)x([0-9]+?)([^0-9]*?)$/ui','$1',$new['book_size_2']);
//			$new['book_size_3_r'] = preg_replace('/^([0-9]+?)x([0-9]+?)([^0-9]*?)$/ui','$2',$new['book_size_2']);
			$new['book_size_2'] = '';
		}elseif(preg_match('/x/u',$new['book_size_3'])){
			$new['book_size_3_l'] = preg_replace('/^([0-9]+?)x([0-9]+?)([^0-9]*?)$/ui','$1',$new['book_size_3']);
			$new['book_size_3_r'] = preg_replace('/^([0-9]+?)x([0-9]+?)([^0-9]*?)$/ui','$2',$new['book_size_3']);
		}

		$new['issuer_kana_1'] = $this->getHKana($new['issuer_kana_1']);
		$new['publisher_kana_2'] = $this->getHKana($new['publisher_kana_2']);
		$new['kana_1'] = $this->getHKana($new['kana_1']);
		$new['sub_kana_1'] = $this->getHKana($new['sub_kana_1']);
		$new['sub_kana_1'] = $this->getHKana($new['sub_kana_1']);

		if($new['price_1'])
			$new['price_tax_1'] = floor($new['price_1'] * 1.08 + 0.5);
		if($new['price_special_1'])
			$new['price_special_tax_1'] = floor($new['price_special_1'] * 1.08 + 0.5);
		$new['isbn_1'] = preg_replace('/-/u','',$new['isbn_1']);
		if(preg_match('/-/u',$new['magazine_code_1']))
			$new['magazine_code_1'] = preg_replace('/[-\/]/u','',$new['magazine_code_1']);

		$new['release_date_1'] = mb_convert_kana($new['release_date_1'],'N');

		$this->new = $new;
	}

	function getHKana($s){
		return mb_ereg_replace('(\(|\)|（|）|．|\.|，|,|・|･|-|=|＝|[a-zａ-ｚＡ-ＺA-Z]|[0-9０-９]|ー|　| )+','', mb_convert_kana($s,'k'));
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