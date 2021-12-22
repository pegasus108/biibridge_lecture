<?php
require_once(dirname(__FILE__) . '/smarty/libs/Smarty.class.php');

if (!class_exists('DatabeseRenderer')) {

class DatabeseRenderer extends Smarty {
	function __construct() {
		Smarty::__construct();
		$this->caching = false;
		$this->template_dir = $_SERVER['DOCUMENT_ROOT'];
		$this->compile_id = $_SERVER['HTTP_HOST'];
		$this->compile_dir = $_SERVER['DOCUMENT_ROOT'] . '/../libdb/smarty/templates_c';
		$this->plugins_dir[] = dirname(__FILE__) . '/smarty/libs/plugins';
		$this->plugins_dir[] = dirname(__FILE__) . '/smarty/plugins';
	}
}

}
?>
