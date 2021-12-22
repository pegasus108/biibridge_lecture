<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	var $sqlPath = 'admin/admin/publisher/sql/';

	function prepare() {
		parent::prepare();

		//画像関係
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/publisher/'.$this->id.'/images/custom',
			$siteroot . '/publisher/'.$this->id
		);

		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);
	}

	function execute() {

		if ($this->free) {
			foreach($this->free as $k => $v){
				if(empty($v)){
					unset($this->free[$k]);
				}
			}
			$this->freeitem = serialize($this->free);
		} else {
			$this->freeitem = null;
		}

		$db =& $this->_db;
		$db->setSqlDirectoryPath($this->sqlPath);
		$up =& $this->_uploader;

		//画像
		$key = 'logo';
		if($this->logo['http_path']){
			$perma_path = $up->getPermanentPath($key, 'logo');

			//大文字GIFを小文字gifへ
			$perma_path = strtolower($perma_path);

			// フォームから送信されたファイル名をlogo.[ext]にオーバーライド
			$this->logo['name'] = substr($perma_path, strrpos($perma_path, '/')+1);

			unlink($perma_path);
			$up->copy($key, $perma_path);
			$up->remove($key);
		}

		$db->assign($this->getProperties());

		$db->begin();
		$db->statement('update');
		$db->commit();

		// 定数設定用confファイル生成
		$this->reload = 0;
		if($this->smp_old != $this->smp) {
			$env = '';
			if($this->smp == 1) {
				if(!empty($this->smp_path)) {
					$env = "SetEnv SMARTPHONEPATH {$this->smp_path}";
				} else {
					$env = 'SetEnv SMARTPHONEPATH /core/smp/';
				}
			}

			$filename = sprintf('%s/../etc/httpd/conf.d/env/%s.env.conf', $_SERVER['DOCUMENT_ROOT'], $this->id);
			$fp = fopen($filename, 'w');
			flock($fp, LOCK_EX);
			fwrite($fp, $env);
			flock($fp, LOCK_UN);
			fclose($fp);
			$this->reload = 1;
		}

		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>