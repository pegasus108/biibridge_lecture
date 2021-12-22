<?php

define('__QUERYCACHE_BASE_DIR__', '/tmp/querycache/');
define('__QUERYCACHE_DELETE_RESERVAION_DIR__', '/tmp/querycache/reserv_delete/');
if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']=='220.151.170.232'){
define('__QUERYCACHE_CACHE_PROSESS_FILE__', 'cache.log');
}

//define('__QUERYCACHE_EXPIRATIONHOUR__', 96);
// Memcachedの場合、30日を越える事はできない。(720h)
define('__QUERYCACHE_EXPIRATIONHOUR__', 720);
//define('__QUERYCACHE_EXPIRATIONHOUR__', 0.01);


//if( preg_match('/toyokan/', $_SERVER['HTTP_HOST']) || preg_match('/keisoshobo/', $_SERVER['HTTP_HOST']) ){
if(isset($_SERVER['QUERY_CACHE_ENABLE']) && $_SERVER['QUERY_CACHE_ENABLE']){
	//define('__QUERYCACHE_ENABLE__', true);
//}elseif($_SERVER['REMOTE_ADDR'] == '220.151.170.232' || $_SERVER['REMOTE_ADDR'] == '183.76.168.73'){
	//define('__QUERYCACHE_ENABLE__', true);
}

//if(isset($_SERVER['QUERY_CACHE_ENABLE']) && $_SERVER['QUERY_CACHE_ENABLE']){
//if($_SERVER['REMOTE_ADDR'] == '220.151.170.232' || $_SERVER['REMOTE_ADDR'] == '183.76.168.73'){
//	define('__QUERYCACHE_ENABLE__', true);
//}
//}

define('__QUERYCACHE_USE_MEMCACHE__', 2);

class DatabaseCache{
	var $cache = array();
	var $cachePointer = array();
	var $increment_resource_no = 1;
	var $cacheEnable = false;
	var $modeDebug = false;
	var $objMemcache = null;

	var $intForcePublisherNo = null;


	function DatabaseCache($publisher_no=false){
		//if($_SERVER['REMOTE_ADDR'] == '220.151.170.232'){
		//      $this->modeDebug = true;
		//}
		if(is_numeric($publisher_no)){
			$this->intForcePublisherNo = $publisher_no;
			//$this->cacheEnable = true;
		}else{
			$publisher_no = $this->getPublisherNumber();
			if(!is_numeric($publisher_no)){
				return;
			}
		}

		if(defined('__QUERYCACHE_ENABLE__') && __QUERYCACHE_ENABLE__){
			// POSTだと更新クエリでキャッシュ削除してくれないので条件コメントアウト
			//if($_SERVER["REQUEST_METHOD"] == "GET"){
			if(isset($_SERVER["HTTP_HOST"])){
				if( !preg_match('/\/home\/hondana\/jpo/', $_SERVER["DOCUMENT_ROOT"])
				){
					$this->cacheEnable = true;
				}
			}
			//}

			if(isset($_SERVER['QUERY_CACHE_ENABLE']) && $_SERVER['QUERY_CACHE_ENABLE']==2){
				$this->cacheEnable = __QUERYCACHE_USE_MEMCACHE__;
				$this->create_memcache();
			}else{
				$this->cacheEnable = 1;
			}
		}

		if(!$this->cacheEnable){return;}

		$cache_dir = $this->getCacheFilePath();
		if(!file_exists($cache_dir)){

			if(!file_exists(dirname($cache_dir))){
				if(!file_exists(dirname(dirname($cache_dir)))){
					mkdir(dirname(dirname($cache_dir)));
				}
				mkdir(dirname($cache_dir));
			}
			mkdir($cache_dir);
			chmod($cache_dir, 0700);

		}
	}

	function create_memcache(){
		if(!defined('__QUERYCACHE_ENABLE__') && !__QUERYCACHE_ENABLE__){
			return false;
		}
		$this->objMemcache = new Memcache;
		$this->objMemcache->connect('127.0.0.1',11211);
		return true;
	}

	function read($key){
		$result = null;
		if($this->cacheEnable){
			if($this->cacheEnable===__QUERYCACHE_USE_MEMCACHE__){
				$data = $this->objMemcache->get($key);
				if($data===false){
					//$this->logging("+NO: [memcached] Read cache file : {$key}.");
				}else{
					$result = $this->addResource($data);
					$this->logging("+OK: [memcached] Read cache file : {$key}.");
				}
			}else{
				$cache_file_path = $this->getCacheFilePath($key);
				if(file_exists($cache_file_path)){
					// 有効期限の確認
					$cache_timestamp = filemtime($cache_file_path);
					if( time() > (filemtime($cache_file_path) + (__QUERYCACHE_EXPIRATIONHOUR__ * 60 * 60) )){
						$this->clear($key);
						return null;
					}

					$cacheData = file_get_contents($cache_file_path);
					$data = unserialize($cacheData);
					$result = $this->addResource($data);

					$this->logging("+OK: [filecache] Read cache file : {$cache_file_path}.");
					//$this->logging(print_r($_SERVER, true));
				}
			}
		}
		return $result;
	}

