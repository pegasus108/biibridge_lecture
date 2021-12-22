<?php
/**
 * キャッシュ削除を管理するクラス
 *
 * @since 2016/12/18
 * @author T.Ando
 */


// クエリキャッシュクラスの読み込み
require_once(dirname(__FILE__).'/DatabaseCache.php');


// キャッシュ予約削除ディレクトリ（削除時間を管理するディレクトリ）
define('__CACHEMANAGER_DELETE_RESERVAION_DIR__', '/tmp/cache/reserv_delete/');

// publisher_no と ページキャッシュ保存先パスのハッシュテーブル（ページキャッシュ用）
define('__CACHEMANAGER_PAGECACHE_PUBLISHERNO_CACHEPATH_HASHTABLE__', '/tmp/cache/publisher_no2cachepath.txt');

// ログファイル
//define('__CACHEMANAGER_LOG__', '/tmp/cache/action.log');

// キャッシュ予約削除ディレクトリの有無。なければ作成
if(!is_dir(__CACHEMANAGER_DELETE_RESERVAION_DIR__)){
	if(!is_dir(dirname(__CACHEMANAGER_DELETE_RESERVAION_DIR__))){
		if(!is_dir(dirname(dirname(__CACHEMANAGER_DELETE_RESERVAION_DIR__)))){
			mkdir(dirname(dirname(__CACHEMANAGER_DELETE_RESERVAION_DIR__)));
		}
		mkdir(dirname(__CACHEMANAGER_DELETE_RESERVAION_DIR__));
	}
	mkdir(__CACHEMANAGER_DELETE_RESERVAION_DIR__);
}

/**
 * 管理するためのクラス
 */
class CacheManager{

	/**
	 * キャッシュファイルの削除
	 *
	 * @param int $publisher_no	削除する対象の出版社Noを指定します。
	 * @param int $timestamp=null	予約投稿の場合は、キャッシュを削除する日時を選択してください。秒まで指定された場合は、次の0秒のタイミングで削除されます。
	 * @param int $pageCacheRootPath=null	ページキャッシュのためのパラメータです。キャッシュパスをpublisher_no と関連付ける為にハッシュテーブルで管理をしていますが、そのテーブルを更新するためのパラメータです。ページキャッシュの場合は、
	 * @return bool 成功したらtrueを、失敗したらfalseを返す。
	 */
	static public function DeleteCache($publisher_no, $timestamp=null, $pageCacheRootPath=null){
//file_put_contents('/tmp/test.txt', $_SERVER['REQUEST_URI']."\n".print_r(func_get_args(),true), FILE_APPEND);
		CacheManager::logging(__METHOD__."()");
		CacheManager::logging(__METHOD__."(\$publisher_no($publisher_no), \$timestamp($timestamp), \$pageCacheRootPath($pageCacheRootPath));");

		// 引数チェック
		if(!is_numeric($publisher_no)){
			trigger_error("１番目の引数（\$publisher_no）に指定された値が整数ではありません。");
			return false;
		}

		if(!empty($timestamp)){
			if(!is_numeric($timestamp)){
				trigger_error("２番目の引数（\$timestamp）に値が設定されていますが、指定された値が整数ではありません。");
				return false;
			}
			if(empty($pageCacheRootPath)){
				trigger_error("３番目の引数（\$pageCacheRootPath）に値が設定されていません。");
				return false;
			}
		}


		// 予約投稿で決まった時間にキャッシュを削除する
		if(is_numeric($timestamp) && time() < $timestamp){
			// キャッシュ予約削除
			CacheManager::touchCacheDeleteFile($publisher_no, $timestamp);

			if(!empty($pageCacheRootPath)){
				// publisher_noとページキャッシュパスのハッシュテーブル更新。
				CacheManager::addPageCacheRootPathTable($publisher_no, $pageCacheRootPath);
			}
		} else {
			// リアルタイム削除

			// 1.まずは、クエリキャッシュ削除
			$objDBCache = new DatabaseCache($publisher_no);
			$objDBCache->clearAll();
			CacheManager::logging('Clear query cache.');

			// 2.ページキャッシュ削除
			if(!empty($pageCacheRootPath)){
				if(preg_match('/^\/home\/(hondana|hondanatest)\/cache\//', $pageCacheRootPath)){
					$cmd = "/bin/rm -rf $pageCacheRootPath";
					//echo $cmd."\n";
					CacheManager::logging($cmd);
					exec($cmd);
				}
			}

			// 出版社 特別対応
			if($publisher_no == 50) {
				// 東洋館出版社の場合は 一般書サイトのキャッシュもクリア
				$clearpath = '/home/hondana/cache/www.toyokanbooks.com/';
				if(strpos($_SERVER['HTTP_HOST'], '.stg.hondana.jp') !== false) {
					$clearpath = '/home/hondanatest/cache/toyokanbooks.stg.hondana.jp/';
				}
				if(preg_match('/^\/home\/(hondana|hondanatest)\/cache\//', $clearpath)){
					$cmd = "/bin/rm -rf $clearpath";
					exec($cmd);
				}
			}

			// 3.リバースプロキシ(nginx) キャッシュ削除

		}

		return true;
	}



