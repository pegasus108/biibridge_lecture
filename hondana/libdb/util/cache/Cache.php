<?php
// キャッシュ削除クラスの読み込み
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/CacheManager.php');

class Cache {
	var $allowList = array(
		'^\/$',
		'^\/book\/b[0-9]+\.html$',
		'^\/author\/a[0-9]+\.html$',
		'^\/news\/$',
		'^\/news\/n[0-9]+\.html$',
		'^\/news\/nc[0-9]+\.html$',
		'^\/company\/$',
		'^\/company\/c[0-9]+\.html$',
		'^\/company\/cc[0-9]+\.html$',
		'^\/search\/next\.html$',
		'^\/search\/new\.html$',
		'^\/search\/next\.json$',
		'^\/rss\/news\/$',
		'^\/rss\/newbook\/$',
		'^\/error\/$',
		'^\/sitemap.xml$',
		'^\/smp\/$',
		'^\/smp\/book\/b[0-9]+\.html$',
		'^\/smp\/author\/a[0-9]+\.html$',
		'^\/smp\/news\/$',
		'^\/smp\/news\/n[0-9]+\.html$',
		'^\/smp\/news\/nc[0-9]+\.html$',
		'^\/smp\/company\/$',
		'^\/smp\/company\/c[0-9]+\.html$',
		'^\/smp\/company\/cc[0-9]+\.html$',
		'^\/smp\/search\/next\.html$',
		'^\/smp\/search\/new\.html$',
		'^\/smp\/error\/$',
	);
	var $cacheRootPath = null;
	// var $lifetime = 86400; // 24時間
	var $lifetime = 259200; // 24時間 * 3

	function __construct($domain) {
		$hondana = 'hondana';
		if(strpos($_SERVER['HTTP_HOST'], '.test.hondana.jp') !== false) {
			$hondana = 'hondanatest';
		}

		$domain = trim($domain);
		$domain = strtolower($domain);

		// ホビージャパン特別対応
		if($domain == 'hobbyjapan.co.jp') {
			$domain = 'hobbyjapan.hondana.jp';
		}
		// 東海教育研究所用特別対応
		if($domain == 'tokaiedu.co.jp') {
			$domain = 'tokaiedu.hondana.jp';
		}
		$this->cacheRootPath = sprintf('/home/%s/cache/%s', $hondana, $domain);
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
		// publisher_no 取得
		$publisher_number = 0;
		if(isset($_SESSION['publisher_no'])) {
			$publisher_number = $_SESSION['publisher_no'];
		} elseif(isset($_SERVER['HONDANA_PUBLISHER_NUMBER']) && is_numeric($_SERVER['HONDANA_PUBLISHER_NUMBER'])){
			$publisher_number = $_SERVER['HONDANA_PUBLISHER_NUMBER'];
		} else {
			return false;
		}
		// リアルタイム削除
		CacheManager::DeleteCache($publisher_number, null, $this->cacheRootPath . '/');

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
	function isCache($name) {
		if(strpos($_SERVER['HTTP_HOST'], '.vagrant.hondana.jp') !== false) {
			// 開発環境の場合は キャッシュ無効
			return false;
		}
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if(ereg("^DoCoMo", $ua) || ereg("^UP.Browser|^KDDI", $ua)){
			return false;
		}
		foreach($this->allowList as $rule) {
			$pattern = sprintf('/%s/u', $rule);
			if(preg_match($pattern, $name)) {
				return true;
			}
		}
		return false;
	}
	function removeSchedule($str) {
		if(empty($str)) {
			return false;
		}

		// publisher_no 取得
		$publisher_number = 0;
		if(isset($_SESSION['publisher_no'])) {
			$publisher_number = $_SESSION['publisher_no'];
		} elseif(isset($_SERVER['HONDANA_PUBLISHER_NUMBER']) && is_numeric($_SERVER['HONDANA_PUBLISHER_NUMBER'])){
			$publisher_number = $_SERVER['HONDANA_PUBLISHER_NUMBER'];
		} else {
			return false;
		}

		$time = strtotime($str); // 60秒ずらす。公開日は秒まで指定出来るが、atコマンドは分までしか指定ができない為

		// 予約削除
		CacheManager::DeleteCache($publisher_number, $time, $this->cacheRootPath . '/');

		return true;
	}
	function _convertCacheName($name) {
		return urlencode($name);
	}
}
?>
