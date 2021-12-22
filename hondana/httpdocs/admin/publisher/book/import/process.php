<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	function execute() {
		// 入力データのインポート開始
		$startTime = microtime(true);
		$this->log('[import]Start import.');

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$this->genreList = array();
		$this->seriesList = array();
		$this->authorList = array();

		//使用値リスト作成
		foreach ($this->bookList as $key => $val){
			if(count($val) > 30){
				foreach($val as $key2 => $val2){
					if($key2 == 29){
						foreach ($val2 as $val3){
							if($val3 && !in_array($val3 , $this->genreList)){
								array_push($this->genreList, $val3);
							}
						}
					}elseif($key2 == 30){
						foreach ($val2 as $val3){
							if($val3 && !in_array($val3 , $this->seriesList)){
								array_push($this->seriesList , $val3);
							}
						}
					}elseif($key2 >= 31){
						$hasList = false;
						foreach ($this->authorList as $val3){
							if( $val2[0] == $val3['name'] && $val2[1] == $val3['kana']){
								$hasList = true;
							}
						}
						if(!$hasList && $val2[0]){
							array_push($this->authorList , array('name' => $val2[0],'kana' => $val2[1],'type' => $val2[2]) );
						}
					}
				}
			}
		}
		//$this->genreList = array_unique($this->genreList);
		//$this->seriesList = array_unique($this->seriesList);
		//$this->authorList = array_unique($this->authorList);

		//現在値リスト作成
		$result = $db->statement('admin/publisher/book/sql/genre_list.sql');
		$tree = $db->buildTree($result, 'genre_no');
		$this->nowGenreList = $tree;

		$result = $db->statement('admin/publisher/book/sql/series_list.sql');
		$tree = $db->buildTree($result, 'series_no');
		$this->nowSeriesList = $tree;

		$result = $db->statement('admin/publisher/book/sql/author_list.sql');
		$tree = $db->buildTree($result, 'author_no');
		$this->nowAuthorList = $tree;

		//追加値リスト作成
		$tempList = array();
		foreach ($this->genreList as $genre){
			$hasItem = false;
			foreach($this->nowGenreList as $nowGenre){
				if($genre == $nowGenre['name']){
					$hasItem = true;
				}
			}
			if(!$hasItem){
				array_push($tempList, $genre);
			}
		}
		$this->genreList = $tempList;

		$tempList = array();
		foreach ($this->seriesList as $series){
			$hasItem = false;
			foreach($this->nowSeriesList as $nowSeries){
				if($series == $nowSeries['name']){
					$hasItem = true;
				}
			}
			if(!$hasItem){
				array_push($tempList, $series);
			}
		}
		$this->seriesList = $tempList;

		$tempList = array();
		foreach ($this->authorList as $author){
			$hasItem = false;
			foreach($this->nowAuthorList as $nowAuthor){
				if($author['name'] == $nowAuthor['name'] && $author['kana'] == $nowAuthor['kana']){
					$hasItem = true;
				}
			}
			if(!$hasItem){
				array_push($tempList, $author);
			}
		}
		$this->authorList = $tempList;


		$this->bookList = $this->clearEmptyElement($this->bookList);
		$this->bookList = $this->q($this->bookList);
		$this->genreList = $this->q($this->genreList);
		$this->seriesList = $this->q($this->seriesList);
		$this->authorList = $this->q($this->authorList);

		$db->assign($this->getProperties());


		$db->begin();
        try {
            $rs = $db->statement('admin/publisher/book/sql/import_1.sql');
            if($rs === false) {
				$endTime = microtime(true);
				$this->log(sprintf('[import][err]import1 error! time:%ss', $endTime - $startTime));
                $this->crashDB("i1ex");
			}

            $rs = $db->statement('admin/publisher/book/sql/import_2.sql');
            if($rs === false) {
				$endTime = microtime(true);
				$this->log(sprintf('[import][err]import2 error! time:%ss', $endTime - $startTime));
                $this->crashDB("i2ex");
			}

        } catch (Exception $exc) {
			$this->log(sprintf('[import][err]import error! %s. time:%ss', $exc->getTraceAsString(), $endTime - $startTime));
            $this->crashDB($exc->getTraceAsString());
        }
		$db->commit();

		// 入力データのインポート終了
		$endTime = microtime(true);
		$this->log(sprintf('[import]Complete import. time:%ss count:%d', $endTime - $startTime, $this->rowCount));

        $this->clearProperties();

		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}

    function crashDB($str){
		$db =& $this->_db;
        $db->rollback();

        $message = "書誌の一括取込に失敗しました。:" . $str;
        $this->__controller->redirectToURL("index.php?action=input&db_crash={$message}");

        exit();
    }

	function q($str='') {
		if(is_array($str)) {
			$q = function_exists("q") ? "q" : array(&$this, "q");
			return array_map($q, $str);
			}else {
			if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
			}
			if(!is_numeric($str)) {
			$ver = explode('.', phpversion());
			if(intval($ver[0].$ver[1])>=43) {
			 $str = mysqli_real_escape_string($this->_db->connection,$str);
			}else {
			 $str = addslashes($str);
			 $pre = array('/\n/um', '/\r/um', '/\x1a/um');
			 $after = array('\\\n', '\\\r', '\Z');
			 $str = preg_replace($pre, $after, $str);
			}
			}
			return $str;
		}
	}

	function clearEmptyElement($array){
		if(is_array($array)){
			foreach($array as $k => $v){
				if(empty($v)){
					unset ($array[$k]);
				}
				if(0==count($v)){
					unset ($array[$k]);
				}

			}
		}
		return $array;
	}
}
?>