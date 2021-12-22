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

		$authorList = array();

		// 親ジャンル取得
		$result = $db->statement('admin/publisher/book/import_v2/sql/get_parent_genre.sql');
		$parent_genre = $db->fetch_assoc($result);

		// 親シリーズ取得
		$result = $db->statement('admin/publisher/book/import_v2/sql/get_parent_series.sql');
		$parent_series = $db->fetch_assoc($result);

		// 電子書籍書店リスト取得
		$result = $db->statement('admin/publisher/book/import_v2/sql/get_format_list.sql');
		$tree = $db->buildTree($result);
		$formatlist = array();
		foreach ($tree as $k => $v) {
			if($v['id'] != 6) {
				// その他以外のリスト作成
				$formatlist[$v['id']] = $v['name'];
			}
		}

		// 使用値リスト作成
		foreach ($this->bookList as $key => $val){
			// 書籍の繰り返し
			if(count($val) > 30){
				foreach($val as $key2 => $val2){
					// 項目の繰り返し
					if($key2 == 29 && !empty($val2)) {
						// ジャンルの取得 追加
						$genreList = array();
						foreach ($val2 as $genre) {
							$cascade = explode('>',$genre); // 「>」で分割 最大3階層
							$targetno = $parent_genre['genre_no'];
							foreach ($cascade as $k => $v) {
								$db->assign("target_genre",$targetno);
								$db->assign("name",trim($v));
								$db->assign("depth",$k+1);
								$result = $db->statement('admin/publisher/book/import_v2/sql/check_genre.sql');
								$target = $db->fetch_assoc($result);

								if(!empty($target)) {
									// すでにジャンルがある → 次のループのためにgenre_noを上書き
									$targetno = $target['genre_no'];
								} else {
									// ジャンルがない → ジャンルを追加
									$db->begin();
									$db->statement('admin/publisher/book/genre/sql/insert.sql');
									$db->commit();

									$result = $db->statement('admin/publisher/book/import_v2/sql/check_genre.sql');
									$target = $db->fetch_assoc($result);
									if(!empty($target)) {
										// 登録したジャンルのgenre_noで上書き
										$targetno = $target['genre_no'];
									} else {
										// 取得エラー ループから抜ける
										break;
									}
								}
							}
							$genreList[] = $targetno;
						}
						// 同じジャンルを指定した場合は削除
						$genreList = array_unique($genreList);
						$this->bookList[$key][$key2] = $genreList;
					} elseif($key2 == 30 && !empty($val2)) {
						// シリーズの取得 追加
						$db->assign("kana",'');
						$seriesList = array();
						foreach ($val2 as $series) {
							$cascade = explode('>',$series); // 「>」で分割 最大3階層
							$targetno = $parent_series['series_no'];
							foreach ($cascade as $k => $v) {
								$db->assign("target_series",$targetno);
								$db->assign("name",trim($v));
								$db->assign("depth",$k+1);
								$result = $db->statement('admin/publisher/book/import_v2/sql/check_series.sql');
								$target = $db->fetch_assoc($result);

								if(!empty($target)) {
									// すでにシリーズがある → 次のループのためにseries_noを上書き
									$targetno = $target['series_no'];
								} else {
									// シリーズがない → シリーズを追加
									$db->begin();
									$db->statement('admin/publisher/book/series/sql/insert.sql');
									$db->commit();

									$result = $db->statement('admin/publisher/book/import_v2/sql/check_series.sql');
									$target = $db->fetch_assoc($result);
									if(!empty($target)) {
										// 登録したシリーズのseries_noで上書き
										$targetno = $target['series_no'];
									} else {
										// 取得エラー ループから抜ける
										break;
									}
								}
							}
							$seriesList[] = $targetno;
						}
						// 同じシリーズを指定した場合は削除
						$seriesList = array_unique($seriesList);
						$this->bookList[$key][$key2] = $seriesList;
					} elseif(($key2 == 33 || $key2 == 34) && !empty($val2)) {
						$no = array_search($val2, $formatlist);
						if(!empty($no)) {
							// フォーマットリストにある
							$this->bookList[$key][$key2] = array(
								'id' => $no,
								'val' => ''
							);
						} else {
							// フォーマットリストにない
							$this->bookList[$key][$key2] = array(
								'id' => 6,
								'val' => $val2
							);
						}
					} elseif($key2 >= 35) {
						// 著者リスト
						$hasList = false;
						foreach ($authorList as $val3){
							if( $val2[0] == $val3['name'] && $val2[1] == $val3['kana']){
								$hasList = true;
							}
						}
						if(!$hasList && $val2[0]){
							// 著者リストに追加
							array_push($authorList , array('name' => $val2[0],'kana' => $val2[1],'type' => $val2[2]) );
						}
					}
				}
			}
		}

		// 現在値リスト作成
		$result = $db->statement('admin/publisher/book/sql/author_list.sql');
		$tree = $db->buildTree($result, 'author_no');
		$this->nowAuthorList = $tree;

		$tempList = array();
		foreach ($authorList as $author){
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
		$authorList = $tempList;

		// 電子書籍書店リスト取得
		$result = $db->statement('admin/publisher/book/import_v2/sql/get_ebookstore.sql');
		$tree = $db->buildTree($result);
		$this->ebookstorelist = $tree;

		$this->bookList = $this->clearEmptyElement($this->bookList);
		$this->bookList = $this->q($this->bookList);
		$this->authorList = $this->q($authorList);

		$db->assign($this->getProperties());

		$db->begin();
		try {
			$rs = $db->statement('admin/publisher/book/import_v2/sql/import_1.sql');
			if($rs === false) {
				$endTime = microtime(true);
				$this->log(sprintf('[import][err]import1 error! time:%ss', $endTime - $startTime));
				$this->crashDB("i1ex");
			}

			$rs = $db->statement('admin/publisher/book/import_v2/sql/import_2.sql');
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
					$str = mysql_real_escape_string($str);
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