<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	function prepare() {
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/img/uploads/banner/big',
			$siteroot
		);
		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);

		// 画像処理
		$key = 'banner';
		if ($this->_uploader->exists($key)) {
			$temp_path = $this->_uploader->getTemporaryPath($key);

			// バナーサイズ取得
			$config = parse_ini_file('../../big/config.ini', true);
			$width = 1200;
			$height = 1000;
			if(!empty($config['bigbanner']['width'])) {
				$width = $config['bigbanner']['width'];
			}
			if(!empty($config['bigbanner']['width'])) {
				$height = $config['bigbanner']['width'];
			}

			$size = sprintf('%sx\>', $width);
			$crop = sprintf('%sx%s+0+0', $width, $height);
			$command = sprintf('convert -quality 100 -thumbnail %s -gravity center -crop %s %s %s', $size, $crop, $temp_path, $temp_path);
			exec($command);
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
		if (!$this->banner) {
			$this->errors['banner'] = 'バナー画像の選択をご確認ください。';
		}

		if(count($this->errors)) {
			return 'input';
		}

		return true;
	}
}
?>