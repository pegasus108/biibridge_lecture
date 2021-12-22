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
				if(join('',$data)) $bookList[] = $data;
			}
			fclose($handle);

			if(count($bookList)){

				foreach($bookList as $key => $val){
					$baseCount = 0;
					foreach($bookList[$key] as $key2 => $val2){
						$bookList[$key][$key2] = mb_ereg_replace('(^[ |　|"]*)|([ |　|"]*$)','',$bookList[$key][$key2]);
						$bookList[$key][$key2] = mb_ereg_replace('(^")|("$)','',$bookList[$key][$key2]);
						$bookList[$key][$key2] = mb_ereg_replace('(^[ |　|"]*)|([ |　|"]*$)','',$bookList[$key][$key2]);
					}

					if($val[0])
						$isbnList[$val[0]] = $key;
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
		$this->log('[csv_update]Start validation.');

		$db =& $this->_db;

		$this->error = false;
		$this->rowCount=0;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/sql/stock_status_list.sql');
		$tree = $db->buildTree($result, 'stock_status_no');
		$this->stockStatusList = $tree;
/*
		$result = $db->statement('admin/publisher/book/sql/book_isbn_list.sql');
		$tree = $db->buildTree($result, 'book_no');
		$this->bookISBNList = $tree;
*/
//		$subBook = $this->bookList;
		foreach ($this->bookList as $key => $val){
			if(!(preg_match('/ISBN/ui',$val[0]) && $val[1]=='在庫設定')){

				if(count($val) != 2 ){
					$this->error[] = ($key + 1) . '行目の項目数が適切ではありません。<br />アップロードファイルの形式のご確認をお願いいたします。';
//					break;
				}

				if (!$val[0]) {
					$this->error[] = ($key + 1) . '行目にISBNが記載されていません。';
//					break;
				}elseif(!preg_match('/^(([0-9]{9}[0-9X]{1})|([0-9]{12}[0-9X]{1}))$/ui', $val[0])){
					$this->error[] = ($key + 1) . '行目のISBNの形式が正しくありません。';
//					break;
				}else{
//					for($i = $key+1 ; $i <= count($subBook) ; $i++){
//						if($val[0] == $subBook[$i][1]){
//							$this->error[] = ($key + 1) . '行目のISBNと' . ($i + 1) . '行目のISBNが重複しています。';
////							break 1;
//						}
//					}
					if($this->isbnList[$val[0]] && $key != $this->isbnList[$val[0]]){
						$this->error[] = ($key + 1) . '行目のISBNと' . ($this->isbnList[$val[0]] + 1) . '行目のISBNが重複しています。';
//							break 1;
					}
					$ok = false;
					if(!count($this->dbRecord('book',"isbn='${val[0]}' and publisher_no=${_SESSION['publisher_no']}"))){
						$this->error[] = ($key + 1) . '行目のISBNが登録されている書誌の中に見つかりません。';
//						break;
					}
					/*
					foreach($this->bookISBNList as $isbnVal){
						if($val[0] == $isbnVal['isbn'])
							$ok = true;
					}
					if(!$ok){
						$this->error = ($key + 1) . '行目のISBNが登録されている書誌の中に見つかりません。<br />ISBNの確認をお願いいたします。';
						break;
					}
					*/
				}

				if (!$val[1]){
					$this->error[] = ($key + 1) . '行目に在庫ステータスが記載されていません。';
//					break;
				}else{
					$isNull = true;
					foreach($this->stockStatusList as $val2){
						if($val2['name'] == $val[1])
							$isNull = false;
					}
					if ($isNull){
						$this->error[] = ($key + 1) . '行目の在庫ステータスが判別できません。';
//						break;
					}
				}
				$this->rowCount ++;
			}else
				$this->bookList[$key] = array();
		}


		// 入力データの検証終了
		$endTime = microtime(true);
		$this->log(sprintf('[csv_update]Complete validation. time:%ss count:%d', $endTime - $startTime, $this->rowCount));

		$maxRowCount = 3000;
		if($this->rowCount > $maxRowCount){
			$this->log(sprintf('[csv_update][err]maxRowCount over!', $this->rowCount));
			$this->error[] = "一度に更新出来る書誌は最大{$maxRowCount}冊までです。";
			return 'input';

		}elseif($this->error){
			$this->log(sprintf('[csv_update][err]validation error! errorCount=%d', count($this->error)));
			return 'input';

		}elseif(!$this->rowCount){
			$this->log('[csv_update][err]no data');
			$this->error = '有効な行がファイルにありません。<br />ファイルの内容をご確認ください。';
			return 'input';
		}

		return true;
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
		$eof=false;
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0)
                $eof = true;
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

	function dbRecord($table,$where=null,$select=null,$group=null){
		$db =& $this->_db;
		$sql ="select";
		if(empty($select))
			$sql .= " *";
		else
			$sql .= " " . $select;
		$sql .= " from ". $table;
		if (!empty($where))
			$sql .= " where ".$where;
		if(!empty ($group))
			$sql .=" group by ".$group;
		$sql .= " limit 0,1;";

		$db->assign($this->getProperties());
		$result = $db->query($sql);
		if($row = $db->fetch_assoc($result))
			return $row;
		else
			return array();
	}
}
?>