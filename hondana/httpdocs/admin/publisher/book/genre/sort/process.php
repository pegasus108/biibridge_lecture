<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		$errors = array();
		$db =& $this->_db;

		$db->assign('publisher_no', $_SESSION['publisher_no']);
		$db->assign('genre_no', $this->genre_no);

		$result = $db->statement('admin/publisher/book/genre/sql/check.sql');
		$tree = $db->buildTree($result);
		if (!empty($tree[0]['rgt']) && !empty($tree[0]['lft']) && !empty($tree[0]['myDistance'])) {
			if ($this->order == "up") {
				$db->assign('myL', $tree[0]['lft']);
				$result = $db->statement('admin/publisher/book/genre/sql/check_up.sql');
			}elseif($this->order == "down"){
				$db->assign('myR', $tree[0]['rgt']);
				echo $tree[0]['rgt'];
				$result = $db->statement('admin/publisher/book/genre/sql/check_down.sql');
			} else {
				$errors['sort'] = 'システムで問題が発生しました。お手数ですが、システム管理者までお問い合わせください。';
			}

			$tree = $db->buildTree($result);
			if (empty($tree[0]['rgt']) || empty($tree[0]['lft']) || empty($tree[0]['toDistance'])) {
				$errors['sort'] = 'システムで問題が発生しました。お手数ですが、システム管理者までお問い合わせください。';
			}
		} else {
			$errors['sort'] = 'システムで問題が発生しました。お手数ですが、システム管理者までお問い合わせください。';
		}

		if(!count($errors)) {
			$db->begin();
			if($this->order == "up"){
				$db->statement('admin/publisher/book/genre/sql/sort_up.sql');
			}elseif($this->order == "down"){
				$db->statement('admin/publisher/book/genre/sql/sort_down.sql');
			}
			$db->commit();
		} else {
			$_SESSION['mess']=$errors['sort'];
		}

		$this->clearProperties();
		$this->__controller->redirectToURL('../');

		return false;
	}
}
?>