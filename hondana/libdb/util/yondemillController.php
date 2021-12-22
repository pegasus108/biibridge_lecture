<?php
class yondemillController extends DefaultController {
	// var $apiUrl = "http://staging.api.yondemill.jp/1/";
	// var $apiUrl = "http://api.yondemill.dev/1/";
	var $apiUrl = "https://api.yondemill.jp/1/";
	var $db;

	/**
	 * [yondemillController description]
	 * @param  [type] $d [description]
	 * @return [type]    [description]
	 */
	function __construct($d) {
		$this->db =& $d;
	}

	/**
	 * [getEbookstores 電子書籍書店情報取得]
	 * @param  [type] $auth_token [description]
	 * @return [type]             [description]
	 */
	function getEbookstores($option = null) {
		// 電子書籍書店リストと出版社ごとの反映状況を合わせて取得
		$this->db->assign("publisher_no",$option['publisher_no']);

		if(!empty($option['book_no'])) {
			$this->db->assign("book_no",$option['book_no']);
		} else {
			$this->db->assign("book_no","");
		}
		$result = $this->db->statement('admin/publisher/system/ebookstores/sql/list.sql');
		$ebookstoreList = $this->db->buildTree($result, 'id');
		// if(!empty($option['book_no'])) {
			// 表示ステータスの調整
			$defaultstoreList = $ebookstoreList;
			$defaultFlg = true;
			foreach ($ebookstoreList as $k => &$v) {
				if(empty($v['be_status'])) { // 書籍単位の設定がない
					$v['be_id'] = -1;
					$v['be_status'] = -1;
				} else {
					$defaultFlg = false;
				}
				$defaultstoreList[$k]['be_id'] = -1;
				if(empty($defaultstoreList[$k]['public_status'])) {
					$defaultstoreList[$k]['be_status'] = -1;
				} else {
					$defaultstoreList[$k]['be_status'] = 1;
				}
			}
			if($defaultFlg) {
				$ebookstoreList = $defaultstoreList;
			}
		// }
		return $ebookstoreList;
	}

	/**
	 * [__updateEbookstores 電子書籍書店情報更新]
	 * @param  [type] $ebookstores [description]
	 * @return [type]              [description]
	 */
	function __updateEbookstores($ebookstores) {
		return false;
	}

	/**
	 * [getEbookstoresURL 電子書籍書店へのリンク先取得]
	 * @param  [type] $option [description]
	 * @return [type]         [description]
	 */
	function getEbookstoresURL($option = null) {
		if(empty($option['book_name']) || empty($option['book_no'])) {
			// 必要な引数チェック
			return false;
		}
		$sqlPath = '';
		if(!empty($option['setSQLPath'])) {
			$sqlPath = $option['setSQLPath'];
		}
		$searchWord = $option['book_name'];

		// 電子書籍書店リストと出版社ごとの反映状況を合わせて取得
		$this->db->assign("publisher_no",$option['publisher_no']);
		$this->db->assign("book_no",$option['book_no']);
		$result = $this->db->statement($sqlPath . 'admin/publisher/system/ebookstores/sql/list.sql');
		$ebookstoreList = $this->db->buildTree($result, 'id');
		if(empty($ebookstoreList)) {
			return false;
		}

		// 書籍単位の設定があるか確認
		$bookset = false;
		foreach ($ebookstoreList as $k => $v) {
			if(!empty($v['be_id'])) {
				$bookset = true;
				break;
			}
		}

		$results = array();
		foreach ($ebookstoreList as $k => $v) {
			if($bookset) {
				// 書籍単位の公開設定がある
				if($v['be_status'] == 1) {
					$v['url'] = $this->getEbookStoreUrl($v,$searchWord,$option['amazon_associates_id']);
					$results[] = $v;
				}
			} else {
				// 出版社単位の公開設定が公開
				if(!empty($v['public_status'])) {
					$v['url'] = $this->getEbookStoreUrl($v,$searchWord);
					$results[] = $v;
				}
			}
		}
		return $results;
	}

