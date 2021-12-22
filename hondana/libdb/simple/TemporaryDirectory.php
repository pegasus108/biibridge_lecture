<?php
require_once('Debug.php');

class TemporaryDirectory extends Debug {
	var $temporary = null;
	var $personal = null;
	var $prefix = null;
	var $expire = null;
	var $basename = null;

	function __construct($temporary) {
		$this->temporary = $temporary;

		Debug::__construct();

		$this->makeDir();
		$this->removeOldDirs();
	}

	function init() {
		Debug::init();

		if (!$this->temporary) {
			$this->temporary = $_SERVER['DOCUMENT_ROOT'] . '/tmp';
		}
		$this->expire = 3600;
		$this->basename = 'upload';

		if (!session_id()) {
			session_start();
		}
		$this->personal = session_id();
	}

	/**
	* Add file by key.
	* @access public
	*/
	function add($key, $tmpName, $name = null) {
		if (!$name) {
			$name = $tmpName;
		}

		$this->makeDir();
		$this->removeDirByDir($this->getDir($key));
		$this->makeDirByDir($this->getDir($key));

		$filename = $this->createTemporaryFilename($key, $name);

		copy($tmpName, $filename);
	}

	/**
	* Remove file by key.
	* @access public
	*/
	function remove($key) {
		$dir = $this->getDir($key);
		if (!file_exists($dir)) {
			return;
		}

		$this->removeDirByDir($dir);
	}

	/**
	* Copy file by key.
	* @access public
	* @return string
	*/
	function copy($key, $distination) {
		if (!$distination) {
			return;
		}

		$dir = dirname($distination);
		$this->makeParentDir($dir);

		copy($this->getPath($key), $distination);
	}

	/**
	* Exists file by key.
	* @access public
	* @return bool
	*/
	function exists($key) {
		$dir = $this->getDir($key);
		return file_exists($dir);
	}

	/**
	* Get path by key.
	* @access public
	* @return string
	*/
	function getPath($key) {
		$dir = $this->getDir($key);
		$list = $this->getPathsByDir($dir);

		if (count($list) <= 0) {
			return null;
		}

		return array_shift($list);
	}

	/**
	* Get relative path.
	* @access public
	* @return string
	*/
	function getRelativePath($key, $baseDir) {
		$path = $this->getPath($key);
		return $this->convertRelativePath($path, $baseDir);
	}

	/**
	* Convert relative path.
	* @access public
	* @return string
	*/
	function convertRelativePath($filename, $baseDir) {
		return substr($filename, strlen($baseDir));
	}

	/**
	* Get path list.
	* @access public
	* @return array
	*/
	function getPaths() {
		$list = $this->getPathsByDir($this->getTemporaryDir());

		foreach ($list as $key => $path) {
			$fullpath = $this->getPath($key);
			$list[$key] = $fullpath;
		}

		return $list;
	}

	/**
	* Get relative path list.
	* @access public
	* @return array
	*/
	function getRelativePaths($baseDir) {
		$list = $this->getPaths();
		$list = $this->convertRelativePaths($list, $baseDir);

		return $list;
	}

	/**
	* Convert relative path list.
	* @access public
	* @return string
	*/
	function convertRelativePaths($list, $baseDir) {
		foreach ($list as $key => $path) {
			$list[$key] = $this->convertRelativePath($path, $baseDir);
		}

		return $list;
	}

	/**
	* Get path list by directory.
	* @access public
	* @return array
	*/
	function getPathsByDir($dir, $recurse = false) {
		if (file_exists($dir)) {
			touch($dir);
		}

		$list = array();
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$filename = $dir . '/' . $file;
					if ($recurse && is_dir($filename)) {
						$list[$file] = $this->getPathsByDir($filename);
					}
					else {
						$list[$file] = $filename;
					}
				}
			}
			closedir($handle);
		}

		return $list;
	}

	/**
	* Get directory path by key.
	* @access public
	* @return string
	*/
	function getDir($key = null) {
		$dir =  $this->getTemporaryDir();

		if ($key) {
			$dir .= '/';
			if ($this->prefix) {
				$dir .= $this->prefix;
			}
			$dir .= $key;
		}

		if (file_exists($dir)) {
			touch($dir);
		}

		return $dir;
	}

	/**
	* Get temporary directory path.
	* @access public
	* @return string
	*/
	function getTemporaryDir() {
		$dir =  $this->temporary . '/' . $this->personal;
		return $dir;
	}

	/**
	* Make directory.
	* @access public
	*/
	function makeDir() {
		$this->makeDirByDir($this->getTemporaryDir());
	}

	/**
	* Make directory by directory.
	* @access public
	*/
	function makeDirByDir($dir) {
		if (file_exists($dir)) {
			return;
		}

		mkdir($dir);
	}

	/**
	* Make parent directory.
	* @access public
	*/
	function makeParentDir($path) {
		if (!$path) {
			return;
		}

		$parentDir = dirname($path);
		if (!file_exists($parentDir)) {
			$this->makeParentDir($parentDir);
		}

		if (!file_exists($path)) {
			$this->makeDirByDir($path);
		}
	}

	/**
	* Remove directory.
	* @access public
	*/
	function removeDir() {
		$this->removeDirByDir($this->getTemporaryDir());
	}

	/**
	* Remove directory by directory.
	* @access public
	*/
	function removeDirByDir($dir) {
		if (!file_exists($dir)) {
			return;
		}

		$fileList = $this->getPathsByDir($dir);
		foreach ($fileList as $file) {
			if (is_dir($file)) {
				$this->removeDirByDir($file);
			}
			else {
				unlink($file);
			}
		}
		rmdir($dir);
	}

	/**
	* Remove old directory.
	* @access public
	*/
	function removeOldDirs() {
		$dirList = $this->getPathsByDir($this->temporary);

		$dateformat = 'Y-m-d H:i:s';
		$n = getdate();
		$threshold = date($dateformat, mktime($n['hours'], $n['minutes'], $n['seconds'] - $this->expire, $n['mon'], $n['mday'], $n['year']));
		foreach ($dirList as $dir) {
			$mtime = date($dateformat, filemtime($dir));
			if ($mtime < $threshold) {
				$this->removeDirByDir($dir);
			}
		}
	}

	/**
	* Create temporary filename.
	* @access public
	* @return string
	*/
	function createTemporaryFilename($key, $name) {
		if ($name) {
			$info = pathinfo($name);
			$ext = $info['extension'];
		}

		$filename = $this->getDir($key) . '/' . trim($this->basename, '/');
		if ($ext) {
			$filename .= '.' . $ext;
		}

		return $filename;
	}
}
?>