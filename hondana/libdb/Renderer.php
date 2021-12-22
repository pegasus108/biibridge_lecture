<?php
require_once(dirname(__FILE__) . '/smarty/libs/SmartyBC.class.php');

if (!class_exists('Renderer')) {

class Renderer extends SmartyBC {
	function __construct() {
		SmartyBC::__construct();
		$this->caching = false;
		$this->template_dir = $_SERVER['DOCUMENT_ROOT'];
		$this->compile_id = $_SERVER['HTTP_HOST'];
		$this->compile_dir = $_SERVER['DOCUMENT_ROOT'] . '/../libdb/smarty/templates_c';
		// $this->compile_dir = dirname(__FILE__) . '/smarty/templates_c';
		$this->setPluginsDir(array(
			dirname(__FILE__) . '/smarty/libs/plugins',
			dirname(__FILE__) . '/smarty/plugins'
		));

		$this->load_filter('pre','ssi2smarty');

		/*
		 * モバイル対応（docomoとauだけSJIS-Winに変換する）
		 */
		// $ua = $_SERVER['HTTP_USER_AGENT'];
		// if(ereg("^DoCoMo", $ua) || ereg("^UP.Browser|^KDDI", $ua)){
		// 	$this->load_filter('output','utf82sjis');
		// }
	}
}

}
?>
