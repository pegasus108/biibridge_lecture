<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function execute() {
		if(!$this->company_no){
			$this->__controller->redirectToURL('../');
		}

		$db =& $this->_db;
		$db->assign('company_no', $this->company_no);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		/* company category list */
		$result = $db->statement('admin/publisher/company/sql/category_list.sql');
		$tree = $db->buildTree($result, 'company_category_no');
		$this->companyCategoryList = $tree;

		if($this->back) {
			$this->back = false;
			return;
		}

		$result = $db->statement('admin/publisher/company/sql/company.sql');
		$row = $db->fetch_assoc($result);
		$this->setProperties($row);

		// WYSIWYG入力フィールド内 ルートパス → フルパスへ変換して表示 (管理画面から画像が参照できるように)
		$this->setFullpath(array('value'),$_SESSION['publisher_url']);
	}
}
?>