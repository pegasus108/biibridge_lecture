<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function execute() {

		if (!empty($this->mess)) {
			$this->mess = null;
		}

		$db =& $this->_db;
		$rs = $db->query("select count(*) cnt from book where publisher_no={$_SESSION['publisher_no']};");
		$row = $db->fetch_assoc($rs);
		$cnt = $row["cnt"];

		if($cnt){
			$this->hasPage = 1;

			if (file_exists($_SERVER['DOCUMENT_ROOT']."/publisher/".$_SESSION['id']."/mokuroku/download/tankoubon.epub")) {
				$this->tankoubon = date ("Y/m/d 更新", filemtime($_SERVER['DOCUMENT_ROOT']."/publisher/".$_SESSION['id']."/mokuroku/download/tankoubon.epub"));
			} else {
				$this->tankoubon = null;
			}
			if (file_exists($_SERVER['DOCUMENT_ROOT']."/publisher/".$_SESSION['id']."/mokuroku/download/daiwabunko.epub")) {
				$this->daiwabunko = date ("Y/m/d 更新", filemtime($_SERVER['DOCUMENT_ROOT']."/publisher/".$_SESSION['id']."/mokuroku/download/daiwabunko.epub"));
			} else {
				$this->daiwabunko = null;
			}
		}else{
			$this->hasPage = 0;
		}

	}
}
?>