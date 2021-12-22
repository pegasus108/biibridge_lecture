<?php
require_once('Debug.php');

class AbstractController extends Debug {
	var $__parameters = null;
	var $__actionName = null;
	var $__action = null;
	var $__filters = null;
	var $__filterResult = null;

	var $__systemCharset = 'UTF-8';
	var $__templateCharset = 'UTF-8';

	function __construct() {
		Debug::__construct();

		$this->addParameter($this->__actionName);
	}

	function init() {
		Debug::init();
		$this->__parameters = array();
		$this->__actionName = 'action';
		$this->__filters = array();
	}

	/**
	* Run controller.
	* @access public
	*/
	function run() {
		$this->__filterResult = $this->beginFiltering();
		if ($this->__filterResult === false) {
			return;
		}

		if($this->__systemCharset != $this->__templateCharset) {
			mb_convert_variables($this->__systemCharset, $this->__templateCharset, $_REQUEST);
			if (get_magic_quotes_gpc()) {
				array_walk($_REQUEST, array(&$this, 'stripslashes'));
			}
		}

		$this->setProperties($_REQUEST, true);

		$this->__filterResult = $this->prepareFiltering();
		if ($this->__filterResult === false) {
			return;
		}

		$action = $this->buildAction();
		$viewName = $this->executeAction($action);

		$this->__filterResult = $this->postFiltering();
		if ($this->__filterResult === false) {
			return;
		}

		if ($viewName !== false) {
			$this->renderView($action, $viewName);
		}

		$this->__filterResult = $this->endFiltering();
		if ($this->__filterResult === false) {
			return;
		}
	}

	/**
	* Build action.
	* @access public
	* @return object
	*/
	function buildAction() {
		//Please implement.
	}

	/**
	* Execute action.
	* @access public
	* @return mixed
	*/
	function executeAction(&$action) {
		//Please implement.
	}

	/**
	* Render view.
	* @access public
	*/
	function renderView(&$action, $viewName) {
		//Please implement.
	}

	/**
	* Get controller id.
	* @access public
	* @return string
	*/
	function getId() {
		$id = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
		return $id;
	}

	/**
	* Get controller directory.
	* @access public
	* @return string
	*/
	function getDir() {
		$dir = substr(dirname($_SERVER['PHP_SELF']), 1);
		return $dir;
	}

	/**
	* Add parameter.
	* @access public
	*/
	function addParameter($parameter) {
		$this->addList($this->__parameters, $parameter);
	}

	/**
	* Remove parameter.
	* @access public
	*/
	function removeParameter($parameter) {
		$this->removeList($this->__parameters, $parameter);
	}

	/**
	* Get properties.
	* @access public
	* @return array
	*/
	function getProperties() {
		$hash = array();
		foreach (get_object_vars($this) as $key => $value) {
			if (preg_match('/^_.*/ui', $key)) {
				continue;
			}

			$hash[$key] = $value;
		}
		return $hash;
	}

	/**
	* Set properties.
	* @access public
	*/
	function setProperties(&$hash, $force = false) {
		if (!is_array($hash) || count($hash) <= 0) {
			return;
		}

		foreach ($hash as $key => $value) {
			if (in_array($key, $this->__parameters)) {
				$key = '__' . $key;
			}
			else {
				if (preg_match('/^_.*/ui', $key)) {
					continue;
				}
			}

			if (!isset($this->$key) || $force) {
				$this->$key = $value;
			}
		}
	}

	/**
	* Clear properties.
	* @access public
	*/
	function clearProperties() {
		$hash =& $this->getProperties();
		foreach ($hash as $key => $value) {
			unset($this->$key);
		}
	}

	/**
	* Redirect to action.
	* @access public
	*/
	function redirectToAction($action) {
		$script = $_SERVER['SCRIPT_NAME'];

		$parameter = '';
		if ($action) {
			$parameter = '?action=' . $action;
		}
		else {
			$parameter = '';
		}

		$url = $script . $parameter;
		$this->redirectToURL($url);
	}

	/**
	* Redirect to URL.
	* @access public
	*/
	function redirectToURL($url) {
		header('Location: ' . $url);
	}

	/**
	* Add filter.
	* @access public
	*/
	function addFilter($filter) {
		$this->addList($this->__filters, $filter);
	}

	/**
	* Remove filter.
	* @access public
	*/
	function removeFilter($filter) {
		$this->removeList($this->__filters, $filter);
	}

	/**
	* After begin, it filters.
	* @access private
	* @return bool
	*/
	function beginFiltering() {
		return $this->filtering('Beginfilter', false);
	}

	/**
	* After prepare, it filters.
	* @access private
	* @return bool
	*/
	function prepareFiltering() {
		return $this->filtering('Prefilter', false);
	}

	/**
	* After post, it filters.
	* @access private
	* @return bool
	*/
	function postFiltering() {
		return $this->filtering('Postfilter', true);
	}

	/**
	* After end, it filters.
	* @access private
	* @return bool
	*/
	function endFiltering() {
		return $this->filtering('Endfilter', true);
	}

	/**
	* It filters sequentially.
	* @access private
	* @return bool
	*/
	function filtering($suffix, $reverse = false) {
		$filters = $this->__filters;

		$result = true;
		while (count($filters)) {
			if ($reverse) {
				$filter = array_pop($filters);
			}
			else {
				$filter = array_shift($filters);
			}

			$methodName = $filter . $suffix;
			$methods = get_class_methods(get_class($this));

			$exists = false;
			if (in_array($methodName, $methods)) {
				$exists = true;
			}
			elseif (in_array(strtolower($methodName), $methods)) {
				$exists = true;
			}

			if ($exists) {
				if ($this->$methodName($result) === false) {
					$result = false;
				}
			}
		}

		return $result;
	}

	/**
	* Add value to list.
	* @access public
	*/
	function addList(&$list, $value) {
		$list[] = $value;
	}

	/**
	* Remove value from list.
	* @access public
	*/
	function removeList(&$list, $value) {
		if (in_array($value, $list)) {
			array_splice($list, array_search($value, $list));
		}
	}

	/**
	* Strip slashes on magic_quotes_gpc=on.
	* @access public
	*/
	function stripslashes(&$value, $key) {
		if (is_array($value)) {
			array_walk($value, array(&$this, 'stripslashes'));
		}
		else {
			$value = stripslashes($value);
		}
	}
}
?>