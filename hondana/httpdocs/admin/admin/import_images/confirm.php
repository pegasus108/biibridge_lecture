<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
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
		$key = 'zip';
		if ($up->exists($key)) {
			$temp_path = $up->getTemporaryPath($key);

			$file = file_get_contents($temp_path);

			if($file){
				$unzip = sprintf("unzip %s -d %s", $temp_path, $up->getDir($key));
				$output = "";
				exec($unzip, $output);
				unlink($temp_path);
			}else{
				return false;
			}
		} else {
			$this->errors['zip'] = "zipファイルをアップロードして下さい。";
		}
	}

	function execute() {
		$this->upload_publisher_id = $this->publisherList[$this->upload_publisher_no]['id'];
	}

	function validate() {
		$db =& $this->_db;
		$up =& $this->_uploader;
		$key = 'zip';

		if($this->errors['zip']) {
			// ファイルアップロードエラー
			return 'input';
		}

		$this->errors = array();

		if(empty($this->upload_publisher_no)) {
			$this->errors['upload_publisher_no'] = "適用先出版社を選択して下さい。";

		} else {
			$uploadImageList = array();
			$this->uploadImageList = array();
			if($this->idformat == 1) {
				// ISBNでの取り込み
				$in_isbn = array();
				foreach($up->getPathsByDir($up->getDir($key)) as $key => $file) {
					// ファイル名にISBNが設定されているもののみ取得
					if(preg_match('/^([0-9]{9}[0-9xX]{1}|[0-9]{13})\.(gif|jpg)$/u', $key)) {
						// 拡張子削除
						$isbn = preg_replace('/\.[a-zA-Z]+$/u', '', $key);
						$in_isbn[] = $isbn;
						$uploadImageList[$isbn]['file'] = $file;
					} else {
						unlink($file);
					}
				}
				if(!count($uploadImageList)) {
					// 有効なファイル名のファイルが存在しなかった場合
					$this->errors['zip'] = "有効な画像ファイルが含まれていません。<br />ファイルの内容をご確認ください。";
				} else {
					$db->assign('publisher_no', $this->upload_publisher_no);
					$db->assign('in_isbn', $in_isbn);
					$result = $db->statement('admin/admin/import_images/sql/book_list.sql');
					$isbnList = $db->buildTree($result, 'isbn');
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
			} elseif($this->idformat == 2) {
				// bookIDでの取り込み
				$in_bookid = array();
				foreach($up->getPathsByDir($up->getDir($key)) as $key => $file) {
					// ファイル名にbookIDが設定されているもののみ取得
					if(preg_match('/^[0-9]*\.(gif|jpg)$/u', $key)) {
						// 拡張子削除
						$bookid = preg_replace('/\.[a-zA-Z]+$/u', '', $key);
						$in_bookid[] = $bookid;
						$uploadImageList[$bookid]['file'] = $file;
					} else {
						unlink($file);
					}
				}
				if(!count($uploadImageList)) {
					// 有効なファイル名のファイルが存在しなかった場合
					$this->errors['zip'] = "有効な画像ファイルが含まれていません。<br />ファイルの内容をご確認ください。";
				} else {
					$db->assign('publisher_no', $this->upload_publisher_no);
					$db->assign('in_bookid', $in_bookid);
					$result = $db->statement('admin/admin/import_images/sql/book_list_bookid.sql');
					$bookidList = $db->buildTree($result, 'book_no');
					// DBに登録されている書籍データとの突き合わせ
					foreach ($uploadImageList as $bookid => &$v) {
						if($bookidList[$bookid]) {
							$this->uploadImageList[$bookid]['file'] = $v['file'];
							$this->uploadImageList[$bookid]['book_no'] = $bookidList[$bookid]['book_no'];
						} else {
							unlink($v['file']);
						}
					}
				}
			}

			if(!count($this->uploadImageList)) {
				$this->errors['zip'] = "有効な画像ファイルが含まれていません。<br />ファイルの内容をご確認ください。";
			}
		}

		if(count($this->errors)) {
			return 'input';
		}

		return true;
	}
}
?>