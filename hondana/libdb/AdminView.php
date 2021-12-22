<?php

class AdminView extends DefaultView {
	var $_db;
	var $_account;
	var $currentURL = "";

	function fetch() {
		$text = parent::fetch();

		$this->databaseInit();

		if(!$this->isPublisherLogined($_SESSION['loginid'] , $_SESSION['pass'])){
			return $text;
		}

		$this->currentURL = preg_replace("/\/[^\/]*$/u", "/", $_SERVER["SCRIPT_NAME"]);

		// 権限によるアクセス制限
		$text = $this->disableDenyAccess($text);

		// 書籍管理項目の非表示対応
		if($this->currentURL == "/admin/publisher/book/edit/"
			|| $this->currentURL == "/admin/publisher/book/new/"
		){
			$text = $this->disableDenyBookInput($text);
		}

		// 制御ボタンの非表示対応
		if(
			$this->currentURL == "/admin/publisher/book/edit/"
			|| $this->currentURL == "/admin/publisher/author/edit/"
			|| $this->currentURL == "/admin/publisher/book/genre/edit/"
			|| $this->currentURL == "/admin/publisher/book/series/edit/"
		){
			$text = $this->disableDenyButton($text);
		}

		// 書籍一括操作機能 削除対応
		if($this->currentURL == "/admin/publisher/book/"){
			$text = $this->disableDenyOptionBookList($text);
		}
		// 著者情報一覧 無効化
		if($this->currentURL == "/admin/publisher/author/"){
			// 著者一覧編集機能 無効化
			$text = $this->disableListEdit($text);
			// 一括操作機能 削除対応
			$text = $this->disableDenyOption($text);
		}
		// ジャンル シリーズ レーベル 一覧 無効化
		if(
			$this->currentURL == "/admin/publisher/book/genre/"
			|| $this->currentURL == "/admin/publisher/book/series/"
			|| $this->currentURL == "/admin/publisher/book/label/"
		){
			// 登録機能 無効化
			$text = $this->disableAdd($text);
			// 編集機能 無効化
			$text = $this->disableEdit($text);
			// 削除機能 無効化
			$text = $this->disableDenyDelete($text);
		}
		// ジャンル シリーズ レーベル 著者 ポップアップ一覧 無効化
		if(
			$this->currentURL == "/admin/publisher/book/genre_popup/"
			|| $this->currentURL == "/admin/publisher/book/series_popup/"
			|| $this->currentURL == "/admin/publisher/book/label_popup/"
			|| $this->currentURL == "/admin/publisher/author/author_popup/"
		){
			// // ポップアップ登録機能 無効化
			$text = $this->disablePopupAdd($text);
			// // ポップアップ編集機能 無効化
			$text = $this->disablePopupEdit($text);
		}

		return $text;
	}

	/**
	 * [disableDenyAccess 権限によるアクセス制限]
	 * @param  [type] $text [出力ページのHTMLソースコード]
	 * @return [type]       [description]
	 */
	function disableDenyAccess($text){
		// アクセス拒否リストの取得
		$denyList = $this->denyList();
		$allow = false;

		if(!empty($denyList)) {
			foreach ( $denyList as $deny){
				if(!$allow) {
					if(!empty((string)$deny["url"]) && strpos($this->currentURL, (string)$deny["url"]) !== false){
						if($deny['allow'] == 1) {
							// アクセス可能処理
							$allow = true;
						} else {
							// アクセス拒否ページのため ログインページへリダイレクト
							header("Location: /admin/");
							exit();
						}
					}
				}

				// ページ内リンク 非表示対応
				$relatevePath = $this->makeRelativePath($deny["url"], $this->currentURL);
				$depth = "";
				if($deny["is_depth"]==1){
					$depth = " isDepth";
				}

				if(preg_match("/<li.*\"" .preg_quote($deny["url"], "/"). ".*?\".*?<\/li>/u", $text)){
					$text = preg_replace("/(<li)(.*\"" .preg_quote($deny["url"], "/"). ".*?\".*?<\/li>)/u", "$1 class=\"meAndParentHasnotRole{$depth}\"$2", $text);
				}
				if(preg_match("/<li.*\"" .preg_quote($relatevePath, "/"). ".*?\".*?<\/li>/u", $text)){
					$text = preg_replace("/(<li)(.*\"" .preg_quote($relatevePath, "/"). ".*?\".*?<\/li>)/u", "$1 class=\"meAndParentHasnotRole{$depth}\"$2", $text);
				}
			}
		}

		return $text;
	}

