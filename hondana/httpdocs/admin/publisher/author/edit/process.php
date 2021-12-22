<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	function prepare() {
		parent::prepare();

		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/img/uploads/author',
			$siteroot
		);
	}

	function execute() {
		$db =& $this->_db;
		$up =& $this->_uploader;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);


		$db->begin();

		$key = 'author';
		if($this->author['http_path']){
			unlink($_SERVER['DOCUMENT_ROOT'] . $this->image);
			$perma_path = $up->getPermanentPath($key, $this->author_no);
			$up->copy($key, $perma_path);
			$up->remove($key);
			$db->assign('new_image', $up->convertHttpPath($perma_path));
		}elseif($this->clear_image){
			unlink($_SERVER['DOCUMENT_ROOT'] . $this->image);
		}

		$db->statement('admin/publisher/author/sql/update.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>