	/**
	 * 指定日にキャッシュを削除するための空ファイルを作成する。
	 *
	 * @param int $publisher_no 該当の出版社番号を指定
	 * @param int $timestamp クエリキャッシュを削除する日付
	 * @return bool 成功したらtrueを、失敗したらfalseを返す。
	 */
	static public function touchCacheDeleteFile($publisher_no, $timestamp){
		if(!is_numeric($publisher_no)){
			trigger_error("\$publisher_noが整数ではありません。");
			return false;
		}

		$target_dir = __CACHEMANAGER_DELETE_RESERVAION_DIR__.'/'.$publisher_no;
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

		CacheManager::logging(__METHOD__."(): touch($target_file);");
		return  touch($target_file);
	}



	/**
	 * ページキャッシュの保存ディレクトリは、fqdn（ホスト名）で構成されているため publisher_no とキャッシュ保存パスを対にして保存しておく
	 */
	static public function addPageCacheRootPathTable($publisher_no, $pageCacheRootPath){
		$updateFlag = false;

		$aryHash = CacheManager::loadPageCacheRootPathTable($publisher_no);

		if(isset($aryHash[$publisher_no])){
			if(!in_array($pageCacheRootPath, $aryHash[$publisher_no])){
				$aryHash[$publisher_no][] = $pageCacheRootPath;
				$updateFlag = true;
			}
			//for($i=0; $i<count($aryHash[$publisher_no]); $i++ ){
			//	$aryHash[$publisher_no][$i]
			//}
		}else{
			// 初めて出現の publisher_no の場合
			$aryHash[$publisher_no] = array();
			$aryHash[$publisher_no][] = $pageCacheRootPath;
			$updateFlag = true;
		}

		if($updateFlag){
			file_put_contents(__CACHEMANAGER_PAGECACHE_PUBLISHERNO_CACHEPATH_HASHTABLE__, serialize($aryHash));
		}
	}

	static public function loadPageCacheRootPathTable(){
		$result = array();
		if(file_exists(__CACHEMANAGER_PAGECACHE_PUBLISHERNO_CACHEPATH_HASHTABLE__)){
			$result = unserialize(file_get_contents(__CACHEMANAGER_PAGECACHE_PUBLISHERNO_CACHEPATH_HASHTABLE__));
		}
		return $result;
	}


	static function logging($message){
		if(!defined('__CACHEMANAGER_LOG__')){return;}

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

			$logfile = __CACHEMANAGER_LOG__;
			$newfile = false;
			if(file_exists($logfile)){ $newfile=true; }

			file_put_contents($logfile, $out, FILE_APPEND | LOCK_EX);
			if($newfile){chmod($logfile,0666);}
			$index++;
		//}
	}

}

