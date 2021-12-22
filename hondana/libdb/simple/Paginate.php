<?php
require_once('Debug.php');

class Paginate extends Debug {
	/**
	* Page control information.
	* @access public
	* @return array
	*/
	function getPageInfo($page, $total, $amount = 0, $pageAmount = 0) {
		if (!$page) {
			$page = 1;
		}

		if (0 < $amount) {
			if (0 < $total) {
				$first = ($page - 1) * $amount + 1;
			}
			else {
				$first = 0;
			}
			$last = $first + $amount - 1;
			if ($total < $last) {
				$last = $total;
			}
			$pageTotal = ceil($total / $amount);
			$next = $page + 1;
			if ($pageTotal <= $page) {
				$next = null;
			}
			$prev = $page - 1;
			if ($page <= 1) {
				$prev = null;
			}
			
			if ($pageAmount) {
				$pageFirst = $page - (ceil($pageAmount / 2) - 1);
				$pageLast = $page + floor($pageAmount / 2);
				if ($pageFirst < 1) {
					$pageLast += 1 - $pageFirst;
					$pageFirst = 1;
					if ($pageTotal < $pageLast) {
						$pageLast = $pageTotal;
					}
				}
				if ($pageTotal < $pageLast) {
					$pageFirst += $pageTotal - $pageLast;
					$pageLast = $pageTotal;
					if ($pageFirst < 1) {
						$pageFirst = 1;
					}
				}
				$pageNext = $page + $pageAmount;
				if ($pageTotal < $pageNext) {
					$pageNext = $pageTotal;
				}
				if ($pageTotal <= $pageLast) {
					$pageNext = null;
				}
				$pagePrev = $page - $pageAmount;
				if ($pagePrev < 1) {
					$pagePrev = 1;
				}
				if ($pageFirst <= 1) {
					$pagePrev = null;
				}
			}
		}
		else {
			$page = 1;
			if (0 < $total) {
				$first = 1;
			}
			else {
				$first = 0;
			}
			$last = $total;
			$next = null;
			$prev = null;
			$pageTotal = 1;
			$pageFirst = 1;
			$pageLast = 1;
			$pageNext = null;
			$pagePrev = null;
		}
	
		$pageInfo = array();
		$pageInfo['page'] = $page;
		$pageInfo['amount'] = $amount;
		$pageInfo['total'] = $total;
		$pageInfo['first'] = $first;
		$pageInfo['last'] = $last;
		$pageInfo['next'] = $next;
		$pageInfo['prev'] = $prev;
		$pageInfo['pageAmount'] = $pageAmount;
		$pageInfo['pageTotal'] = $pageTotal;
		$pageInfo['pageFirst'] = $pageFirst;
		$pageInfo['pageLast'] = $pageLast;
		$pageInfo['pageNext'] = $pageNext;
		$pageInfo['pagePrev'] = $pagePrev;

		return $pageInfo;
	}
}
?>