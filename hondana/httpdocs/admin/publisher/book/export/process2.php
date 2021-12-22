<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function execute() {
		$page = "_{$this->page}";
		if(!isset($_REQUEST["page"])){
			$this->page = 1;
			$page = "";
		}

		$csvFileName = "book_list{$page}.csv";
		$pagesize = 500;


		ob_end_clean();

		header("Content-Type: application/octet-stream");
		header("Content-disposition: attachment; filename=".$csvFileName);
		$this->printHeader();
		flush();

		$db =& $this->_db;
		$offset = ($this->page - 1) * $pagesize;
		$db->assign('offset', $offset);
		$db->assign('pagesize', $pagesize);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		/* book list */
		$rs = $db->statement('admin/publisher/book/sql/export.sql');
		while ($row = $db->fetch_assoc($rs)){
			$this->printOneRow($row);
			flush();
		}

		exit();
		/* HTTPヘッダ */
	}


	function printHeader(){
		$o = "書名,書名カナ,巻次,サブタイトル,サブタイトルカナ,ISBN,雑誌コード,Cコード,出版年月日,書店発売日,版,版型,ページ数,本体価格,概要長文,概要短文,目次,内容説明,公開日,同一公開日付表示順,公開の状態,新刊設定,キーワード,これから出る本設定,おすすめ設定,おすすめ紹介文,おすすめ表示順,在庫設定,カート設定,ジャンル,シリーズ,著者名1,著者カナ1,著者タイプ1,著者名2,著者カナ2,著者タイプ2,著者名3,著者カナ3,著者タイプ3,著者名4,著者カナ4,著者タイプ4,著者名5,著者カナ5,著者タイプ5,著者名6,著者カナ6,著者タイプ6,著者名7,著者カナ7,著者タイプ7,著者名8,著者カナ8,著者タイプ8,著者名9,著者カナ9,著者タイプ9,著者名10,著者カナ10,著者タイプ10\r\n";
		echo $o;
	}

	function printOneRow($book){
		foreach ($book as $k=> $v){
			$book[$k] = str_replace('"', '""', $v);
			if( strpos($k, "author_name") !== false ){
				$book[$k] = str_replace('^','","',$book[$k]);
				if( !preg_match('/^(.*?)","(.*?)","(.*?)$/',$book[$k]) ){
					$book[$k] = '","","';
				}
			}
		}

		$o .= '"'.$book['name'].'",';
		$o .= '"'.$book['kana'].'",';
		$o .= '"'.$book['volume'].'",';
		$o .= '"'.$book['sub_name'].'",';
		$o .= '"'.$book['sub_kana'].'",';
		$o .= '"'.$book['isbn'].'",';
		$o .= '"'.$book['magazine_code'].'",';
		$o .= '"'.$book['c_code'].'",';

		if($book['fb_date']=='0000-00-00')
			$o .= '"未設定",';
		else
			$o .= '"'.$book['fb_date'].'",';

		if($book['fr_date']=='0000-00-00')
			$o .= '"未設定",';
		else
			$o .= '"'.$book['fr_date'].'",';

		$o .= '"'.$book['version'].'",';
		$o .= '"'.$book['book_size_name'].'",';
		$o .= '"'.$book['page'].'",';
		$o .= '"'.$book['price'].'",';
		$o .= '"'.$book['outline'].'",';
		$o .= '"'.$book['outline_abr'].'",';
		$o .= '"'.$book['explain'].'",';
		$o .= '"'.$book['content'].'",';

		if($book['public_date']=='0000-00-00 00:00:00')
			$o .= '"未設定",';
		else
			$o .= '"'.$book['public_date'].'",';

		if($book['public_date_order']=='2147483647')
			$o .= '"指定なし",';
		else
			$o .= '"'.$book['public_date_order'].'",';

		if(!$book['public_status'])
			$o .= '"非公開",';
		else
			$o .= '"公開",';

		if(!$book['new_status'])
			$o .= '"非公開",';
		else
			$o .= '"公開",';

		$o .= '"'.$book['keyword'].'",';

		if(!$book['next_book'])
			$o .= '"これから出る本ではない",';
		else
			$o .= '"これから出る本",';

		if(!$book['recommend_status'])
			$o .= '"非おすすめ",';
		else
			$o .= '"おすすめ",';

		$o .= '"'.$book['recommend_sentence'].'",';

		if($book['recommend_order']=='2147483647')
			$o .= '"指定なし",';
		else
			$o .= '"'.$book['recommend_order'].'",';

		$o .= '"'.$book['stock_status_name'].'",';

		if(!$book['cart_status'])
			$o .= '"カート無効",';
		else
			$o .= '"カート有効",';

		$o .= '"'.$book['genre_name'].'",';
		$o .= '"'.$book['series_name'].'",';
		$o .= '"'.$book['author_name1'].'",';
		$o .= '"'.$book['author_name2'].'",';
		$o .= '"'.$book['author_name3'].'",';
		$o .= '"'.$book['author_name4'].'",';
		$o .= '"'.$book['author_name5'].'",';
		$o .= '"'.$book['author_name6'].'",';
		$o .= '"'.$book['author_name7'].'",';
		$o .= '"'.$book['author_name8'].'",';
		$o .= '"'.$book['author_name9'].'",';
		$o .= '"'.$book['author_name10'].'"'."\r\n";

		echo $o;
	}

}
?>