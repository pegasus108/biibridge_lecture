<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage project_plugins
 */

/**
 * Smarty euc2utf8 outputfilter plugin
 *
 * File:     outputfilter.euc2utf8.php<br>
 * Type:     outputfilter<br>
 * Name:     sjis2euc<br>
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('output','euc2utf8');</code>
 *           from application.
 * @author   Kawamoto Koo
 * @param string
 * @param Smarty
 */
function smarty_outputfilter_euc2utf8($source, &$smarty) {
	return mb_convert_encoding($source, 'UTF-8', 'EUC-JP');
}

?>