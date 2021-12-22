<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function isSlashDate($value) {
		return preg_match('/^[0-9]{4}[\/][0-1][0-9][\/][0-3][0-9]$/u', $value);
	}

	function execute() {
		$db =& $this->_db;
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/sql/publisher.sql');

		$row = $db->fetch_assoc($result);

		$id = false;
		$pass = false;
		$repoID = false;
		if(!empty ($row["ga_account"])) {
			$id = $row["ga_account"];
		}
		if(!empty ($row["ga_password"])) {
			$pass = $row["ga_password"];
		}
		if(!empty ($row["ga_report"])) {
			$repoID = $row["ga_report"];
		}
		$this->setProperties($row);
		if(empty($this->gapi_version)) {
			$this->gapi_version = '1.3';
		}

		$result = $db->statement('admin/publisher/sql/info.sql');
		$tree = $db->buildTree($result);
		$this->infoList = $tree;

		$this->total=array();
		if ($id && $pass && $repoID || ($repoID && $this->gapi_version == '2.0')) {

			$this->startDate = date("Y/m/d" , time() - 60*60*24*31);
			$this->endDate = date("Y/m/d");
			$this->periodError = '';
			if(!empty($_REQUEST["sdate"]) && !empty($_REQUEST["edate"])){
				if((!$this->isSlashDate($_REQUEST["sdate"]) || !$this->isSlashDate($_REQUEST["edate"]))){
					$this->periodError = '日付の形式が正しくありません。
YYYY/mm/ddの形式で入力してください。';
				}
				else{
					$this->startDate = $_REQUEST["sdate"];
					$this->endDate = $_REQUEST["edate"];

					if(str_replace("/", "", $this->startDate) > str_replace("/", "", $this->endDate)){
						$orgStartDate = $this->startDate;
						$this->startDate = $this->endDate;
						$this->endDate = $orgStartDate;
					}
				}
			}
			$startDate = str_replace("/", "-", $this->startDate);
			$endDate = str_replace("/", "-", $this->endDate);

			if($this->gapi_version == '2.0') {
				// GAPI version 2.0
				require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/util/google/gapi2/gapi.class.php');
				try {
					$ga = new gapi('136362893032-7dulc77448rhkaf0pdp4ng2aqhqns03i@developer.gserviceaccount.com','gapitest-2b738f51f8ee.p12');
				} catch (Exception $exc) {
					$this->total["error"]="1";
					return;
				}
			} else {
				// GAPI version 1.3
				require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/util/google/gapi.class.php');
				try {
					$ga = new gapi($id,$pass);
				} catch (Exception $exc) {
					$this->total["error"]="1";
					return;
				}
			}

			try {
				$ga->requestReportData($repoID,
					array("date"),
					array("pageviews","visits","visitors"),
					null,null,
					$startDate,
					$endDate,
					null,1);
			} catch (Exception $exc) {
				$this->total["error"]="1";
				return;
			}

			$this->total["pageviews"] = $ga->getPageviews();
			$this->total["visits"] = $ga->getVisits();
			$this->total["visitors"] = $ga->getVisitors();

		}



	}
}
?>