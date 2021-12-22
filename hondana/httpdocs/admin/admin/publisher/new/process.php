<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Renderer.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	var $sqlPath = 'admin/admin/publisher/sql/';

	function prepare() {
		parent::prepare();

		// 画像関係
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/img/uploads/common',
			$siteroot
		);
	}

	function execute() {
		$db =& $this->_db;
		$db->setSqlDirectoryPath($this->sqlPath);
		$up =& $this->_uploader;

		// 画像
		$key = 'logo';
		if(!empty($this->logo)){
			$perma_path = $up->getPermanentPath($key, 'logo');

			// 大文字GIFを小文字gifへ
			$perma_path = strtolower($perma_path);

			// フォームから送信されたファイル名をlogo.[ext]にオーバーライド
			$this->logo['name'] = substr($perma_path, strrpos($perma_path, '/')+1);

			unlink($perma_path);
			$up->copy($key, $perma_path);
			$up->remove($key);
		}

		$db->assign($this->getProperties());
		$db->begin();
		$result = $db->statement('insert');
		$row = $db->fetch_assoc($result[0]);
		$db->commit();

		$conf = new Renderer();
		$conf->template_dir = realpath(dirname(__FILE__) . '/..');

		$conf->assign($this->getProperties());
		$conf->assign('publisher_no', $row['add_publisher_no']);

		$filename = "";


		// 画像ディレクトリ作成
		$dirname = sprintf("%s/web/img/uploads", $_SERVER['DOCUMENT_ROOT'], $this->id);
		mkdir($dirname);
		chmod($dirname, 0777);

		$dirname = sprintf("%s/web/img/uploads/custom", $_SERVER['DOCUMENT_ROOT'], $this->id);
		mkdir($dirname);
		chmod($dirname, 0777);

		$dirname = sprintf("%s/web/img/uploads/banner", $_SERVER['DOCUMENT_ROOT'], $this->id);
		mkdir($dirname);
		chmod($dirname, 0777);

		$dirname = sprintf("%s/web/img/uploads/book", $_SERVER['DOCUMENT_ROOT'], $this->id);
		mkdir($dirname);
		chmod($dirname, 0777);

		$dirname = sprintf("%s/web/img/uploads/author", $_SERVER['DOCUMENT_ROOT'], $this->id);
		mkdir($dirname);
		chmod($dirname, 0777);

		$dirname = "";

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>