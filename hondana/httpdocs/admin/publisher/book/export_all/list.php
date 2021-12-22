<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	function execute() {

		$oneSize = 500;

		$db =& $this->_db;
		$rs = $db->query("select count(*) cnt from book where publisher_no={$_SESSION['publisher_no']};");
		$row = $db->fetch_assoc($rs);
		$cnt = $row["cnt"];

		if($cnt > $oneSize) {
			$this->hasPage = 1;
			$pageSize = ceil($cnt / $oneSize);
		} else {
			$this->hasPage = 0;
		}

		for($i=1 ; $i <= $pageSize ; $i++){
			$start = ( ($i-1) * $oneSize ) + 1;
			$end = $i*$oneSize;
			if($i==$pageSize){
				$end = ($start-1) + ($cnt%$oneSize);
			}

			$start = $start-1;
			$end = $end-1;
			$oneCnt = $end - $start +1;

			$rs = $db->query("select book_date from book where publisher_no={$_SESSION['publisher_no']} order by book_date desc limit {$start},1;");
			$row = $db->fetch_assoc($rs);
			$startDate = $row["book_date"];

			$rs = $db->query("select book_date from book where publisher_no={$_SESSION['publisher_no']} order by book_date desc limit {$end},1;");
			$row = $db->fetch_assoc($rs);
			$endDate = $row["book_date"];

			$this->pages[$i] = array(
				"start" => $startDate,
				"end"=>$endDate,
				"cnt"=>$oneCnt
			);
		}
	}
}
?>