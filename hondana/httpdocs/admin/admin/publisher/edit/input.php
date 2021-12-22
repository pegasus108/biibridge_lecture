<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	var $sqlPath = 'admin/admin/publisher/sql/';

	function execute() {

		if(isset($this->back)) {
			$this->back = null;

			return;
		}

		$db =& $this->_db;
		$db->setSqlDirectoryPath($this->sqlPath);

		$db->assign('publisher_no', $this->publisher_no);

		$row = $db->statementFetch('publisher');
		// ロゴ 添字変更 （logoのアップロードと変数名がカブるため）
		$row['savelogo'] = $row['logo'];
		unset($row['logo']);

		$this->setProperties($row);

		// スマートフォンサイト 閲覧フラグ退避
		$this->smp_old = $this->smp;

		if ($this->freeitem) {
			$this->free = $this->unserialize($this->freeitem);
		} else {
			$this->free = null;
		}

		if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/publisher/' . $this->id . '/images/custom/' . $this->savelogo) && !empty($this->savelogo)) {
			// DBのlogoフィールドに値がある
			$this->image = 'images/custom/' . $this->savelogo;
		} elseif(file_exists($_SERVER['DOCUMENT_ROOT'] . '/publisher/' . $this->id . '/images/custom/logo.gif')) {
			// DBのlogoフィールドは空だがlogo.gifは既に存在する
			$this->image = 'images/custom/logo.gif';
		} else {
			// ロゴ画像が存在しない
			$this->image = null;
		}

		if($this->url) {
			$this->publisher_url = $this->url;
		} else {
			$this->publisher_url = 'http://' . $this->id . '.hondana.jp/';
		}
	}

}
?>