<?php
require_once('AbstractController.php');

class BaseController extends AbstractController {
	var $__moduleName = null;
	var $__module = null;
	var $__moduleDir = null;

	var $__defaultAction = null;
	var $__defaultActionFile = null;
	var $__defaultActionClass = null;
	var $__viewFile = null;
	var $__viewClass = null;

	var $__onlyDefaultAction = null;

	var $__redirectDefaultAction = null;

	var $__persistent = null;
	var $__smallSession = null;

	function __construct() {
		AbstractController::__construct();

		$this->addParameter($this->__moduleName);

		$this->addFilter('redirectOtherModule');
		$this->addFilter('onlyDefaultAction');
		$this->addFilter('redirectDefaultAction');
		$this->addFilter('setDefaultAction');
		$this->addFilter('persistentStore');
	}

	function init() {
		AbstractController::init();

		$this->__moduleName = 'module';
		$this->__module = $this->getDir();

		$this->__defaultAction = 'default';
		$this->__defaultActionFile = dirname(__FILE__) . '/DefaultAction.php';
		$this->__defaultActionClass = 'DefaultAction';
		$this->__viewFile = dirname(__FILE__) . '/DefaultView.php';
		$this->__viewClass = 'DefaultView';

		$this->__onlyDefaultAction = false;
		$this->__redirectDefaultAction = false;
		$this->__persistent = true;
	}

	function buildAction() {
		$methods = get_class_methods(get_class($this));
		if (in_array(strtolower($this->__action), $methods)) {
			return $this;
		}
		else {
			$actionClass = $this->getActionClass();
			$action = new $actionClass($this);
			return $action;
		}
	}
	function getActionClass() {
		$this->requireActionFile();

		$type1 = ucfirst($this->__action) . 'Action';
		$type2 = ucfirst($this->__action);

		if (class_exists($type1)) {
			return $type1;
		}
		elseif (class_exists($type2)) {
			return $type2;
		}
		elseif (class_exists('Action')) {
			return 'Action';
		}
		else {
			return $this->__defaultActionClass;
		}
	}
	function requireActionFile() {
		$type1 = ucfirst($this->__action) . 'Action.php';
		$type2 = $this->__action . '.php';

		if (file_exists($type1)) {
			require_once($type1);
		}
		elseif (file_exists($type2)) {
			require_once($type2);
		}
		else {
			require_once($this->__defaultActionFile);
		}
	}

	function executeAction(&$action) {
		$viewName = false;

		if (is_a($action, 'AbstractController')) {
			$actionName = $action->__action;
			$viewName = $action->$actionName();
		}
		else {
			$viewName = $action->run();
			$properties = $action->getProperties();
			$this->setProperties($properties, true);
		}

		return $viewName;
	}

	function renderView(&$action, $viewName) {
		if ($viewName) {
			$action->__view = $viewName;
		}
		else {
			$action->__view = $this->__action;
		}

		require_once($this->__viewFile);
		$view = new $this->__viewClass($action);
		$view->display();
	}

	/**
	* Redirect other module filter.
	* @access private
	* @return bool
	*/
	function redirectOtherModulePrefilter($result) {
		$moduleDir = $this->getDir();
		if ($this->__module && $this->__module != $moduleDir) {
			$this->redirectToAction($this->__action, $this->__module);
			return false;
		}

		return true;
	}

	/**
	* Only default action filter.
	* @access private
	* @return bool
	*/
	function onlyDefaultActionPrefilter($result) {
		if ($this->__onlyDefaultAction) {
			$this->__action = $this->__defaultAction;
		}

		return true;
	}

	/**
	* Redirect default action filter.
	* @access private
	* @return bool
	*/
	function redirectDefaultActionPrefilter($result) {
		if ($this->__action) {
			return true;
		}

		if ($this->__redirectDefaultAction) {
			$this->redirectToAction($this->__defaultAction);
			return false;
		}

		return true;
	}

	/**
	* Set default action filter.
	* @access private
	* @return bool
	*/
	function setDefaultActionPrefilter($result) {
		if (!$this->__action) {
			$this->__action = $this->__defaultAction;
		}

		return true;
	}

	/**
	* Persistent store filter.
	* @access private
	* @return bool
	*/
	function persistentStorePrefilter($result) {
		if ($this->__persistent) {
			if (!session_id()) {
				session_start();
			}
			require_once('SmallSession.php');

			$this->__smallSession = new SmallSession($this->getId());

			if ($result === false) {
				$this->clearSession();
			}

			$this->loadPropertiesFromSession();
		}

		return true;
	}
	function persistentStorePostfilter() {
		if ($this->__persistent) {
			$this->savePropertiesToSession();
		}

		return true;
	}
	function savePropertiesToSession() {
		$hash = $this->getProperties();

		/*
		 * viewに値を渡した後、セッションから変数を削除したい場合は
		 * 以下のように、unsetを行う。
		 * unset($hash['errors']);
		 * これで、$this->errorsが削除される
		 * 上記の場合、入力画面を表示した際に、必ずエラーメッセージを出さないようにすることが出来る。
		 */
		unset($hash['errors']);

		$this->__smallSession->save($hash);
	}
	function loadPropertiesFromSession() {
		$hash = $this->__smallSession->load();
		$this->setProperties($hash);
	}
	function clearSession() {
		$this->__smallSession->clear();
	}
	function clearProperties() {
		AbstractController::clearProperties();

		$this->clearSession();
	}

	/**
	* Redirect to action with module.
	* @access public
	*/
	function redirectToAction($action, $module = null) {
		$script = '';
		if ($module) {
			$module = trim($module, '/');
			$script = '/' . $module . '/';
		}
		else {
			$script = $_SERVER['SCRIPT_NAME'];
		}

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
}
?>