	/**
	 * [disableDenyBookInput 書籍管理項目の非表示対応]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disableDenyBookInput($text){
		$denyCodeList = $this->denyBookCodeList();
		foreach ( $denyCodeList as $code){
			if(preg_match("/class=\"RoleType" .preg_quote($code, "/"). "\"/u", $text)){
				$text = preg_replace("/class=\"([^\"]*)RoleType" .preg_quote($code, "/"). "\"/u", "class=\"$1RoleType{$code} none\"", $text);
			}
		}

		return $text;
	}

	/**
	 * [disableDenyButton 制御ボタン 非表示対応]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disableDenyButton($text){
		$denyList = $this->denyList();
		if(!empty($denyList)) {
			foreach ( $denyList as $deny){
				if(empty($deny["url"]))
					continue;

				if(preg_match("/<input[^<>]*'" .preg_quote($deny["url"], "/"). "[^<>]*>/u", $text)){
					$text = preg_replace("/<input[^<>]*'" .preg_quote($deny["url"], "/"). "[^<>]*>/u", "", $text);
				}
				// 相対パスへ対応
				$relatevePath = $this->makeRelativePath($deny["url"], $this->currentURL);
				if(preg_match("/<input[^<>]*'" .preg_quote($relatevePath, "/"). "[^<>]*>/", $text)){
					$text = preg_replace("/<input[^<>]*'" .preg_quote($relatevePath, "/"). "[^<>]*>/u", "", $text);
				}
			}
		}

		return $text;
	}

	/**
	 * [disableDenyOptionBookList 書籍一括操作機能 削除対応]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disableDenyOptionBookList($text){
		$denyCodeList = $this->denyOptionCodeList();
		foreach ( $denyCodeList as $code){
			if(preg_match("/<option value=\"" .preg_quote($code, "/"). "\"/u", $text)){
				$text = preg_replace("/<option value=\"" .preg_quote($code, "/"). "\".*?<\/option>/u", "", $text);
			}
		}

		return $text;
	}

	/**
	 * [disableDenyOption 一括操作機能 削除対応]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disableDenyOption($text){
		$denyList = $this->denyList();
		if(!empty($denyList)) {
			foreach ($denyList as $v){
				if(strpos($v['url'], $this->currentURL) !== false){
					// 現在のURLが含まれるものがある場合
					$action = substr(str_replace($this->currentURL, '', $v['url']),0,-1);
					if(preg_match("/<option value=\"" .preg_quote($action, "/"). "\"/u", $text)){
						$text = preg_replace("/<option value=\"" .preg_quote($action, "/"). "\".*?<\/option>/u", "", $text);
					}
				}
			}
		}

		return $text;
	}

	/**
	 * [disableListEdit 著者一覧 編集機能 無効化]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disableListEdit($text){
		$denyList = $this->denyList();
		if(!empty($denyList)) {
			foreach ($denyList as $v){
				if(strpos($v['url'], $this->currentURL . 'edit/') !== false){
					// 現在のURLが含まれるものがある場合
					if(preg_match("/<td>.*?<a.*?>.*?<\/a>.*?<\/td>/us", $text)){
						$text = preg_replace("/<td>(.*?)<a.*?>(.*?)<\/a>(.*?)<\/td>/us", '<td>$1$2$3</td>', $text);
					}
				}
			}
		}
		return $text;
	}

	/**
	 * [disableAdd ジャンル シリーズ レーベル 一覧 登録機能 無効化]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disableAdd($text){
		$denyList = $this->denyList();
		if(!empty($denyList)) {
			foreach ($denyList as $v){
				if(strpos($v['url'], $this->currentURL . 'new/') !== false){
					// 現在のURLが含まれるものがある場合
					if(preg_match("/<p class=\"add-item\"/u", $text)){
						$text = preg_replace("/<p class=\"add-item\".*?\/p>/u", '', $text);
					}
					if(preg_match("/<div class=\"right\".*?を登録.*?<\/div>/us", $text)){
						$text = preg_replace("/<div class=\"right\".*?を登録.*?<\/div>/us", '', $text);
					}
				}
			}
		}
		return $text;
	}

	/**
	 * [disableEdit ジャンル シリーズ レーベル 一覧 編集機能 無効化]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disableEdit($text){
		$denyList = $this->denyList();
		if(!empty($denyList)) {
			foreach ($denyList as $v){
				if(strpos($v['url'], $this->currentURL . 'edit/') !== false){
					// 現在のURLが含まれるものがある場合
					if(preg_match("/<div class=\"left\".*?<a.*?>.*?<\/a>.*?<\/div>/us", $text)){
						$text = preg_replace("/<div class=\"left\"(.*?)<a.*?>(.*?)<\/a>(.*?)<\/div>/us", '<div class="left"$1$2$3</div>', $text);
					}
				}
			}
		}
		return $text;
	}

	/**
	 * [disableDenyDelete ジャンル シリーズ 一覧 一括削除機能 無効化]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disableDenyDelete($text){
		$denyList = $this->denyList();
		if(!empty($denyList)) {
			foreach ($denyList as $v){
				if(strpos($v['url'], $this->currentURL . 'delete/') !== false){
					// 現在のURLが含まれるものがある場合
					if(preg_match("/<div class=\"delete\"/u", $text)){
						$text = preg_replace("/<div class=\"delete\"/u", '<div class="delete disabled"', $text);
					}
				}
			}
		}

		return $text;
	}

	/**
	 * [disablePopupAdd ジャンル シリーズ レーベル 著者 ポップアップ一覧 登録機能 無効化]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disablePopupAdd($text){
		$denyList = $this->denyList();
		if(!empty($denyList)) {
			foreach ($denyList as $v){
				if(strpos($v['url'], $this->currentURL . 'new/') !== false){
					// 現在のURLが含まれるものがある場合
					if(preg_match("/<(td|th|tr) data-add>/u", $text)){
						$text = preg_replace("/<th data-add>.*?<\/th>/us", '', $text);
						$text = preg_replace("/<td data-add>.*?<\/td>/us", '', $text);
						$text = preg_replace("/<tr data-add>.*?<\/tr>/us", '', $text);
					}
				}
			}
		}
		return $text;
	}

	/**
	 * [disablePopupEdit ジャンル シリーズ レーベル 著者 ポップアップ一覧 編集機能 無効化]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function disablePopupEdit($text){
		$denyList = $this->denyList();
		if(!empty($denyList)) {
			foreach ($denyList as $v){
				if(strpos($v['url'], $this->currentURL . 'edit/') !== false){
					// 現在のURLが含まれるものがある場合
					if(preg_match('/<a href="[^"]*" data-edit>.*?<\/a>/us', $text)){
						$text = preg_replace('/<a href="[^"]*" data-edit>(.*?)<\/a>/us', '$1', $text);
					}
				}
			}
		}
		return $text;
	}

	function databaseInit(){
		$_url = 'mysql://' . $_ENV['DB_USERNAME'] . ':' . $_ENV['DB_PASSWORD'] . '@' . $_ENV['DB_HOST'] . '/' . $_ENV['DB_DATABASE'];
		$this->_db = new Database($_url);
		$this->_db->connect();
	}

	function isPublisherLogined($id , $password){
		if($id && $password) {
			$db =& $this->_db;

			$db->assign('id', $id);
			$db->assign('pass', $password);
			$result = $db->statement('admin/sql/auth.sql');

			if($row = $db->fetch_assoc($result)) {
				$this->_account = $row;
				return true;
			}
		}
		return false;
	}

	/**
	 * [denyList アクセス拒否リストの取得]
	 * @return [type] [description]
	 */
	function denyList(){
		$array = array();
		$sql = "
			select a.access_no,a.url,a.is_depth,a.allow
			from
				role r left join role_access ra on r.role_no=ra.role_no
				left join access a on ra.access_no = a.access_no
			where r.role_no = '{$this->_account["role_no"]}'
			order by allow desc,url
			;
		";

		$rs = $this->_db->query($sql);
		$rs = $this->_db->buildTree($rs,"access_no");

		if(isset($rs[0])) {
			// 0件の場合の処理
			$rs = null;
		}

//		$array = array_keys($rs);
		return $rs;
	}

