<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage project_plugins
 */

/**
 * Smarty mbtruncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     mbtruncate<br>
 * @param string
 * @param integer
 * @param string
 * @return string
 */
function smarty_modifier_mbtruncate($string, $length = 80, $etc = '...') {
    if ($length == 0) {return '';}
    if (strlen($string) > $length) {
        $length -= strlen($etc);
        return mb_strcut($string, 0, $length).$etc;
    } else {
        return $string;
    }
}

/* vim: set expandtab: */

?>