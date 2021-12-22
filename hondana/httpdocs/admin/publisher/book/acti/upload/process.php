<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	function prepare() {
		parent::prepare();

		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/publisher/'.$_SESSION['id'].'/files/actibook',
			$siteroot . '/publisher/'.$_SESSION['id']
		);

		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);
	}

	function execute() {
		$up =& $this->_uploader;

		$key = 'zip';
		if($up->exists($key)) {
			$temp_path = $up->getTemporaryPath($key);
			$perma_path = $up->getPermanentPath($key);
			$perma_path = sprintf('%s/%s/%s', dirname($perma_path), $this->book_no, basename($perma_path));

			$dirname = sprintf('%s/files/actibook/%s', $up->root, $this->book_no);
			$command = sprintf('rm -fr %s', $dirname);
			exec($command);
			$this->mkdir($dirname, 0777, true);

			rename($temp_path, $perma_path);

			$commond = sprintf("unzip %s -d %s", $perma_path, dirname($perma_path));
			$output = "";
			exec($commond, $output);

			$commond = sprintf("chmod -R 777 %s", dirname($perma_path));
			$output = "";
			exec($commond, $output);

			unlink($perma_path);

			if($handle = opendir(dirname($perma_path))) {
				while(false !== ($file = readdir($handle))) {
					if($file == "actibook") {
						$command = sprintf('mv %s %s', sprintf('%s/%s/*', dirname($perma_path), $file), dirname($perma_path));
						exec($command);

						break;
					}
				}
				closedir($handle);
				rmdir(sprintf('%s/%s', dirname($perma_path), $file));
			}

		}

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}

	function validate() {
		$up =& $this->_uploader;

		$this->errors = array();

		if($this->book_no) {
		} else {
			$this->errors['zip'] = "書誌選択からやり直してください。";
		}

		$key = 'zip';
		if(($_FILES[$key]['name'])) {
		} else {
			$this->errors['zip'] = "アップロードするZIPファイルを選択してください。";
		}

		if(count($this->errors)) {
			return 'input';
		}

		return true;
	}

	function mkdir($dirname = null, $permission = 0777, $recursive = false) {
		if(!isset($dirname)) {
			return false;
		}

		if($recursive) {
			$dirnameList = array();
			$dirnameList = explode('/', $dirname);

			array_shift($dirnameList);
			$dir = '';
			foreach($dirnameList as $value) {
				$dir .= '/' . $value;
				if(file_exists($dir)) {
				} else {
					mkdir($dir);
					chmod($dir, $permission);
				}
			}
		} else {
			if(file_exists($dirname)) {
			} else {
				mkdir($dirname);
				chmod($dirname, $permission);
			}
		}

		return true;
	}
}
?>