	function denyBookCodeList(){
		$array = array();
		$sql = "
			select ab.code
			from
				role r left join role_access_book rab on r.role_no=rab.role_no
				left join access_book ab on rab.access_book_no = ab.access_book_no
			where r.role_no = '{$this->_account["role_no"]}' and ab.code is not null;
		";

		$rs = $this->_db->query($sql);
		$rs = $this->_db->buildTree($rs,"code");

		$array = array_keys($rs);
		return $array;
	}

	function denyOptionCodeList(){
		$sql = "
			select ab.options
			from
				role r left join role_access_book rab on r.role_no=rab.role_no
				left join access_book ab on rab.access_book_no = ab.access_book_no
			where r.role_no = '{$this->_account["role_no"]}' and ab.options is not null;
		";

		$rs = $this->_db->query($sql);
		$rs = $this->_db->buildTree($rs,"options");
		$rs = array_keys($rs);

		$return = array();
		foreach($rs as $k => $v){
			$temp = explode(",", $v);
			foreach($temp as $k => $v){
				$return[] = $v;
			}
		}

		return $return;
	}

	/**
	 * [makeRelativePath 相対パス生成]
	 * @param  [type] $targetPath  [description]
	 * @param  [type] $currentPath [description]
	 * @return [type]              [description]
	 */
	function makeRelativePath($targetPath,$currentPath){
		$path = "";
		for($i = 0 ; $i < 999 ; $i++){

			if(strpos($targetPath, $currentPath)!==false){
				if($i==0){
//					$path = "./";
					$path = "";
				}

				$otherPath = str_replace($currentPath, "", $targetPath);
				$path.=$otherPath;
				break;
			}else{
				$currentPath = preg_replace("/\/[^\/]*\/[^\/]*$/u", "/", $currentPath);
				$path .= "../";
			}
		}

		return $path;
	}
}

?>