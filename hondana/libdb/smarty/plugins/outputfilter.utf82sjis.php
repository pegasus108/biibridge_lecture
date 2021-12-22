<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage project_plugins
 */

/**
 * Smarty utf82sjis outputfilter plugin
 *
 * File:     outputfilter.utf82sjis.php<br>
 * Type:     outputfilter<br>
 * Name:     utf82sjis<br>
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('output','utf82sjis');</code>
 *           from application.
 * @author   Kawamoto Koo
 * @param string
 * @param Smarty
 */
function smarty_outputfilter_utf82sjis($source, &$smarty) {
	$source = preg_replace('/utf-8/ui', 'Shift_JIS', $source);
	return mb_convert_encoding($source, 'SJIS-Win', 'UTF-8');
}

?>