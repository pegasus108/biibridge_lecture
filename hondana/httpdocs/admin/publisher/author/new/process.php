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
		$result = $db->statement('admin/publisher/author/sql/next_no.sql');
		$row = $db->fetch_assoc($result);
		$next_no = $row['next_no'];


		$key = 'author';
		if($this->author['http_path']){
			$perma_path = $up->getPermanentPath($key, $next_no);
			$up->copy($key, $perma_path);
			$up->remove($key);

			$db->assign('image', $up->convertHttpPath($perma_path));
		}

		$db->statement('admin/publisher/author/sql/insert.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>