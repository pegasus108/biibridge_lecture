<?php
/*
* Smarty plugin
* ----- 
* File:     resource.this.php
* Type:     resource
* Name:     this
* Purpose:  Smarytにassignした値をそのままテンプレートとして使用する
* -----
*/

function smarty_resource_this_source($tpl_name, &$tpl_source, &$smarty) {
   if ($tpl_name) {
       $tpl_source = $smarty->get_template_vars($tpl_name);
       return true;
   } else {
       return false;
   }

}

function smarty_resource_this_timestamp($tpl_name, &$tpl_timestamp, &$smarty) {
   if ($tpl_name) {
       $tpl_timestamp = time();
       return true;
   } else {
       return false;
   }

}

function smarty_resource_this_secure($tpl_name, &$smarty) {
   return true;

}

function smarty_resource_this_trusted($tpl_name, &$smarty) {
   return true;
}
?>