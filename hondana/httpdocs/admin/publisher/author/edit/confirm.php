<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	function init() {
		parent::init();

		if(!isset($_REQUEST['clear_image'])) {
			$this->clear_image = null;
		}
	}

	function prepare() {
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/img/uploads/author',
			$siteroot
		);
		//thumb size
		$this->width = 200;
		$this->height = 300;

		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);

		$key = 'author';
		if ($this->author['http_path']) {
			$temp_path = $this->_uploader->getTemporaryPath($key);
			$this->convertGeometry($temp_path,$this->width,$this->height);
		}
	}

	function validate() {

		$this->errors = array();

		if (!$this->name) {
			$this->errors['name'] = '著者名の記入をご確認ください。';
		}
		if (!$this->kana) {
			$this->errors['kana'] = '著者名カナの選択をご確認ください。';
		}
		if (!$this->author) {
//			$this->errors['image'] = '画像をご確認ください。';
		}
		if (!$this->value) {
//			$this->errors['value'] = 'プロフィールの記入をご確認ください。';
		}


		if(count($this->errors)) {
			return 'input';
		}


		return true;
	}
}
?>