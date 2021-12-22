<?php
require_once('BaseController.php');
// require_once(dirname(__FILE__) . '/../util/cache/Cache.php');

class DefaultController extends BaseController {
	var $__caching = null;

	function __construct() {
		parent::__construct();
	}

	function run() {
		// $name = !empty($_SERVER['SCRIPT_URL']) ? $_SERVER['SCRIPT_URL'] : $_SERVER['REQUEST_URI'];
		$name = $_SERVER['REQUEST_URI'];
		$host = $_SERVER['HTTP_HOST'];

		/**
		 * キャッシュ処理前に スマホ切り替え判断
		 */
		$viewsite = "pc";
		$ua = "pc";
		if(!empty($_SERVER['SMARTPHONEPATH'])) {
			// 表示させるページを判定
			if(
				preg_match("/Android|iPhone|iPod|IEMobile|BlackBerry/", $_SERVER['HTTP_USER_AGENT']) &&
				!preg_match("/Sony Tablet S/", $_SERVER['HTTP_USER_AGENT'])
			) {
				$ua = "smp";

				// スマホでアクセス
				if(empty($_COOKIE['viewsite'])) {
					$viewsite = "smp";
				}

				// 表示切り替え確認
				if(!empty($_REQUEST['changeview'])) {
					if(preg_match('/hobbyjapan/', $host) === 1 && preg_match('/^\/books\//', $name) === 1){
						// ホビージャパン用 特別分岐
						if($_REQUEST['changeview'] == 'pc') {
							setcookie("viewsite", 1, time()+60*60*24*7, "/books/", "hobbyjapan.co.jp", false,true);
							//特別分岐ステージング切り分け対応
							if(strpos($_SERVER['HTTP_HOST'], '.stg') !== false){
								setcookie("viewsite", 1, time()+60*60*24*7, "/books/", "hobbyjapan.stg.hondana.jp", false,true);
							}
							$viewsite = "pc";
						} elseif($_REQUEST['changeview'] == 'smp' && !empty($_COOKIE['viewsite'])) {
							setcookie("viewsite", false, time()-42000, "/books/", "hobbyjapan.co.jp", false,true);
							//特別分岐ステージング切り分け対応
							if(strpos($_SERVER['HTTP_HOST'], '.stg') !== false){
								setcookie("viewsite", false, time()-42000, "/books/", "hobbyjapan.stg.hondana.jp", false,true);
							}
							$viewsite = "smp";
						}
					}else if(preg_match('/tokaiedu/', $host) === 1 && preg_match('/^\/t-booksstore\//', $name) === 1){
						// 東海教育研究所用 特別分岐
						if($_REQUEST['changeview'] == 'pc') {
							setcookie("viewsite", 1, time()+60*60*24*7, "/t-booksstore/", "tokaiedu.co.jp", false,true);
							$viewsite = "pc";
						} elseif($_REQUEST['changeview'] == 'smp' && !empty($_COOKIE['viewsite'])) {
							setcookie("viewsite", false, time()-42000, "/t-booksstore/", "tokaiedu.co.jp", false,true);
							$viewsite = "smp";
						}
					}else{
						if($_REQUEST['changeview'] == 'pc') {
							setcookie("viewsite", 1, time()+60*60*24*7, "/", $_SERVER['SERVER_NAME'], false,true);
							$viewsite = "pc";
						} elseif($_REQUEST['changeview'] == 'smp' && !empty($_COOKIE['viewsite'])) {
							setcookie("viewsite", false, time()-42000, "/", $_SERVER['SERVER_NAME'], false,true);
							$viewsite = "smp";
						}
					}
				}
			}
			$this->viewsite = $viewsite;
		}
		$protocol = 'http://';
		if(!empty($_SERVER['HTTPS'])) {
			$protocol = 'https://';
		}
		// リダイレクトの判断
		if(
			!preg_match('/\/sitemap.xml$/u', $name) &&
			!preg_match('/\/rss\/news\/$/u', $name) &&
			!preg_match('/\/rss\/newbook\/$/u', $name) &&
			!preg_match('/\/rss\/insert\/$/u', $name) &&
			!preg_match('/\/rss\/update\/$/u', $name)
		) {
			if(preg_match('/\/smp\//', $name)) {
				// スマホページ閲覧中
				if($viewsite == "pc") {
					if(preg_match('/hobbyjapan/', $host) === 1 && preg_match('/^\/books\//', $name) === 1){
						// ホビージャパン用 特別分岐
						$this->redirectToURL("http://hobbyjapan.co.jp".preg_replace("/\/smp\/(.*)$/","/$1",$name));
						//特別分岐ステージング切り分け対応
						if(strpos($_SERVER['HTTP_HOST'], '.stg') !== false){
							$this->redirectToURL("http://hobbyjapan.stg.hondana.jp".preg_replace("/\/smp\/(.*)$/","/$1",$name));
						}
					}else if(preg_match('/tokaiedu/', $host) === 1 && preg_match('/^\/t-booksstore\//', $name) === 1){
						// 東海教育研究所用 特別分岐
						$this->redirectToURL("http://tokaiedu.co.jp".preg_replace("/\/smp\/(.*)$/","/$1",$name));
					}else{
						$this->redirectToURL($protocol.$_SERVER['SERVER_NAME'].preg_replace("/\/smp\/(.*)$/","/$1",$name));
					}
					exit();
				}
			} else {
				// PCページ閲覧中
				if($viewsite == "smp") {
					if(preg_match('/hobbyjapan/', $host) === 1 && preg_match('/^\/books\//', $name) === 1){
						// ホビージャパン用 特別分岐
						$this->redirectToURL("http://hobbyjapan.co.jp/books/smp".preg_replace("/\/books\/(.*)$/","/$1",$name));
						//特別分岐ステージング切り分け対応
						if(strpos($_SERVER['HTTP_HOST'], '.stg') !== false){
							$this->redirectToURL("http://hobbyjapan.stg.hondana.jp/books/smp".preg_replace("/\/books\/(.*)$/","/$1",$name));
						}
					}else if(preg_match('/tokaiedu/', $host) === 1 && preg_match('/^\/t-booksstore\//', $name) === 1){
						// 東海教育研究所用 特別分岐
						$this->redirectToURL("http://tokaiedu.co.jp/t-booksstore/smp".preg_replace("/\/t-booksstore\/(.*)$/","/$1",$name));
					}else{
						$this->redirectToURL($protocol.$_SERVER['SERVER_NAME']."/smp".$name);
					}
					exit();
				}
			}
		}

		// $cache = new Cache($_SERVER['HTTP_HOST']);

		/**
		 * Content-typeを変える
		 */
		if(preg_match('/\/sitemap.xml$/u', $name)) {
			header('Content-type: text/xml');
		} elseif(preg_match('/\/rss\/news\/$/u', $name)) {
			header("Content-Type: application/xml;");
		} elseif(preg_match('/\/rss\/newbook\/$/u', $name)) {
			header("Content-Type: application/xml;");
		} elseif(preg_match('/\/rss\/insert\/$/u', $name)) {
			header("Content-Type: application/xml;");
		} elseif(preg_match('/\/rss\/update\/$/u', $name)) {
			header("Content-Type: application/xml;");
		}

		if($ua == 'smp') {
			$url = $_SERVER['SCRIPT_NAME'];
			if(!empty($_SERVER['QUERY_STRING'])) {
				$url .= "?" . $_SERVER['QUERY_STRING'];
			}
			$url = preg_replace("/[\?|&]changeview=.*$/","",$url);
			if(preg_match('/\?/', $url)) {
				$url .= "&changeview=";
			} else {
				$url .= "?changeview=";
			}

			if($viewsite == 'pc') {
				if(preg_match('/hobbyjapan/', $host) === 1 && preg_match('/^\/books\//', $name) === 1){
					// ホビージャパン用 特別分岐
					$url = "/books/smp".preg_replace("/\/books\/(.*)$/","/$1",$url)."smp";
				}else if(preg_match('/tokaiedu/', $host) === 1 && preg_match('/^\/t-booksstore\//', $name) === 1){
					// 東海教育研究所用 特別分岐
					$url = "/t-booksstore/smp".preg_replace("/\/t-booksstore\/(.*)$/","/$1",$url)."smp";
				}else{
					$url = "/smp".$url."smp";
				}
			} else {
				$this->changePcUrl = preg_replace("/\/smp\/(.*)$/","/$1",$url)."pc";
			}
		}

		// ****************************************************
		// ****** キャッシュ機能無効化 2019/5/22 evolni baba ******
		// ****************************************************
		// if($name !== '' && $cache->isCached($name)) {
		// 	$start = microtime(true);
		// 	echo $cache->getCache($name);
		// 	$end = microtime(true);

		// 	if(($_SERVER['HTTP_HOST'] !== 'www.stg.hondana.jp' && $_SERVER['HTTP_HOST'] !== 'stg.hondana.jp' && $_SERVER['HTTP_HOST'] !== 'www.hondana.jp')
		// 		&& ($_SERVER['REMOTE_ADDR'] === '182.171.234.187' || $_SERVER['REMOTE_ADDR'] === '219.111.52.193')
		// 		&& !(preg_match('/\/rss\/news\/$/u', $name) || preg_match('/\/rss\/newbook\/$/u', $name))
		// 	) {
		// 		printf('キャッシュ使用:%.10f秒', $end - $start);
		// 	}
		// } else {
		// 	$start = microtime(true);
		// 	parent::run();
		// 	$end = microtime(true);
		// 	if(($_SERVER['HTTP_HOST'] !== 'www.stg.hondana.jp' && $_SERVER['HTTP_HOST'] !== 'stg.hondana.jp' && $_SERVER['HTTP_HOST'] !== 'www.hondana.jp')
		// 		&& ($_SERVER['REMOTE_ADDR'] === '182.171.234.187' || $_SERVER['REMOTE_ADDR'] === '219.111.52.193')
		// 		&& !(preg_match('/\/rss\/news\/$/u', $name) || preg_match('/\/rss\/newbook\/$/u', $name))
		// 	) {
		// 		printf('キャッシュ未使用:%.10f秒', $end - $start);
		// 	}
		// }
		parent::run();
		if($viewsite == 'pc' && $ua == 'smp') {
			$style = array(
				"position: fixed",
				"right: 0",
				"top: 30px",
				"display:block",
				"width: auto",
				"padding:0",
//				"opacity: 0.7",
//				"background-color: black",
//				"color:#FFF",
//				"font-size:300%",
//				"text-align: center",
//				"-ms-writing-mode: tb-rl",
//				"-webkit-writing-mode: vertical-rl",
			);
			$disp = '<div id="smpViewLink" style="'.implode(";",$style).'">';
			if(!preg_match('/^\/books\//', $name)) {
				$disp .= '<div clsss="btnClose"><a href="javascript:closeBtn();"><img src="/smp/img/btn_close.png" alt="" /></a></div>';
				$disp .= '<div><a href="'.$url.'">';
				$disp .= '<img src="/smp/img/btn_smp.png" alt="スマートフォンページを閲覧する" />';
				$disp .= '</a><div>';
				$disp .= '</div>';
				$disp .= '<script type="text/javascript" src="/smp/js/pc_view_control.js"></script>';
			} else {
				$disp .= '<div clsss="btnClose"><a href="javascript:closeBtn();"><img src="/books/smp/img/btn_close.png" alt="" /></a></div>';
				$disp .= '<div><a href="'.$url.'">';
				$disp .= '<img src="/books/smp/img/btn_smp.png" alt="スマートフォンページを閲覧する" />';
				$disp .= '</a><div>';
				$disp .= '</div>';
				$disp .= '<script type="text/javascript" src="/books/smp/js/pc_view_control.js"></script>';
			}


			echo($disp);
		}
	}

	function renderView(&$action, $viewName) {
		if ($viewName) {
			$action->__view = $viewName;
		}
		else {
			$action->__view = $this->__action;
		}

		require_once($this->__viewFile);
		$view = new $this->__viewClass($action);
		$html = $view->fetch();

		/*
		 * キャッシュ生成処理の実行
		 */
		// $cache = new Cache($_SERVER['HTTP_HOST']);
		// $name = $_SERVER['REQUEST_URI'];
		// if(($this->__caching === true && $name !== '') || ($this->__caching === null && $name !== '' && $cache->isCache($name))) {
		// 	$cache->generate($name, $html);
		// }
		echo $html;
	}
}
?>
