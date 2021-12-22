<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	/**
	*
	* @var csvEncode type String
	*  set charset for input csv file's encode
	*  that 'UTF-8' or 'SJIS-win'
	*
	*/
	var $csvEncode = "SJIS-win";

	function prepare() {
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/admin/images/tmp',
			$siteroot
		);

		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);

		$up =& $this->_uploader;
		$key = 'tsv';
		if ($up->exists($key)) {
			$temp_path = $up->getTemporaryPath($key);

			setlocale(LC_ALL,'ja_JP');
			$row = 1;
			$handle = fopen($temp_path, "r");

			$bookList = array();
			$isbnList = array();
			while (($data = $this->fgetcsv_reg($handle)) !== false) {
				if(join('',$data)) {
					$bookList[] = $data;
				}
			}
			fclose($handle);

			if(count($bookList)){

				foreach($bookList as $key => $val){
					// 行の繰り返し
					$baseCount = 0;
					foreach($bookList[$key] as $key2 => $val2){
						// 項目の繰り返し

						// 0: 書名 1: 書名カナ 2: 巻次 3: サブタイトル 4: サブタイトルカナ 5: ISBN 6: 雑誌コード 7: Cコード 8: 出版年月日 9: 書店発売日 10: 版
						// 11: 版型 12: ページ数 13: 本体価格 14: 概要長文 15: 概要短文 16: 目次 17: 内容説明 18: 公開日 19: 同一公開日付表示順 20: 公開の状態
						// 21: 新刊設定 22: キーワード 23: これから出る本設定 24: おすすめ設定 25: おすすめ紹介文 26: おすすめ表示順 27: 在庫設定 28: カート設定 29: ジャンル 30: シリーズ
						// 31: 電子書籍設定 32: フォーマット紐付け対象ISBN 33: 自分のフォーマット 34: 紐付け対象のフォーマット 35: 著者名1 36: 著者カナ1 37: 著者タイプ1 38: 著者名2 39: 著者カナ2 40: 著者タイプ2
						// 41: 著者名3 42: 著者カナ3 43: 著者タイプ3 44: 著者名4 45: 著者カナ4 46: 著者タイプ4 47: 著者名5 48: 著者カナ5 49: 著者タイプ5 50: 著者名6
						// 51: 著者カナ6 52: 著者タイプ6 53: 著者名7 54: 著者カナ7 55: 著者タイプ7 56: 著者名8 57: 著者カナ8 58: 著者タイプ8 59: 著者名9 60: 著者カナ9
						// 61: 著者タイプ9 62: 著者名10 63: 著者カナ10 64: 著者タイプ10

						$bookList[$key][$key2] = mb_ereg_replace('(^[ |　|"]*)|([ |　|"]*$)','',$bookList[$key][$key2]);
						$bookList[$key][$key2] = mb_ereg_replace('(^")|("$)','',$bookList[$key][$key2]);
						$bookList[$key][$key2] = mb_ereg_replace('(^[ |　|"]*)|([ |　|"]*$)','',$bookList[$key][$key2]);

						// 複数カテゴリの分割（|区切り）
						// 29: ジャンル 30: シリーズ
						if($key2 == 29 || $key2 ==30){
							$v = trim($val2);
							if(!empty($v)) {
								// 「|」で分割
								$array = explode('|',$bookList[$key][$key2]);
								// 同じジャンルを指定した場合は削除
								$array = array_unique($array);

								$bookList[$key][$key2] = $array;
							} else {
								$bookList[$key][$key2] = null;
							}
						}
						// 31: 電子書籍設定
						if($key2 ==31){
							if(trim($val2) == '設定') {
								// 「設定」という文字列が入力されている
								$bookList[$key][$key2] = true;
							} else {
								$bookList[$key][$key2] = false;
							}
						}
						// 著者部分の処理 3列ごとに著者の配列にして格納
						if($key2 >= 35 && $key2 < 45){
							$author = array(3);
							$author = array($bookList[$key][$key2 + $baseCount],$bookList[$key][$key2+$baseCount+1],$bookList[$key][$key2+$baseCount+2]);
							$baseCount += 2;
							$bookList[$key][$key2] = $author;
						}
					}

					if($val[0]) {
						// ISBNの重複チェック用
						$isbnList[$val[5]] = $key;
					}
				}
				// 著者部分 配列に変換した残りの行を削除
				foreach($bookList as $key => $val){
					if(count($val) > 44) {
						for($i = 0; $i < count($val) - 45; $i++) {
							array_pop($bookList[$key]);
						}
					}
				}
				$this->bookList = $bookList;
				$this->isbnList = $isbnList;
			}else{
				return false;
			}
		}

	}

	function execute() {
	}

	function validate() {
		// 入力データの検証開始
		$startTime = microtime(true);
		$this->log('[import]Start validation.');

		$db =& $this->_db;

		$this->error = array();
		$this->rowCount=0;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		// 著者タイプリスト取得
		$result = $db->statement('admin/publisher/book/sql/author_type_list.sql');
		$tree = $db->buildTree($result, 'author_type_no');
		$this->authorTypeList = $tree;

		// 在庫ステータスのリスト取得
		$result = $db->statement('admin/publisher/book/sql/stock_status_list.sql');
		$tree = $db->buildTree($result, 'stock_status_no');
		$this->stockStatusList = $tree;

		// 判型のリスト取得
		$result = $db->statement('admin/publisher/book/sql/book_size_list.sql');
		$tree = $db->buildTree($result, 'book_size_no');
		$this->bookSizeList = $tree;

		// 現在設定されているオススメ書籍数
		$result = $db->statement('admin/publisher/book/sql/recommend_count.sql');
		$row = $db->fetch_assoc($result);
		$this->recommend_count = $row['recommend_count'];

		foreach ($this->bookList as $key => $val){
			if(!($val[0]=='書名' && $val[1]=='書名カナ' && $val[2]=='巻次')){
				// csvのヘッダじゃない（データのとき）

				if (!$val[0]) {
					$this->error[] = ($key + 1) . '行目の書名がありません。 書名の記入をお願いいたします。';
				}

				if (!$val[1]) {
				}elseif(!$this->isKatakana($val[1])){
					$this->error[] = ($key + 1) . '行目の書名カナが全角カタカナではありません。 書名カナの記入は全角カタカナでお願いいたします。';
				}

				if (!$val[4]) {
				}elseif(!$this->isKatakana($val[4])){
					$this->error[] = ($key + 1) . '行目のサブタイトルカナが全角カタカナではありません。 サブタイトルカナの記入は全角カタカナでお願いいたします。';
				}

				if (!$val[5]) {
				}elseif(!preg_match('/^(([0-9]{9}[0-9X]{1})|([0-9]{12}[0-9X]{1}))$/ui', $val[5])){
					$this->error[] = ($key + 1) . '行目のISBNの形式が正しくありません。 ISBNの記入は半角10桁か13桁でお願いいたします。';
				}else{
					if($this->isbnList[$val[5]] && $key != $this->isbnList[$val[5]]){
						$this->error[] = ($key + 1) . '行目のISBNと' . ($this->isbnList[$val[5]] + 1) . '行目のISBNが重複しています。 同じ書誌を同時に登録することはできません。';
					}
					$db->assign('isbn', $val[5]);
					$isbnRow = $db->fetch_assoc($db->statement('admin/publisher/book/sql/book_isbn.sql'));
					if(false !== $isbnRow){
						$this->error[] = ($key + 1) . '行目のISBNはすでに登録されている書誌のISBNを使用しています。 同じ書誌を追加登録することはできません。';
					}
				}

				if (!$val[6]) {
				}elseif(!preg_match('/^[0-9]{7}$/u', $val[6])){
					$this->error[] = ($key + 1) . '行目の雑誌コードの形式が正しくありません。 雑誌コードの記入は半角数字5桁と2桁でお願いいたします。';
				}

				if (!$val[7]) {
				}elseif(!preg_match('/^[0-9]{4}$/u', $val[7])){
					$this->error[] = ($key + 1) . '行目のCコードの形式が正しくありません。 Cコードの記入は半角数字4桁でお願いいたします。';
				}

				if (!$val[8] || $val[8] == '未設定') {
				}elseif(!$this->isDate($val[8])){
					$this->error[] = ($key + 1) . '行目の出版年月日の形式が正しくありません。 出版年月日の記入をご確認ください。';
				}

				if (!$val[9] || $val[9] == '未設定') {
				}elseif(!$this->isDate($val[9])){
					$this->error[] = ($key + 1) . '行目の書店発売日の形式が正しくありません。 書店発売日の記入をご確認ください。';
				}

				// ページ数
				if (!$val[12]) {
				}elseif(!$this->isNumber($val[12])){
					$this->error[] = ($key + 1) . '行目のページ数の形式が正しくありません。 半角数字で記入をお願いいたします。';
				}

				// 本体価格
				if (!$val[13]) {
				}elseif(!$this->isNumber($val[13])){
					$this->error[] = ($key + 1) . '行目の本体価格の形式が正しくありません。 半角数字で記入をお願いいたします。';
				}

				if (!$val[14]) {
				}elseif(mb_strlen(mb_ereg_replace('\n|\r', '', $val[14])) > 62){
					$this->error[] = ($key + 1) . '行目の概要（長文）は62文字以内で指定する必要があります。 概要（長文）の記入は62文字以内でお願いいたします。';
				}

				if (!$val[15]) {
				}elseif(mb_strlen(mb_ereg_replace('\n|\r', '', $val[15])) > 20){
					$this->error[] = ($key + 1) . '行目の概要（短文）は20文字以内で指定する必要があります。 概要（短文）の記入は20文字以内でお願いいたします。';
				}

				if (!$val[18] || $val[18]=='未設定') {
				}elseif(!$this->isDateTime($val[18])){
					$this->error[] = ($key + 1) . '行目の公開日時の形式が正しくありません。 公開日時の記入をご確認ください。';
				}

				if (!$val[22]) {
				}elseif(!mb_ereg_match('^.{0,130}$', $val[22])){
					$this->error[] = ($key + 1) . '行目のキーワードが130文字を超えています。 130文字以内でご記入ください。';
				}

				if (!$val[24]) {
				}elseif($val[24] == 'おすすめ'){
					$this->recommend_count ++;
					if($this->recommend_count > 10){
						$this->error[] = 'おすすめに設定した書誌が10冊を超えています。 '.($key + 1).'行目のおすすめの設定をご確認ください。';
					}
				}

				if (!$val[11]){
				}else{
					$isNull = true;
					foreach($this->bookSizeList as $val2){
						if($val2['name'] == $val[11]){
							$isNull = false;
							break;
						}
					}
					if ($isNull){
						$this->error[] = ($key + 1) . '行目の版型が正しくありません。 版型の記入をご確認ください。';
					}
				}

				if (!$val[27]){
				}else{
					$isNull = true;
					foreach($this->stockStatusList as $val2){
						if($val2['name'] == $val[27]){
							$isNull = false;
						}
					}
					if ($isNull){
						$this->error[] = ($key + 1) . '行目の在庫設定が正しくありません。 在庫の記入をご確認ください。';
					}
				}

				if (!$val[29]){
				}else{
					foreach ($val[29] as $k => $v) {
						$cascade = explode('>',$v);
						if(count($cascade) > 3) {
							$this->error[] = ($key + 1) . '行目のジャンルの階層が3階層を超えています。';
						}
					}
				}

				if (!$val[30]){
				}else{
					foreach ($val[30] as $k => $v) {
						$cascade = explode('>',$v);
						if(count($cascade) > 3) {
							$this->error[] = ($key + 1) . '行目のシリーズの階層が3階層を超えています。';
						}
					}
				}

				// 32: フォーマット紐付け対象ISBN 33: 自分のフォーマット 34: 紐付け対象のフォーマット
				if(empty($val[32]) && empty($val[33]) && empty($val[34])) {
					// 全て未入力の場合はスルー
				} elseif(!(!empty($val[32]) && !empty($val[33]) && !empty($val[34]))) {
					// どこかしらに入力があり どこかしらに未入力がある
					$this->error[] = ($key + 1) . '行目のフォーマット指定が途中です。ご確認ください。';
				} elseif(!preg_match('/^(([0-9]{9}[0-9X]{1})|([0-9]{12}[0-9X]{1}))$/ui', $val[32])){
					$this->error[] = ($key + 1) . '行目のフォーマット紐付け対象ISBNの形式が正しくありません。 ISBNの記入は半角10桁か13桁でお願いいたします。';
				} else {
					$db->assign('isbn', $val[32]);
					$isbnRow = $db->fetch_assoc($db->statement('admin/publisher/book/sql/book_isbn.sql'));
					if(false === $isbnRow){
						$this->error[] = ($key + 1) . '行目で指定されたフォーマット紐付け対象ISBNが登録されていません。';
					} else {
						// 紐付いた書籍のbook_noを格納
						$this->bookList[$key][32] = $isbnRow['book_no'];
					}
				}

				foreach($val as $key2 => $val2){
					if($key2 >= 35){
						if (!$val2[1]) {
						}elseif(!$this->isKatakana($val2[1])){
							$this->error[] = ($key + 1) . '行目の著者'.($key2 - 34).'の著者名カナが全角カタカナではありません。 著者名カナの記入は全角カタカナでお願いいたします。';
						}
					}
				}

				$this->rowCount ++;
			}else{
				$this->bookList[$key] = array();
			}
		}


		// 入力データの検証終了
		$endTime = microtime(true);
		$this->log(sprintf('[import]Complete validation. time:%ss count:%d', $endTime - $startTime, $this->rowCount));

		$maxRowCount = 4000; // 一括取り込みの最大行
		if($this->rowCount > $maxRowCount){
			$this->log(sprintf('[import][err]maxRowCount over!', $this->rowCount));
			$this->error[] = "一度に登録出来る書誌は最大{$maxRowCount}冊までです。";
			return 'input';

		}elseif(count($this->error)) {
			$this->log(sprintf('[import][err]validation error! errorCount=%d', count($this->error)));
			return 'input';

		}elseif(!$this->rowCount){
			$this->log('[import][err]no data');
			$this->error[] = '有効な行がファイルにありません。 ファイルの内容をご確認ください。';
			return 'input';
		}

		return true;
	}

	function isDate($string){
		if(preg_match("/^\d*$/u",$string)){
			$dateItems[0] = preg_replace("/^(\d{4})(\d{2})(\d{2})$/u","$1",$string);
			$dateItems[1] = preg_replace("/^(\d{4})(\d{2})(\d{2})$/u","$2",$string);
			$dateItems[2] = preg_replace("/^(\d{4})(\d{2})(\d{2})$/u","$3",$string);
		}else{
			$dateItems = preg_split("/-|\//u", $string);
		}

		if(count($dateItems)==3){
			if(parent::isDate($dateItems[0], $dateItems[1], $dateItems[2])){
				return true;
			}
		}

		return false;
	}

	function isDateTime($string){
		if(preg_match("/\s/u",$string)){
			$dateItems = preg_split("/\s/u", $string);

			if($this->isDate($dateItems[0])
					&& preg_match("/^\d{2}:\d{2}(:\d{2})?$/u", $dateItems[1])){
				return true;
			}
		}else{
			return $this->isDate($string);
		}

		return false;
	}

	/**
	 * ファイルポインタから行を取得し、CSVフィールドを処理する
	 * @param resource handle
	 * @param int length
	 * @param string delimiter
	 * @param string enclosure
	 * @return ファイルの終端に達した場合を含み、エラー時にFALSEを返します。
	 */
	function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
		$d = preg_quote($d);
		$e = preg_quote($e);
		$_line = "";
		// 1行を取得 「"」が奇数の場合は途中で改行されていると見なし 次の行と連結
		$eof=false;
		while ($eof != true) {
			$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
			$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
			if ($itemcnt % 2 == 0) {
				$eof = true;
			}
		}

		$_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
		$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
		preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		$_csv_data = $_csv_matches[1];

		for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
			$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
			$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
		}

		if ( $this->csvEncode == "SJIS-win"){
			mb_convert_variables("UTF-8", "SJIS-win", $_csv_data);
		}

		return empty($_line) ? false : $_csv_data;
	}
}
?>
