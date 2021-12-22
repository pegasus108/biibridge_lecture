<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage project_plugins
 */

/**
 * Smarty ssi2smarty prefilter plugin
 *
 * File:     prefilter.ssi2smarty.php<br>
 * Type:     prefilter<br>
 * Name:     ssi2smarty<br>
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('pre','ssi2smarty');</code>
 *           from application.
 * @author   Kawamoto Koo
 * @param string
 * @param Smarty
 */
function smarty_prefilter_ssi2smarty($source, $smarty) {
	$source = mb_ereg_replace('<!--[ \\t]*#include[ \\t]+virtual[ \\t]*=[ \\t]*"/(include/analysis\\.html)"[ \\t]*-->', '{include_text file=\'\\1\'}', $source);
	$source = mb_ereg_replace('<!--[ \\t]*#include[ \\t]+virtual[ \\t]*=[ \\t]*"/([^"]+)"[ \\t]*-->', '{include file=\'\\1\'}', $source);
	return $source;
}

?>