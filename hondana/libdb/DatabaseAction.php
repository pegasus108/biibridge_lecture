<?php

//通常
// error_reporting(0);

//デバッグ時
//error_reporting(E_ALL ^ E_NOTICE);

require_once(dirname(__FILE__) . '/simple/DefaultAction.php');
require_once(dirname(__FILE__) . '/simple/Database.php');

class DatabaseAction extends DefaultAction
{
	var $_url = null;
	var $_db = null;

	function __construct(&$controller)
	{
		DefaultAction::__construct($controller);

		$this->_db = new Database($this->_url);
		$connection = $this->_db->connect();
		if (!$connection) {
			// データベースに接続できなかった際は キャッシュさせない
			$this->__controller->__caching = false;
		}

		$db = &$this->_db;
	}

	function init()
	{
		DefaultAction::init();
		$this->_url = 'mysql://' . $_ENV['DB_USERNAME'] . ':' . $_ENV['DB_PASSWORD'] . '@' . $_ENV['DB_HOST'] . '/' . $_ENV['DB_DATABASE'];
	}

	/**
	 * オリジナルのunserialize関数
	 *
	 * EUC-JPからUTF-8に文字コードを変えたことが原因で
	 * EUC-JPでシリアライズされたものと、UTF-8でシリアライズされたものの2つが存在してしまっている為
	 * その差異を無くすための関数です。
	 */
	function unserialize($data)
	{
		$result = unserialize($data);
		if (!is_array($result)) {
			$result = unserialize(mb_convert_encoding($data, 'eucJP-win', 'UTF-8'));
			mb_convert_variables('UTF-8', 'eucJP-win', $result);
		}
		return $result;
	}
}
