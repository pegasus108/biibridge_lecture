<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/util/yondemillController.php');

class Action extends AuthAction {
	var $rootCategory = '1';
	function execute() {
		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$result = $db->statement('admin/publisher/book/sql/publisher.sql');
		$this->publisher = $db->fetch_assoc($result);

		$result = $db->statement('admin/publisher/book/sql/linkage_list.sql');
		$tree = $db->buildTree($result, 'linkage_no');
		$linkage_id_list = array();
		foreach($tree as $i=>$linkage){
			$linkage_id_list[] = $linkage['id'];
		}
		$db->assign('linkage_id_list', $linkage_id_list);

		// 削除する YONDEMILL IDを取得
		$result = $db->statement('admin/publisher/book/delete/sql/get_yondemill_id.sql');
		$yondemill_list = $db->buildTree($result);
		$deleleYondemillList = array();

		$yondemill = new yondemillController($db);
		foreach ($yondemill_list as $k => $v) {
			$deleleYondemillList = $v['yondemill_id'];

			$data["auth_token"] = $this->publisher['yondemill_auth_token'];
			$yondemillPost['data'] = $data;
			$yondemillPost['yondemill_id'] = $v['yondemill_id'];
			$yondemill->deleteData($yondemillPost);
		}

		$db->assign('deleleYondemillList', $deleleYondemillList);

		// 元サイズの書影を書き出す出版社のリスト
		$originalsizepublisherlist = array(
			120, // 明窓出版
		);

		$db->begin();
		foreach($this->deleteList as $row){
			if(array_search($this->publisher['publisher_no'], $originalsizepublisherlist) !== false) {
				// ■■ 元サイズ 対応出版社 ■■
				unlink($_SERVER['DOCUMENT_ROOT'].preg_replace('/book\//','book/original/',$row['image']));
			}
			unlink($_SERVER['DOCUMENT_ROOT'].$row['image']);
			//もしグレートサイズにフラグが立っていたら
			if($this->publisher['great_img_status'] == 1) {
				unlink($_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_grt.$1',$row['image']));
			}
			unlink($_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_mid.$1',$row['image']));
			unlink($_SERVER['DOCUMENT_ROOT'].preg_replace('/\.((?!com).+?)$/u','_sml.$1',$row['image']));
		}
		$db->statement('admin/publisher/book/sql/delete.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>