	function write($key, $data){
		if($this->cacheEnable){
			if($this->cacheEnable===__QUERYCACHE_USE_MEMCACHE__){
				$expire = __QUERYCACHE_EXPIRATIONHOUR__ * 60 * 60;
				// 有効期限がMemcacheのMAXであれば、上限-1に設定する
				if($expire >= 2592000){
					$expire = 2591999;
				}

				// 保存するキーをキーリストに追加
				$this->add_memcache_key($key);

				if($this->objMemcache->set($key, $data, MEMCACHE_COMPRESSED, $expire)){
					$this->logging("+OK: [memcached] Write cache file: {$key}.");
				}else{
					$this->logging("-ERR:[memcached] Failed to write cache: (".$this->getPublisherNumber().") :{$key}.");
				}
			}else{
				$cache_file_path = $this->getCacheFilePath($key);
				file_put_contents($cache_file_path, serialize($data));
				chmod($cache_file_path, 0600);
				$this->logging("+OK: [filecache] Write cache file: {$cache_file_path}.");
			}
		}
	}

	/**
	 * Memcached管理用キー。キーの一覧ほ保存して、削除時に利用。
	 */
	function add_memcache_key($key){
		$list_key = $this->get_memcache_list_key($this->getPublisherNumber());
		$aryKey = $this->objMemcache->get($list_key);

		if(!is_array($aryKey)){
			$aryKey = array();
		}

		$aryKey[] = $key;

		if($this->objMemcache->set($list_key, $aryKey, 0, 0)){
			$this->logging("+OK: [memcached] add key: {$key} to {$list_key}.");
		}else{
			$this->logging("-ERR:[memcached] Failed to add key: {$key} to {$list_key}.");
		}

	}
	function get_memcache_list_key($publisher_no){
		$memcache_list_key = 'key_list_pub_no_'.$publisher_no;
		return $memcache_list_key;
	}


	/**
	 * キャッシュを削除する。
	 *
	 * 公開ページと更新系クエリが走る管理画面で<VirtualHost>が異なるため、
	 * ファイルキャッシュとmemcache両方削除する。
	 */
	function clear($key){
		// memcached 削除(memcacheの場合、ここはほとんど意味がない)
			$flgMemcache = true;
			if(!is_object($this->objMemcache)){
				if(!$this->create_memcache()){
					$flgMemcache = false;
				}
			}

			if($flgMemcache){
				$list_key = $this->get_memcache_list_key($this->getPublisherNumber());
				$aryKey = $this->objMemcache->get($list_key);
				// 特定keyキャッシュ削除
				$this->objMemcache->delete($key);

				// リストからも削除
				if(isset($aryKey[$key])){unset($aryKey[$key]);}
				$this->objMemcache->delete($list_key);
				if($this->objMemcache->set($list_key, $aryKey, 0, 0)){
					$this->logging("+OK: [memcached] Clear cache : {$list_key}.");
				}else{
					$this->logging("-ERR:[memcached] Failed to clear cache: {$list_key}.");
				}
			}

		// ファイルキャッシュ削除
			$cache_file_path = $this->getCacheFilePath($key);
			if(file_exists($cache_file_path)){
				unlink($cache_file_path);
				$this->logging("Clear cache file: {$cache_file_path}.");
			}
	}

	function clearAll(){
		// memcached 削除
			$flgMemcache = true;
			if(!is_object($this->objMemcache)){
				if(!$this->create_memcache()){
					$flgMemcache = false;
				}
			}

			if($flgMemcache){
				$list_key = $this->get_memcache_list_key($this->getPublisherNumber());
				$aryKey = $this->objMemcache->get($list_key);
				// 出版社別、全キャッシュ削除
				if(is_array($aryKey)){
					foreach($aryKey as $akey){
						$this->objMemcache->delete($akey);
						$this->logging("+OK: [memcached] ClearAll cache : key={$akey}.");
					}
				}
				// リストも削除
				$this->objMemcache->delete($list_key);
				$this->logging("+OK: [memcached] ClearAll cache : list_key={$list_key}.");
			}

		// ファイルキャッシュ削除
			$dir = $this->getCacheFilePath();
			if ( $dirHandle = opendir ( $dir )) {
				while( false !== ( $fileName = readdir( $dirHandle ) ) ) {
					if($fileName!='.' && $fileName!='..'){
						unlink($dir.'/'.$fileName);
					}
				}
				closedir ( $dirHandle );
				$this->logging("+OK: [filecache] ClearAll cache : {$dir}.");
			}
	}

