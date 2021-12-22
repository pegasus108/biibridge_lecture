<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	function prepare() {
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/img/uploads/banner',
			$siteroot
		);
		// thumb size
		$config = parse_ini_file('../../banner/config.ini', true);
		$this->width = 170;
		$this->height = 300;
		if(!empty($config['banner']['width'])) {
			$this->width = $config['banner']['width'];
		}
		if(!empty($config['banner']['width'])) {
			$this->height = $config['banner']['width'];
		}

		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);

		//画像処理
		$key = 'banner';
		if ($this->_uploader->exists($key)) {
			$temp_path = $this->_uploader->getTemporaryPath($key);
			$this->convertGeometry($temp_path,$this->width,$this->height);
		}
	}

	function validate() {
		$this->errors = array();

		if (!$this->name) {
			$this->errors['name'] = 'タイトルの記入をご確認ください。';
		}
		if (!$this->url) {
			$this->errors['url'] = 'リンク先の記入をご確認ください。';
		}
		if(count($this->errors)) {
			return 'input';
		}

		return true;
	}
}
?>