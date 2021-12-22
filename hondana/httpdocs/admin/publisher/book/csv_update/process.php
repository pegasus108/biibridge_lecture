<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	function execute() {
		// 入力データのインポート開始
		$startTime = microtime(true);
		$this->log('[csv_update]Start import.');

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$this->statuses = array();
		foreach($this->bookList as $k => $book){
			$this->statuses[$book[1]][] = $book[0];
		}

//		print_r($this->statuses);
//		exit();

		$db->assign($this->getProperties());

		$db->begin();
		$db->statement('admin/publisher/book/sql/csv_update.sql');
		$db->commit();

		// 入力データのインポート終了
		$endTime = microtime(true);
		$this->log(sprintf('[csv_update]Complete import. time:%ss count:%d', $endTime - $startTime, $this->rowCount));

		$this->clearProperties();

		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
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
}
?>