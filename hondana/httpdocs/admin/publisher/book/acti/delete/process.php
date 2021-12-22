<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		foreach($this->deleteList as $row){
			$dirname = sprintf('%s/publisher/%s/files/actibook/%s', $_SERVER['DOCUMENT_ROOT'], $_SESSION['id'], $row['book_no']);

			if(file_exists($dirname)) {
				$command = sprintf('rm -fr %s', $dirname);
				exec($command);
			}
		}

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>