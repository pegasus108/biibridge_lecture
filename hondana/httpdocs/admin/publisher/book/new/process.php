<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	var $sqlDirectoryPath = "admin/publisher/book/sql/";

	function prepare() {
		parent::prepare();

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

		$this->grtWidth = 1000;
		$this->grtHeight = 2000;
		$this->largeWidth = 1000;
		$this->largeHeight = 2000;
		$this->midWidth = 250;
		$this->midHeight = 500;
		$this->smlWidth = 78;
		$this->smlHeight = 110;
	}

	function execute() {
		// WYSIWYG入力フィールド内 フルパス → ルートパスへ変換して保存 (SSLページでエラーにならないように)
		$this->unsetFullpath(array('content'),$_SESSION['publisher_url']);

		$db =& $this->_db;
		$db->setSqlDirectoryPath($this->sqlDirectoryPath);
		$up =& $this->_uploader;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$db->begin();
		$row = $db->statementFetch('next_no');
		$next_no = $row['next_no'];
		$imagePosted = false;
		$key = "book{$this->upkey}";
		$this->new_image = null;
		$this->image = null;
		$db->assign('image', null);
		if($this->{$key}['http_path']){
			$imagePosted = true;

			$temp_path = $up->getTemporaryPath($key);
			$perma_path = $up->getPermanentPath($key, $next_no);

			// 元サイズの書影を書き出す出版社のリスト
			$originalsizepublisherlist = array(
				120, // 明窓出版
			);

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
			$db->assign('image', $this->image);
		}

		if($this->publisher['recommend_type'] == 2) {
			$key = 'recommend_image';
			if($this->recommend_image['http_path']) {
				$temp_path = $up->getTemporaryPath($key);
				$perma_path = $up->getPermanentPath($key, sprintf('recommend/%s', $next_no));

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

		// 追加するフォーマットをチェック
		$this->insertFormatList = array();
		if(!empty($this->book_format_list) && is_array($this->book_format_list)) {
			foreach($this->book_format_list as $key => $v){
				$b = array();
				$b['book_no_other'] = $v;
				$b['book_format'] = $this->book_format_l[$key];
				$b['book_format_other'] = $this->book_format_other_l[$key];
				$b['order'] = $key + 1;
				array_push($this->insertFormatList, $b);
			}
		}
		$db->assign('insertFormatList',$this->insertFormatList);

		/**
		 * Role Control
		 */
		$role = $this->denyBookCodeList();
		if(empty ($role))
			$role = array();

		$db->assign('role',$role);
		$db->statement('admin/publisher/book/sql/insert.sql');
		$db->commit();

		// 特設サイト
		$key = "spsiteimg{$this->upkey}";
		if(isset($this->{$key}[0])) {
			$db->assign('book_no',$next_no);
			$sp_site_link_list = $db->statementTree("special_site_link_list");
			$sp_site_link_idlist = array();
			$sp_site_link_namelist = array();
			foreach ($sp_site_link_list as $k => $v) {
				// 今回追加された画像ID
				$sp_site_link_idlist[] = $v['special_site_link_no'];
			}
			$filedir = $_SERVER['DOCUMENT_ROOT'] . "/web/img/uploads/specialsiteimg/{$next_no}";
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

		/**
		 * JPO Controller
		 */
		$db->assign("book_no",$next_no);
		if(!empty ($this->publisher["jpo"]) && $this->jpoSync){
			$syncAllowed = false;
			if(!count($this->errors_jpo)){
				$syncAllowed = true;
			}

			$db->begin();
			$db->assign('imagePsoted', $imagePosted);
			$db->assign('syncAllowed', $syncAllowed);

			$db->statement('admin/publisher/book/sql/jpo_flags_update.sql');
			$db->statement('admin/publisher/book/sql/jpo_insert_update.sql');
			$db->commit();

		}else{
			$db->begin();
			$db->assign('imagePsoted', false);
			$db->assign('syncAllowed', false);
			$db->statement('admin/publisher/book/sql/jpo_flags_update.sql');
			$db->statement('admin/publisher/book/sql/jpo_insert_update.sql');
			$db->commit();
		}

		$db->begin();
		$db->statement('admin/publisher/book/sql/honzuki_insert_update.sql');
		$db->commit();

		/**
		 * ActiBook
		 */
		$up =& $this->_uploaderAB;

		$key = 'actibook';
		if($up->exists($key)) {
			$temp_path = $up->getTemporaryPath($key);
			$perma_path = $up->getPermanentPath($key);
			$perma_path = sprintf('%s/%s/%s', dirname($perma_path), $next_no, basename($perma_path));

			$dirname = sprintf('%s/files/actibook/%s', $up->root, $next_no);
			$command = sprintf('rm -fr %s', $dirname);
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
		}

		/**
		 * 公開ステータスが「公開」で、公開日（public_date）が未来の場合に、キャッシュ削除のスケジュールを>追加
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

}
?>
