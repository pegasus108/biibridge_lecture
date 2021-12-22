<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage project_plugins
 */

/**
 * Smarty euc2sjis outputfilter plugin
 *
 * File:     outputfilter.euc2sjis.php<br>
 * Type:     outputfilter<br>
 * Name:     euc2sjis<br>
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('output','euc2sjis');</code>
 *           from application.
 * @author   Kawamoto Koo
 * @param string
 * @param Smarty
 */
function smarty_outputfilter_euc2sjis($source, &$smarty) {

	/**
	 * 文字コード変換と一緒に
	 * SSLページの相対パスで指定されているリンクのHTTP化
	 */
	// HONDANAでSSLを使用するホスト名一覧
	$sslHostList = array(
		"www.minervashobo.co.jp" => array(
			"/contact/index.php", "/cart/index.php", "/careers/index.php", "/review/index.php"
		),
		"minervashobo.hondana.fl.evol-ni.com" => array(
			"/contact/index.php", "/cart/index.php", "/careers/index.php", "/review/index.php"
		)
	);
	
	$hostname = $_SERVER['SERVER_NAME'];
	if(array_key_exists($hostname, $sslHostList)) {
		$host = 'http://' . $hostname;
		$script = $_SERVER['SCRIPT_NAME'];
		$prefix = NULL;
		
		foreach($sslHostList[$hostname] as $value) {
			if ($script == $value) {
				$prefix = $host . dirname($script) . '/';
				break;
			}
		}
		
		if ($prefix) {
			$source = mb_ereg_replace('<form action="../', '<form action="' . $prefix . '../', $source);
			$source = mb_ereg_replace('<form action="/', '<form action="' . $host . '/', $source);
			$source = mb_ereg_replace('<a href="../', '<a href="' . $prefix . '../', $source);
			$source = mb_ereg_replace('<a href="/', '<a href="' . $host . '/', $source);
		}
	}

	// 文字コード変換
	return mb_convert_encoding($source, 'SJIS', 'EUC-JP');
}

?>