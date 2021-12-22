<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage project_plugins
 */

/**
 * Smarty ssi2php prefilter plugin
 *
 * File:     prefilter.ssi2php.php<br>
 * Type:     prefilter<br>
 * Name:     ssi2php<br>
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('pre','ssi2php');</code>
 *           from application.
 * @author   Kawamoto Koo
 * @param string
 * @param Smarty
 */
function smarty_prefilter_ssi2php($source, &$smarty) {
	$source = str_replace('<!--#include virtual="/update.php"-->', '{php}virtual("/update.php");{/php}', $source);
	$source = str_replace('<!--#include virtual="/ranking.php"-->', '{php}virtual("/ranking.php");{/php}', $source);
	return $source;
}

?>