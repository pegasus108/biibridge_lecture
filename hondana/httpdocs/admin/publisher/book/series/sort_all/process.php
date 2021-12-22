<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {

	// エラーメッセージ
	private $error_msg = '';

	function execute() {
		$json = file_get_contents('php://input');
		$data = json_decode($json);

		// lft, rgt, depthを再設定
		$this->sort($data);

		if(empty($this->error_msg)){
			// DBに反映
			$this->update($data);

			echo '保存に成功しました。';
		}else{
			echo $this->error_msg;
		}
	}

	function sort($data, $lft = 2, $depth = 1){
		if($depth > 3){
			$this->error_msg = 'error: 階層は3階層までとなります。';
			return false;
		}

		foreach($data as $key => $value){
			$data[$key]->lft = $lft;
			$lft++;

			if(!empty($data[$key]->children)){
				$lft = $this->sort($data[$key]->children, $lft, $depth + 1);
			}

			$data[$key]->rgt = $lft;
			$lft++;

			$data[$key]->depth = $depth;
		}
		return $lft;
	}

	function update($data){
		$db =& $this->_db;

		foreach($data as $value){
			$db->assign('lft', $value->lft);
			$db->assign('rgt', $value->rgt);
			$db->assign('depth', $value->depth);
			$db->assign('series_no', $value->id);

			$db->begin();
			$db->statement('admin/publisher/book/series/sql/sort_update.sql');
			$db->commit();

			$this->clearProperties();

			if(!empty($value->children)){
				$this->update($value->children);
			}
		}
	}
}
?>