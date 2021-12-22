<?php
require_once(dirname(__FILE__) . '/smarty/libs/Smarty.class.php');

if (!class_exists('AjaxRenderer')) {

class AjaxRenderer extends Smarty {
	function AjaxRenderer() {
		$this->Smarty();
		$this->caching = false;
		$this->template_dir = $_SERVER['DOCUMENT_ROOT'];
		$this->compile_id = $_SERVER['HTTP_HOST'];
		$this->compile_dir = $_SERVER['DOCUMENT_ROOT'] . '/../libdb/smarty/templates_c';
		// $this->compile_dir = dirname(__FILE__) . '/smarty/templates_c';
		$this->plugins_dir[] = dirname(__FILE__) . '/smarty/plugins';
	}
}

}
?>
