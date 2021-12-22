<?php
class DataCache {
	var $cacheRootPath = null;
	// var $lifetime = 86400; // 24時間
	var $lifetime = 604800; // 24時間 * 7

	function __construct($publisher_no) {
		$hondana = 'hondana';
		if(strpos($_SERVER['HTTP_HOST'], '.stg.hondana.jp') !== false) {
			$hondana = 'hondanatest';
		}

		$publisher_no = trim($publisher_no);
		$publisher_no = strtolower($publisher_no);

		$this->cacheRootPath = sprintf('/home/%s/libdb/tmp/data_cache/%s', $hondana, $publisher_no);
		if(!file_exists($this->cacheRootPath)) {
			mkdir($this->cacheRootPath, 0777, true);
		}
	}
	function generate($name, $data) {
		// 生成する中身がない場合はキャッシュを生成しない
		if($data !== '') {
			$name = $this->_convertCacheName($name);
			$filename = sprintf('%s/%s', $this->cacheRootPath, $name);
			if(file_put_contents($filename, $data)) {
				return $name;
			}
		}
		return false;
	}
	function remove($name = null) {
		if(!empty($name)) {
			$name = $this->_convertCacheName($name);
			$filename = sprintf('%s/%s', $this->cacheRootPath, $name);
			unlink($filename);
		} else {
			// 該当出版社の全キャッシュ クリア
			foreach(glob(sprintf('%s/*', $this->cacheRootPath)) as $filename) {
				unlink($filename);
			}
		}
		return true;
	}
	function getCache($name) {
		$name = $this->_convertCacheName($name);
		$filename = sprintf('%s/%s', $this->cacheRootPath, $name);
		if(($data = file_get_contents($filename)) !== false) {
			return $data;
		}
		return false;
	}
	function isCached($name) {
		$name = $this->_convertCacheName($name);
		$filename = sprintf('%s/%s', $this->cacheRootPath, $name);
		if(file_exists($filename)) {
			if(abs(time() - filemtime($filename)) < $this->lifetime) {
				return true;
			}
		}
		return false;
	}
	function _convertCacheName($name) {
		return urlencode($name);
	}
}
?>
