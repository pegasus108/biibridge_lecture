<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	function prepare() {
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/publisher/'.$_SESSION['id'].'/images/book',
			$siteroot . '/publisher/'.$_SESSION['id']
		);

		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);

		$this->maxfilesize = 51200000;
		$this->maxfilecount = 100;
	}

	function execute() {
		$this->upload_publisher_id = $_SESSION['publisher_no'];
	}

	function validate() {
		$db =& $this->_db;
		$up =& $this->_uploader;
		$this->errors = array();

		$key = 'zip';
		// アップロードファイル存在チェック
		if ($up->exists($key)) {
			$temp_path = $up->getTemporaryPath($key);
			$file = file_get_contents($temp_path);

			if($file){
				// ファイル容量判断
				if($this->{$key}["size"] > $this->maxfilesize){
					$this->errors['zip'] = 'ファイルサイズは50メガバイト以内でお願いいたします。';
					unlink($temp_path);
				} else {
					// zipファイルを解凍
					$unzip = sprintf("unzip %s -d %s", $temp_path, $up->getDir($key));
					$output = "";
					exec($unzip, $output);
					unlink($temp_path);
				}
			}else{
				$this->errors['zip'] = "ファイルをアップロードしてください。";
			}
		} else {
			$this->errors['zip'] = "ファイルをアップロードしてください。";
		}

		// エラー時はinputへ戻す
		if(count($this->errors)) {
			return 'input';
		}

		$uploadImageList = array();
		$in_isbn = array();
		foreach($up->getPathsByDir($up->getDir($key)) as $key => $file) {
			// 画像ファイル名判断
			if(preg_match('/^([0-9]{9}[0-9xX]{1}|[0-9]{13})\.(gif|jpg)$/u', $key)) {
				$isbn = preg_replace('/\.[a-zA-Z]+$/u', '', $key);
				$in_isbn[] = $isbn;
				$uploadImageList[$isbn]['file'] = $file;
			} else {
				unlink($file);
			}
		}

		$cnt = count($uploadImageList);
		if(!$cnt) {
			$this->errors['zip'] = "有効な画像ファイルが含まれていません。<br />ファイルの内容をご確認ください。";
		} elseif($cnt > $this->maxfilecount) {
			$this->errors['zip'] = "1度にアップロードする画像ファイル数は{$this->maxfilecount}点まででご調整ください。";
		} else {
			// 登録書籍の取得（ISBN判断用）
			$db->assign('publisher_no', $_SESSION['publisher_no']);
			$db->assign('in_isbn', $in_isbn);
			$result = $db->statement('admin/admin/import_images/sql/book_list.sql');
			$isbnList = $db->buildTree($result, 'isbn');
			$this->uploadImageList = array();
			// DBに登録されている書籍データとの突き合わせ
			foreach ($uploadImageList as $isbn => &$v) {
				if($isbnList[$isbn]) {
					$this->uploadImageList[$isbn]['file'] = $v['file'];
					$this->uploadImageList[$isbn]['book_no'] = $isbnList[$isbn]['book_no'];
				} else {
					unlink($v['file']);
				}
			}
		}

		if(!count($this->uploadImageList)) {
			$this->errors['zip'] = "有効な画像ファイルが含まれていません。<br />ファイルの内容をご確認ください。";
		}

		// エラー時はinputへ戻す
		if(count($this->errors)) {
			return 'input';
		}

		return true;
	}
}
?>