	function fetch_assoc($resource_str, $row=null){
		$result = null;
		if($this->cacheEnable){
			if(is_numeric($row)){
				$result = $this->cache[$resource_str][$row];
			}else{
				$result = $this->cache[$resource_str][$this->cachePointer[$resource_str]];
			}
			$this->cachePointer[$resource_str]++;

		}
		return $result;
	}

	function data_seek($resource_str, $row_num){
		$this->cachePointer[$resource_str] = $row_num;
	}

	function num_rows(&$resource_str) {
		return count($this->cache[$resource_str]);
	}

	function getCacheFilePath($key=''){
		$publisher_no = $this->getPublisherNumber();
		if(!is_numeric($publisher_no)){
			return null;
		}
		// test or honban
		$homedir = preg_replace('/^\/home\//', '', $_SERVER['DOCUMENT_ROOT']);
		$homedir = preg_replace('/(\/(.*))$/', '', $homedir);
		return __QUERYCACHE_BASE_DIR__.$homedir.'/'.$publisher_no.'/'.$key;
	}

	function addResource($data){
		if(!$this->cacheEnable){return null;}
		// 最新のリソース番号でキャッシュデータを保管
		$resource_no = $this->increment_resource_no;
		$resource_str = $this->getResourceKey($resource_no);

		$this->cache[$resource_str] = $data;
		$this->cachePointer[$resource_str] = 0;

		// リソース番号をインクリメントしておく
		$this->increment_resource_no++;

		return $resource_str;
	}

	function getResourceKey($num){
		if(!$this->cacheEnable){return null;}
		return "CacheResource id #{$num}";
	}

	function getPublisherNumber(){
		$publisher_number = null;
		if(is_numeric($this->intForcePublisherNo)){
			$publisher_number = $this->intForcePublisherNo;
		}elseif(isset($_SERVER['HONDANA_PUBLISHER_NUMBER']) && is_numeric($_SERVER['HONDANA_PUBLISHER_NUMBER'])){
			$publisher_number = $_SERVER['HONDANA_PUBLISHER_NUMBER'];
		}elseif(isset($_SESSION['publisher_no']) && is_numeric($_SESSION['publisher_no'])){
			$publisher_number = $_SESSION['publisher_no'];
		}
		return $publisher_number;
	}

	function logging($message){
		if(!defined('__QUERYCACHE_CACHE_PROSESS_FILE__')){return;}

		static $index = 0;
		//if($this->cacheEnable){
			$out  = "[".sprintf('%03d',$index)."][".date('Y-m-d H:i:s')."]: ";
			$out .= $message;
			$out .= "\n";

			// test or honban
			if($_SERVER['SHELL']){// CLI
				$homedir = 'hondana';
			}else{ // WEB
				$homedir = preg_replace('/^\/home\//', '', $_SERVER['DOCUMENT_ROOT']);
				$homedir = preg_replace('/(\/(.*))$/', '', $homedir);
			}

			$logfile = __QUERYCACHE_BASE_DIR__.$homedir.'/'.__QUERYCACHE_CACHE_PROSESS_FILE__;
			$newfile = false;
			if(file_exists($logfile)){ $newfile=true; }

			file_put_contents($logfile, $out, FILE_APPEND | LOCK_EX);
			if($newfile){chmod($logfile,0666);}
			$index++;
		//}
	}



	/**
	 * 指定日にキャッシュを削除するための空ファイルを作成する。
	 *
	 * @param int $publisher_no 該当の出版社番号を指定
	 * @param int $timestamp クエリキャッシュを削除する日付
	 * @return bool 成功したらtrueを、失敗したらfalseを返す。
	 */
	static function touchCacheDeleteFile($publisher_no, $timestamp){
		if(!is_numeric($publisher_no)){
			trigger_error("\$publisher_noが整数ではありません。");
			return false;
		}

		$target_dir = __QUERYCACHE_DELETE_RESERVAION_DIR__.'/'.$publisher_no;
		$target_file = $target_dir.'/'.date('Ymd_His', $timestamp).'.txt';

		if(!file_exists($target_dir)){
			if(!file_exists(dirname($target_dir))){
				if(!file_exists(dirname(dirname($target_dir)))){
					mkdir(dirname(dirname($target_dir)));
					chmod(dirname(dirname($target_dir)), 0777);
				}
				mkdir(dirname($target_dir));
				chmod(dirname($target_dir), 0777);
			}

			mkdir($target_dir);
			chmod($target_dir, 0777);
		}

		return 	touch($target_file);
	}

}


//DatabaseCache::touchCacheDeleteFile($publisher_no, $timestamp);
//DatabaseCache::touchCacheDeleteFile(3, 1474983227);

