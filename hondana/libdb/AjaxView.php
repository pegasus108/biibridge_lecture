<?php
require_once(dirname(__FILE__) . '/simple/DefaultView.php');
require_once('AjaxRenderer.php');

class AjaxView extends DefaultView {
	function fetch() {
		$this->__renderer = new AjaxRenderer();

		$this->assignProperties();

		$action = $this->__action;
		if (!$action->__module) {
			$action->__module = $action->__controller->__module;
		}
		if (!$action->__view) {
			$action->__view = $action->__controller->__action;
		}

		if (strpos($action->__view, '/') === 0) {
			$templateFile = $this->__prefix . substr($action->__view, 1) . $this->__suffix;
		}
		elseif ($action->__module) {
			$templateFile = $action->__module . '/' . $this->__prefix . $action->__view . $this->__suffix;
		}
		else {
			$templateFile = $this->__prefix . $action->__view . $this->__suffix;
		}
		$text = $this->__renderer->fetch($templateFile);
		
		return $text;
	}
}
?>