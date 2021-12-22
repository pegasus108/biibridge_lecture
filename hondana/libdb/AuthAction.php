<?php
require_once('DatabaseAction.php');

class AuthAction extends DatabaseAction {

	function decision($condition = null) {
		$this->__decisionResult = parent::decision($condition);
		return $this->__decisionResult;
	}

	// ****************************************************
	// ****** キャッシュ機能無効化 2019/5/22 baba ******
	// ****************************************************
	// function dispose() {
	// 	$action = $this->__controller->__action;
	// 	if($this->__decisionResult === true && $action === 'process') {
	// 		$this->removeCache();
	// 	}
	// }

	function __construct(&$controller) {
		DatabaseAction::__construct($controller);

		// 認証チェック
		if(!$this->auth()) {
			$_SESSION['auth_before'] = dirname($_SERVER['SCRIPT_NAME']);
			$_SESSION['error_session_timeout'] = '長時間操作を行わなかった為、自動的にログアウトしました。';
			if(strpos(dirname($_SERVER['SCRIPT_NAME']), 'popup')) {
				$this->__controller->redirectToURL('/admin/close.html');
			} else {
				$this->__controller->redirectToURL('/admin/');
			}
		}
		$this->showSimpleMessage();
	}

	function auth() {
		if($_SESSION['loginid'] && $_SESSION['pass']) {
			$db =& $this->_db;

			$db->assign('id', $_SESSION['loginid']);
			$db->assign('pass', $_SESSION['pass']);
			$result = $db->statement('admin/sql/auth.sql');
			$this->publisher_account=array();
			if($row = $db->fetch_assoc($result)) {

				$this->publisher_account=$row;
				$_SESSION["publisher_account_name"] = $row["name"];

				$db->assign('publisher_no', $row["publisher_no"]);
				$result = $db->statement('admin/publisher/sql/publisher.sql');
				$row = $db->fetch_assoc($result);

				$this->publisher = $row;
				if ($this->publisher['freeitem']) {
					$this->publisher['free'] = $this->unserialize($this->publisher['freeitem']);
				}
				return true;
			}else{

			}
		}
		return false;
	}

	function isKatakana($value) {
		return preg_match('/^(\(|\)|（|）|．|\.|，|,|・|･|-|=|＝|[a-zａ-ｚＡ-ＺA-Z]|[0-9０-９]|[ァ-ヾ]|ー|　| )+$/u', $value);
	}

	function isDate($year , $month = null, $day = null) {
		if($month !== null && $day !== null) {
			// 引数が3つ
			return (
				preg_match('/^[0-9]{4}[\-\/\.]?[0-1]?[0-9][\-\/\.]?[0-3]?[0-9]$/u', $year.$month.$day)
				&& checkdate($month , $day , $year)
			);
		} elseif($month === null && $day === null) {
			// 引数が1つ
			if(preg_match('/^([0-9]{4})[\-\/\.]?([0-1]?[0-9])[\-\/\.]?([0-3]?[0-9])$/u', $year,$matches)) {
				return checkdate($matches[2],$matches[3],$matches[1]);
			}
			return false;
		}
		// それ以外は とにかくfalse
		return false;
	}

	function isFormatedDate($dateString) {
		return checkdate(substr($dateString,4,2) , substr($dateString,6,2) , substr($dateString,0,4));
	}

	function isFormatedMonthDate($dateString) {
		return checkdate(substr($dateString,4,2) , 1 , substr($dateString,0,4));
	}

	function isHttpUrl($value) {
		return ereg('^s?https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+\/$', $value);
	}
	function showSimpleMessage(){
		if(isset($_SESSION['simpleMessageSender'])) {
			unset($_SESSION['simpleMessageSender']);
		}
		if(isset($_SESSION['simpleMessage'])){
			$_SESSION['simpleMessageSender'] = $_SESSION['simpleMessage'];
			unset($_SESSION['simpleMessage']);
		}
	}

	function setSimpleMessage($str){
		$_SESSION['simpleMessage'] = $str;
	}

	/**
	 *
	 * @param String $text
	 * @param String $to_encoding
	 * @param String $from_encoding
	 * @return boolean
	 */
	function isGarbled($text, $to_encoding = 'SJIS-win', $from_encoding = 'UTF-8') {
		if(empty($text)) {
			return false;
		}
		$convertText = mb_convert_encoding($text, $to_encoding, $from_encoding);
		$convertText = mb_convert_encoding($convertText, $from_encoding, $to_encoding);
		if($text !== $convertText) {
			return true;
		}
		return false;
	}

	/**
	 *
	 * @param String $message
	 * @param Integer $level
	 * @return boolean
	 */
	function log($message, $priority = LOG_INFO) {
		$message = sprintf('HONDANA: %d:%s %s', $_SESSION['publisher_no'], $_SESSION['id'], $message);
		syslog($priority, $message);
		return true;
	}

	// function removeCache($name = null) {
	// 	$host = parse_url($_SESSION['publisher_url'], PHP_URL_HOST);
	// 	$cache = new Cache($host);
	// 	$cache->remove($name);
	// }
	function removeCacheSchedule($date) {
	// 	$host = parse_url($_SESSION['publisher_url'], PHP_URL_HOST);
	// 	$cache = new Cache($host);
	// 	$cache->removeSchedule($date);
	}
	function convert(&$value,$option) {
		if(!empty($value)) {
			$value = mb_convert_kana($value,$option);
		}
	}

	/**
	 * [setFullpath ルートパス → フルパスへ変換 (SSLページ対応用)]
	 * @param [type] $targetfield [変換対象のフィールド]
	 * @param [type] $host        [出版社サイト ホスト]
	 */
	function setFullpath($targetfield,$host) {
		foreach ($targetfield as $k => $v) {
			// 階層が違っている出版社対応
			$customdir = '';
			// files 内のみ置換 (mod proxy 使用の出版社で問題があったため)
			$this->$v = preg_replace('/(src|href)=(["\'])\/' . $customdir . 'files\/(.+?)\2/i', '$1="' . $host . 'files/$3"', $this->$v);
		}
	}

	/**
	 * [unsetFullpath フルパス → ルートパスへ変換 (SSLページ対応用)]
	 * @param [type] $targetfield [変換対象のフィールド]
	 * @param [type] $host        [出版社サイト ホスト]
	 */
	function unsetFullpath($targetfield,$host) {
		$host = preg_replace('|/|', '\/', $host);
		foreach ($targetfield as $k => $v) {
			// files 内のみ置換 (mod proxy 使用の出版社で問題があったため)
			$this->$v = preg_replace('/(src|href)=(["\'])' . $host . 'files\/(.+?)\2/i', '$1="/' . $customdir . 'files/$3"', $this->$v);
		}
	}

	/**
	* Convert image geometry by ImageMagick.
	* @access public
	*/
	function convertGeometry($filepath, $width, $height) {
		// 画像のサイズ確認
		$imgsize = getimagesize($filepath);
		if(!empty($imgsize) && ($imgsize[0] > $width || $imgsize[1] > $height)) {
			// 指定サイズより画像が大きい際は縮小させる
			$command = 'convert -geometry ' . $width . 'x' . $height . '\>'
				. ' ' . $filepath
				. ' ' . $filepath;
			system($command);
		}
	}
}
?>