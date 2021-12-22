<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Renderer.php');

class Action extends AuthAction {
	var $sqlDirectoryPath = "admin/publisher/book/sql/";
	var $grtWidth = 1000;
	var $grtHeight = 2000;
	var $largeWidth = 1000;
	var $largeHeight = 2000;
	var $midWidth = 250;
	var $midHeight = 500;
	var $smlWidth = 78;
	var $smlHeight = 110;

	function execute() {
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/img/uploads/book',
			$siteroot
		);
		$this->_uploaderAB = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/files/uploads/actibook',
			$siteroot
		);

		$db =& $this->_db;
		$up =& $this->_uploader;
		$this->_db->setSqlDirectoryPath($this->sqlDirectoryPath);

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$this->deleteLabelList = array();
		$this->insertLabelList = array();

		// 現在のレーベルデータ取得
		if(!empty($this->book_label_list)) {
			$this->oldBookLabelList = $db->statementTree("book_label", 'book_label_no');
			foreach($this->oldBookLabelList as $oldBookLabel){
				$add = true;
				foreach($this->book_label_list as $bookLabel){
					if($oldBookLabel['label_no'] == $bookLabel){
						$add = false;
						break;
					}
				}
				if($add){
					array_push($this->deleteLabelList, $oldBookLabel['book_label_no']);
				}
			}

			foreach($this->book_label_list as $bookLabel){
				$add = true;
				foreach($this->oldBookLabelList as $oldBookLabel){
					if($bookLabel == $oldBookLabel['label_no']){
						$add = false;
						break;
					}
				}
				if($add){
					array_push($this->insertLabelList, $bookLabel);
				}
			}
		}

		$this->deleteGenreList = array();
		$this->insertGenreList = array();

		// 現在のジャンルデータ取得
		$this->oldBookGenreList = $db->statementTree("book_genre", 'book_genre_no');
		foreach($this->oldBookGenreList as $oldBookGenre){
			$add = true;
			foreach($this->book_genre_list as $bookGenre){
				if($oldBookGenre['genre_no'] == $bookGenre){
					$add = false;
					break;
				}
			}
			if($add){
				array_push($this->deleteGenreList, $oldBookGenre['book_genre_no']);
			}
		}

		if(!empty($this->book_genre_list)) {
			foreach($this->book_genre_list as $bookGenre){
				$add = true;
				foreach($this->oldBookGenreList as $oldBookGenre){
					if($bookGenre == $oldBookGenre['genre_no']){
						$add = false;
						break;
					}
				}
				if($add){
					array_push($this->insertGenreList, $bookGenre);
				}
			}
		}

		$this->deleteSeriesList = array();
		$this->insertSeriesList = array();

		// 現在のシリーズデータ取得
		if(!empty($this->book_series_list)) {
			$this->oldBookSeriesList = $db->statementTree("book_series", 'book_series_no');
			foreach($this->oldBookSeriesList as $oldBookSeries){
				$add = true;
				foreach($this->book_series_list as $bookSeries){
					if($oldBookSeries['series_no'] == $bookSeries){
						$add = false;
						break;
					}
				}
				if($add){
					array_push($this->deleteSeriesList, $oldBookSeries['book_series_no']);
				}
			}

			foreach($this->book_series_list as $bookSeries){
				$add = true;
				foreach($this->oldBookSeriesList as $oldBookSeries){
					if($bookSeries == $oldBookSeries['series_no']){
						$add = false;
						break;
					}
				}
				if($add){
					array_push($this->insertSeriesList, $bookSeries);
				}
			}
		}

		$this->deleteAuthorList = array();
		$this->insertAuthorList = array();

		// 現在の著者データ取得
		$this->oldOpusList = $db->statementTree("opus", 'opus_no');
		if(!empty($this->opus_list)) {
			foreach($this->oldOpusList as $oldOpus){
				$add = true;
				foreach($this->opus_list as $opus){
					if($oldOpus['author_no'] == $opus){
						$add = false;
						break;
					}
				}
				if($add){
					array_push($this->deleteAuthorList, $oldOpus['opus_no']);
				}
			}

			foreach($this->opus_list as $opus){
				$add = true;
				foreach($this->oldOpusList as $oldOpus){
					if($opus == $oldOpus['author_no']){
						$add = false;
						break;
					}
				}
				if($add){
					array_push($this->insertAuthorList, $opus);
				}
			}
		}

		$this->deleteRelateList = array();
		$this->insertRelateList = array();
		$this->updateRelateList = array();

		// 現在の関連書籍データ取得
		$this->oldBookRelateList = $db->statementTree("book_relate", 'book_relate_no');

		// 変更・削除する関連書籍をチェック
		if(!empty($this->news_relate_list)) {
			foreach($this->oldBookRelateList as $oldkey => $oldBookRelate){
				$add = true;
				foreach($this->news_relate_list as $key => $bookRelate){
					if($oldBookRelate['book_relate_book_no'] == $bookRelate){
						$add = false;
						if(empty($oldBookRelate['order']) || $oldkey != $key) {
							$b = array();
							$b['id'] = $bookRelate;
							$b['order'] = $key + 1;
							array_push($this->updateRelateList, $b);
						}
						break;
					}
				}
				if($add){
					array_push($this->deleteRelateList, $oldBookRelate['book_relate_book_no']);
				}
			}

			// 追加する関連書籍をチェック
			foreach($this->news_relate_list as $key => $bookRelate){
				$add = true;
				foreach($this->oldBookRelateList as $oldBookRelate){
					if($bookRelate == $oldBookRelate['book_relate_book_no']){
						$add = false;
						break;
					}
				}
				if($add){
					$b = array();
					$b['id'] = $bookRelate;
					$b['order'] = $key + 1;
					array_push($this->insertRelateList, $b);
				}
			}
		}

		$this->deleteFormatList = array();
		$this->insertFormatList = array();
		$this->updateFormatList = array();

		// 現在のフォーマットデータ取得
		$result = $db->statement('admin/publisher/book/sql/book_format_book.sql');
		$this->bookFormatBook = $db->buildTree($result, 'id');

		// 変更・削除するフォーマットをチェック
		if(!empty($this->book_format_list)) {
			foreach($this->bookFormatBook as $oldkey => $oldBookFormat){
				$add = true;
				foreach($this->book_format_list as $key => $v){
					if($oldBookFormat['book_no_other'] == $v) {
						// すでに登録がある
						$add = false;
						$b = array();

						// フォーマットが変更になっているか？
						if($oldBookFormat['book_format'] != $this->book_format_l[$key]) {
							$b['book_format'] = $this->book_format_l[$key];
						}

						// フォーマット フリー入力が変更になっているか？
						if($oldBookFormat['book_format_other'] != $this->book_format_other_l[$key]) {
							$b['book_format_other'] = $this->book_format_other_l[$key];
						}

						// 上記に変更があれば book_noを設定
						if(!empty($b)) {
							$b['book_no_other'] = $v;
						}

						// フォーマット並び順が変更になっているか？
						if($oldBookFormat['order'] != $key + 1) {
							$b['order'] = $key + 1;
						}

						// 上記のどれかに変更があれば
						if(!empty($b)) {
							array_push($this->updateFormatList, $b);
						}
						break;
					}
				}
				if($add){
					array_push($this->deleteFormatList, $oldBookFormat['book_no_other']);
				}
			}

			// 追加するフォーマットをチェック
			foreach($this->book_format_list as $key => $v){
				$add = true;
				foreach($this->bookFormatBook as $oldBookFormat){
					if($v == $oldBookFormat['book_no_other']){
						$add = false;
						break;
					}
				}
				if($add){
					$b = array();
					$b['book_no_other'] = $v;
					$b['book_format'] = $this->book_format_l[$key];
					$b['book_format_other'] = $this->book_format_other_l[$key];
					$b['order'] = $key + 1;
					array_push($this->insertFormatList, $b);
				}
			}
		}

		// WYSIWYG入力フィールド内 フルパス → ルートパスへ変換して保存 (SSLページでエラーにならないように)
		$this->unsetFullpath(array('content'),$_SESSION['publisher_url']);

		$this->_db->setSqlDirectoryPath($this->sqlDirectoryPath);
		$db =& $this->_db;
		$up =& $this->_uploader;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$imagePosted = false;
		$key = "book{$this->upkey}";
		$this->new_image = null;
		if($this->{$key}['http_path']){
			$imagePosted = true;

			//もしグレートサイズにフラグが立っていたら
			if($this->publisher['great_img_status'] == 1) {
				unlink($_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_grt.$1',$this->image));
			}
			// 元サイズの書影を書き出す出版社のリスト
			$originalsizepublisherlist = array(
				120, // 明窓出版
			);
			if(array_search($this->publisher['publisher_no'], $originalsizepublisherlist) !== false) {
				// ■■ 元サイズ 対応出版社 ■■
				unlink($_SERVER['DOCUMENT_ROOT'].preg_replace('/book\//','book/original/',$row['image']));
			}
			$org_image = $_SERVER['DOCUMENT_ROOT'].$this->image;
			if (file_exists($org_image)) unlink($org_image);
			$mid_image = $_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_mid.$1',$this->image);
			if (file_exists($mid_image)) unlink($mid_image);
			$sml_image = $_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_sml.$1',$this->image);
			if (file_exists($sml_image)) unlink($sml_image);

			$temp_path = $up->getTemporaryPath($key);
			$perma_path = $up->getPermanentPath($key, $this->book_no);

			//もしグレートサイズにフラグが立っていたら
			if($this->publisher['great_img_status'] == 1) {
				// グレートサイズ 書影
				$this->convertGeometry($temp_path,$this->grtWidth,$this->grtHeight);
				$up->copy($key, preg_replace('/\.((?!com).+?)$/u','_grt.$1',$perma_path));
			}

			if(array_search($this->publisher['publisher_no'], $originalsizepublisherlist) === false) {
				// ■■ 元サイズ 未対応出版社 ■■
				//もしグレートサイズにフラグが立っていたら
				if($this->publisher['great_img_status'] == 1) {
					// ラージサイズ 書影
					$this->convertGeometry($temp_path,$this->largeWidth,$this->largeHeight);
					$up->copy($key, $perma_path);
				}else{
					// ラージサイズ 書影
					$up->copy($key, $perma_path);
				}
			} else {
				// ■■ 元サイズ 対応出版社 ■■
				$up->copy($key, preg_replace('/book\//','book/original/',$perma_path));

				// ラージサイズ 書影
				$this->convertGeometry($temp_path,$this->largeWidth,$this->largeHeight);
				$up->copy($key, $perma_path);
			}

			// ミドルサイズ 書影
			$this->convertGeometry($temp_path,$this->midWidth,$this->midHeight);
			$up->copy($key, preg_replace('/\.((?!com).+?)$/u','_mid.$1',$perma_path));

			// スモールサイズ 書影
			$this->convertGeometry($temp_path,$this->smlWidth,$this->smlHeight);
			$up->copy($key, preg_replace('/\.((?!com).+?)$/u','_sml.$1',$perma_path));

			$up->remove($key);
			$this->new_image = $up->convertHttpPath($perma_path);
			$this->image = $this->new_image;
			$db->assign('new_image', $this->new_image);
		}elseif($this->clear_image){
			//もしグレートサイズにフラグが立っていたら
			if($this->publisher['great_img_status'] == 1) {
				unlink($_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_grt.$1',$this->image));
			}
			$org_image = $_SERVER['DOCUMENT_ROOT'].$this->image;
			if (file_exists($org_image)) unlink($org_image);
			$mid_image = $_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_mid.$1',$this->image);
			if (file_exists($mid_image)) unlink($mid_image);
			$sml_image = $_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_sml.$1',$this->image);
			if (file_exists($sml_image)) unlink($sml_image);
		}

		if($this->publisher['recommend_type'] == 2) {
			if(!isset($this->recommend_status) && isset($this->old_recommend_image) && file_exists($_SERVER['DOCUMENT_ROOT'].$this->old_recommend_image)) {
				unlink($_SERVER['DOCUMENT_ROOT'].$this->old_recommend_image);
			}

			$key = 'recommend_image';
			if($this->recommend_image['http_path']){

				$temp_path = $up->getTemporaryPath($key);
				$perma_path = $up->getPermanentPath($key, sprintf('recommend/%s', $this->book_no));

				if(!file_exists(dirname($perma_path))) {
					mkdir(dirname($perma_path));
					chmod(dirname($perma_path), 0777);
				}

				$command = sprintf('convert -thumbnail 694x\> -gravity center -crop 694x228+0+0 %s %s', $temp_path, $temp_path);
				exec($command);

				$up->copy($key, $perma_path);
				$up->remove($key);
			}
		}

		$freeitem = null;
		if (!empty($this->free) && is_array($this->free)) {
			foreach ($this->free as $key => $value) {
				if(!empty($value)) {
					$freeitem = serialize($this->free);
				}
			}
		}
		$db->assign('freeitem',$freeitem);

		/**
		 * Role Control
		 */
		$role = $this->denyBookCodeList();
		if(empty ($role)) {
			$role = array();
		}
		$db->assign('role',$role);

		// 特設サイトリンクの並び順 末尾取得
		$spsite_sort = 0;
		if(!empty($this->oldSpsite)) {
			foreach ($this->oldSpsite as $k => $v) {
				if($spsite_sort < $v['sort']) {
					// 保存している順番より後の場合は更新
					$spsite_sort = $v['sort'];
				}
			}
		}
		$db->assign('spsite_sort',$spsite_sort);

		// キャンペーンサイトURLの並び順 末尾取得
		$cpsite_sort = 0;
		if(!empty($this->oldCpsite)) {
			foreach ($this->oldCpsite as $k => $v) {
				if($cpsite_sort < $v['sort']) {
					// 保存している順番より後の場合は更新
					$cpsite_sort = $v['sort'];
				}
			}
		}

		$db->assign('cpsite_sort',$cpsite_sort);
		$db->statement("update");
		$db->commit();

		// 特設サイト
		$key = "spsiteimg{$this->upkey}";
		if(!empty($this->{$key})) {
			$sp_site_link_list = $db->statementTree("special_site_link_list");
			$sp_site_link_idlist = array();
			$sp_site_link_namelist = array();
			foreach ($sp_site_link_list as $k => $v) {
				if(empty($this->oldSpsite) || !array_key_exists($v['special_site_link_no'], $this->oldSpsite)) {
					// 追加前のデータと比較して 存在しなかったもの = 今回追加されたデータID
					$sp_site_link_idlist[] = $v['special_site_link_no'];
				}
			}
			$filedir = $_SERVER['DOCUMENT_ROOT'] . "/web/img/uploads/specialsiteimg/{$this->book_no}";
			if(!file_exists($filedir)) {
				// ディレクトリがない場合は作成
				mkdir($filedir);
			}
			foreach ($this->{$key} as $k => $v) {
				// 画像一時保存先取得
				$temp_path = $up->getTemporaryPath($key . '_' . $k);
				// 拡張子取得
				$fileinfo = pathinfo($temp_path);
				$ext = $fileinfo['extension'];
				// ファイルコピー
				$up->copy($key . '_' . $k, "{$filedir}/{$sp_site_link_idlist[$k]}.{$ext}");

				// ファイル名 格納
				$sp_site_link_namelist[$sp_site_link_idlist[$k]] = "{$sp_site_link_idlist[$k]}.{$ext}";
			}

			// ファイル名 DB更新
			$db->assign('special_site_link_namelist',$sp_site_link_namelist);
			$db->begin();
			$db->statement("update_sp_site_link_name");
			$db->commit();
		}

		// 特設サイト 画像ファイル削除
		$key = "spsite_delete{$this->upkey}";
		if(!empty($this->{$key})) {
			foreach ($this->{$key} as $k => $v) {
				unlink($siteroot . "/web/img/uploads/specialsiteimg/{$this->book_no}/{$this->oldSpsite[$v]['imagefile']}");
			}
		}

		/**
		 * JPO Controller
		 */
		if(!empty ($this->publisher["jpo"]) && $this->jpoSync){
			$syncAllowed = false;
			if(!count($this->errors_jpo)){
				$syncAllowed = true;
			}
			$db->begin();
			$db->assign('imagePsoted', $imagePosted);
			$db->assign('syncAllowed', $syncAllowed);
			$db->statement("jpo_flags_update");
			$db->statement("jpo_insert_update");
			$db->commit();
		}else{
			$db->begin();
			$db->assign('imagePsoted', false);
			$db->assign('syncAllowed', false);
			$db->statement("jpo_flags_update");
			$db->statement("jpo_insert_update");
			$db->commit();
		}
		$db->begin();
		$db->statement("honzuki_insert_update");
		$db->commit();

		/**
		 * ActiBook Controleller
		 */
		$up =& $this->_uploaderAB;

		$key = 'actibook';

//		if($up->exists($key)) {
		if($this->actibook['http_path']) {
			$temp_path = $up->getTemporaryPath($key);
			$perma_path = $up->getPermanentPath($key);
			$perma_path = sprintf('%s/%s/%s', dirname($perma_path), $this->book_no, basename($perma_path));

			$dirname = sprintf('%s/files/actibook/%s', $up->root, $this->book_no);
			$command = sprintf('rm -fr %s', $dirname);
			$command = sprintf('rm -fr %s', $dirname.'/*');
			exec($command);

			$this->mkdir($dirname, 0777, true);

			rename($temp_path, $perma_path);

			$commond = sprintf("unzip %s -d %s", $perma_path, dirname($perma_path));
			$output = "";
			exec($commond, $output);

			$commond = sprintf("chmod -R 777 %s", dirname($perma_path));
			$output = "";
			exec($commond, $output);

			unlink($perma_path);

			if($handle = opendir(dirname($perma_path))) {
				while(false !== ($file = readdir($handle))) {
					if($file == "actibook") {
						$command = sprintf('mv %s %s', sprintf('%s/%s/*', dirname($perma_path), $file), dirname($perma_path));
						exec($command);

						break;
					}
				}
				closedir($handle);
				rmdir(sprintf('%s/%s', dirname($perma_path), $file));
			}

		}elseif($this->clear_actibook){

			$dirname = sprintf('%s/publisher/%s/files/actibook/%s', $_SERVER['DOCUMENT_ROOT'], $_SESSION['id'], $this->book_no);

			if(file_exists($dirname.'/_SWF_Window.html')) {
//				$command = sprintf('rm -fr %s', $dirname);
				$command = sprintf('rm -fr %s', $dirname.'/*');
				exec($command);
			}
		}

		/**
		 * comp old and new data
		 */
		$changed = $this->checkChengedFields();
		if(!empty($changed) && !empty($this->publisher['book_notice_mail'])){
			// 書誌データ編集メール送信
			$this->sendChangeNotice($changed);
		}

		/**
		 * 公開ステータスが「公開」で、公開日（public_date）が未来の場合に、キャッシュ削除のスケジュールを追加
		 */
		if($this->public_status == 1 && strtotime($this->public_date) > time()) {
			$this->removeCacheSchedule($this->public_date);
		}

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}

	function mkdir($dirname = null, $permission = 0777, $recursive = false) {
		if(!isset($dirname)) {
			return false;
		}

		if($recursive) {
			$dirnameList = array();
			$dirnameList = explode('/', $dirname);

			array_shift($dirnameList);
			$dir = '';
			foreach($dirnameList as $value) {
				$dir .= '/' . $value;
				if(file_exists($dir)) {
				} else {
					mkdir($dir);
					chmod($dir, $permission);
				}
			}
		} else {
			if(file_exists($dirname)) {
			} else {
				mkdir($dirname);
				chmod($dirname, $permission);
			}
		}
		return true;
	}

	function denyBookCodeList(){
		$rs = array();
		$sql = "
			select ab.code
			from
				role r left join role_access_book rab on r.role_no=rab.role_no
				left join access_book ab on rab.access_book_no = ab.access_book_no
			where r.role_no = '{$this->publisher_account["role_no"]}' and ab.code is not null;";

		$rs = $this->_db->query($sql);
		$rs = $this->_db->buildTree($rs,"code");

		return $rs;
	}


	/**
	 * [checkChengedFields カラムが編集されたか確認]
	 * @return [type] [description]
	 */
	function checkChengedFields(){
		$db =& $this->_db;

		$fields = $db->statementTree("book_field_list");
		$changedItems = array();
		foreach ($fields as $k => $v){
			switch ($v["book_field_no"]) {
				case 1:
					if($this->oldCurrentBook["name"] != $this->name){
						$changedItems["name"] = "name";
					}
					break;
				case 2:
					if($this->oldCurrentBook["kana"] != $this->kana){
						$changedItems["kana"] = "kana";
					}
					break;
				case 3:
					break;
				case 4:
					if($this->oldCurrentBook["sub_name"] != $this->sub_name){
						$changedItems["sub_name"] = "sub_name";
					}
					break;
				case 5:
					if($this->oldCurrentBook["sub_kana"] != $this->sub_kana){
						$changedItems["sub_kana"] = "sub_kana";
					}
					break;
				case 6:
					if(!empty($this->deleteGenreList) || !empty($this->insertGenreList)){
						$changedItems["genre"] = "genre";
					}
					break;
				case 7:
					if(!empty($this->deleteSeriesList) || !empty($this->insertSeriesList)){
						$changedItems["series"] = "series";
					}
					break;
				case 8:
					break;
				case 9:
					if(!empty($this->deleteAuthorList) || !empty($this->insertAuthorList)){
						$changedItems["opus"] = "opus";
					}
					break;
				case 10:
					$changed = false;
					foreach($this->oldCurrentBook["author_type_list"] as $key=>$val){
						if($val != $this->author_type_list[$key]){
							$changed =true;
							break;
						}
					}
					if($changed){
						$changedItems["author_type"] = "author_type";
					}
					break;
				case 11:
					if($this->oldCurrentBook["isbn"] != $this->isbn){
						$changedItems["isbn"] = "isbn";
					}
					break;
				case 12:
					if($this->oldCurrentBook["c_code"] != $this->c_code){
						$changedItems["c_code"] = "c_code";
					}
					break;
				case 13:
					if($this->oldCurrentBook["magazine_code"] != $this->magazine_code){
						$changedItems["magazine_code"] = "magazine_code";
					}
					break;
				case 14:
					if(str_replace(" 00:00:00", "", $this->oldCurrentBook["book_date"]) != $this->book_date){
						$changedItems["book_date"] = "book_date";
					}
					break;
				case 15:
					if(str_replace(" 00:00:00", "", $this->oldCurrentBook["release_date"]) != $this->release_date){
						$changedItems["release_date"] = "release_date";
					}
					break;
				case 16:
					if($this->oldCurrentBook["version"] != $this->version){
						$changedItems["version"] = "version";
					}
					break;
				case 17:
					if($this->oldCurrentBook["book_size_no"] != $this->book_size_no){
						$changedItems["book_size_no"] = "book_size_no";
					}
					break;
				case 18:
					break;
				case 19:
					if($this->oldCurrentBook["page"] != $this->page){
						$changedItems["page"] = "page";
					}
					break;
				case 20:
					if($this->oldCurrentBook["price"] != $this->price){
						$changedItems["price"] = "price";
					}
					break;
				case 21:
					break;
				case 22:
					break;
				case 23:
					break;
				case 24:
					break;
				case 25:
					break;
				case 26:
					break;
				case 27:
					break;
				case 28:
					break;
				case 29:
					break;
				case 30:
					break;
				case 31:
					break;
				case 32:
					break;
				case 33:
					break;
				case 34:
					break;
				case 35:
					break;
				case 36:
					break;
				case 37:
					// レーベル
					if(!empty($this->deleteLabelList) || !empty($this->insertLabelList)){
						$changedItems["label"] = "label";
					}
					break;
			}

		}

		return $changedItems;
	}


	function sendChangeNotice($fields) {

		$publisher_name = $this->publisher['name'];
		$publisher_mail = $this->publisher['book_notice_mail'];
		if(empty($publisher_mail)){
			$publisher_mail = $this->publisher['person_mail'];
		}

		$subject = "【書誌情報変更】{$this->oldCurrentBook["name"]}";
		$envelope_from = "info@hondana.jp";

		$mailer = new Renderer();
		$mailer->template_dir = realpath(dirname(__FILE__) . '/..');
		$mailer->assign($this->getProperties());
		$mailer->assign("fields",$fields);

		$customer = $this->mail;
		$system = $publisher_mail;
		$subject = $this->convertEncodingHeader($subject);
		$body = $mailer->fetch('edit/mail.html');
		$body = $this->convertEncodingBody($body);

		return $this->send($system, $subject, $body, $customer, $envelope_from);

	}

	/**
	 * Convert display encoding.
	 * @access private
	 * @return string
	 */
	function convertEncodingDisplay($str, $enc = 'UTF-8') {
		$str = mb_convert_encoding($str, $enc, 'JIS');

		return $str;
	}

	/**
	 * Convert mail body encoding.
	 * @access private
	 * @return string
	 */
	function convertEncodingBody($str, $enc = 'UTF-8') {
		$str = mb_convert_encoding($str, 'JIS', $enc);

		return $str;
	}

	/**
	 * Convert mail header encoding.
	 * @access private
	 * @return string
	 */
	function convertEncodingHeader($str, $enc = 'UTF-8') {
		$str = $this->convertEncodingBody($str, $enc);
		$str = '=?iso-2022-jp?B?' . base64_encode($str) . '?=';

		return $str;
	}

	/**
	 * Send mail.
	 * @access private
	 */
	function send($to, $subject, $body, $from, $envelope_from) {
		return mail($to, $subject, $body, "From: " . $from, "-f" . $envelope_from);
	}

	function fold($str, $length = 70, $enc = 'UTF-8') {
		$str = str_replace("\r\n", "\n", $str);
		$str = str_replace("\r", "\n", $str);
		$lines = mb_split("\n", $str);

		foreach($lines as $key => $line) {
			$works = '';
			$pos = 0;
			while ($pos + $length < strlen($line)) {
				$works .= mb_strcut($line, $pos, $length, $enc) . "\n";
				$pos += $length;
			}
			$lines[$key] = $works . mb_strcut($line, $pos);
		}

		return implode("\n", $lines);
	}
}
?>