	/**
	 * [postData 立ち読みデータ追加・更新]
	 * @param  [type] $option [description]
	 * @return [type]         [description]
	 */
	function postData($option = null) {
		return false;
	}

	/**
	 * [deleteData 立ち読みデータ削除]
	 * @param  [type] $option [description]
	 * @return [type]         [description]
	 */
	function deleteData($option = null) {
		return false;
	}

	/**
	 * [getBookList 書籍の一覧を取得（出版社単位）]
	 * @param  [type] $option [description]
	 * @return [type]         [description]
	 */
	function getBookList ($option = null) {
		return false;
	}

	/**
	 * [getBookData 書籍を1件取得]
	 * @param  [type] $option [description]
	 * @return [type]         [description]
	 */
	function getBookData ($option = null) {
		return false;
	}

	/**
	 * [getBinbURL 立ち読みファイルURL取得]
	 * @param  [type] $option [description]
	 * @return [type]         [description]
	 */
	function getBinbURL($option = null) {
		return false;
	}

	/**
	 * [addBinb 立ち読みURLをDBへ保存]
	 * @param [type] $id  [description]
	 * @param [type] $url [description]
	 */
	function addBinb($list,$option = null) {
		return false;
	}

	function setListBinbURL($booklist ,$option) {
		return $booklist;
	}

	/**
	 * 多次元連想配列を一次元連想配列に変換
	 */
	function hash_flatten($data) {
		$result = array();
		$stack = array();
		$path = null;

		reset($data);
		while (!empty($data)) {
			$key = key($data);
			$element = $data[$key];
			unset($data[$key]);

			if (is_array($element)) {
				if (!empty($data)) {
					$stack[] = array($data, $path);
				}
				$data = $element;
				if(!empty($path)) {
					$path .= "[" .$key . "]";
				} else {
					$path .= $key;
				}
			} else if(!empty($path)) {
				$result[$path . "[" . $key . "]"] = $element;
			} else {
				$result[$key] = $element;
			}

			if (empty($data) && !empty($stack)) {
				list($data, $path) = array_pop($stack);
			}
		}
		return $result;
	}

	/**
	 * 拡張子からMIME Typeを特定
	 */
	function get_mime_type($filename) {
		$mime_type = "";
		$mime_types = array(
			'epub' => 'application/epub+zip',
			'pdf' => 'application/pdf',
			'book' => 'application/book',
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
		);

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		if(array_key_exists($extension, $mime_types)) {
			$mime_type = $mime_types[$extension];
		}
		return $mime_type;
	}

	/**
	 * [getEbookStoreUrl 電子書店リンク取得]
	 * @param  [array] $data [電子書店データ]
	 * @param  [string] $word [検索ワード]
	 * @param  [string] $amazon_associates_id [アフィリエイト（amazon）ID]
	 * @return [string]       [電子書店URL]
	 */
	function getEbookStoreUrl($data,$word,$amazon_associates_id = null) {
		if(!empty($data['url'])) {
			// 書籍単位で設定がある場合
			if($data['id'] == 1 && !empty($amazon_associates_id)) {
				// Amazon Kindle 且つ アフィリエイトIDがある 且つ 直接URL入力の際は
				// URLの後ろにアフィリエイトIDを追加
				return $data['url'] . "&tag=" . $amazon_associates_id;
			} else {
				return $data['url'];
			}
		}

		// 書店側の文字コード確認
		if($data['charset'] == 'euc') {
			$word = mb_convert_encoding($word, "eucjp-win", "UTF-8");
		} elseif($data['charset'] == 'sjis') {
			$word = mb_convert_encoding($word, "sjis-win", "UTF-8");
		}

		// 検索キーワードの挿入
		if(strpos($data['search_url'],'#keyword#') !== false) {
			// #keyword# を検索キーワードへ置き換え
			return str_replace("#keyword#", rawurlencode($word), $data['search_url']);
		} else {
			// 検索キーワードを挿入しない (詳細ページの決まったフォーマットがわからず、トップページへ遷移させる場合等)
			return $data['search_url'];
		}
	}
}
?>