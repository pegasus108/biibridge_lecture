<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	function prepare() {
		parent::prepare();

		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/publisher/'.$_SESSION['id'].'/images/book',
			$siteroot . '/publisher/'.$_SESSION['id']
		);

		$this->width = 200;
		$this->grtWidth = 500;
		$this->midWidth = 150;
		$this->smlWidth = 78;

		$this->height = 300;
		$this->grtHeight = 2000;
		$this->midHeight = 200;
		$this->smlHeight = 110;
	}

	function execute() {
		$db =& $this->_db;
		$up =& $this->_uploader;

		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		//グレートサイズがあるかどうかを確認する
		$result = $db->statement('admin/publisher/book/sql/publisher.sql');
		$this->grt_flg = $db->fetch_assoc($result);

		$key = 'zip';
		foreach($this->uploadImageList as $uploadImage) {
			if($uploadImage['file']){
				$perma_path = $up->getPermanentPath($key, $uploadImage['book_no']);

				//もしグレートサイズにフラグが立っていたら
				if($this->grt_flg['great_img_status'] == 1) {
					$uploadImage['grt_file'] = preg_replace('/\.((?!com).+?)$/u','_grt.$1', $uploadImage['file']);
				}
				$uploadImage['mid_file'] = preg_replace('/\.((?!com).+?)$/u','_mid.$1', $uploadImage['file']);
				$uploadImage['sml_file'] = preg_replace('/\.((?!com).+?)$/u','_sml.$1', $uploadImage['file']);

				//もしグレートサイズにフラグが立っていたら
				if($this->grt_flg['great_img_status'] == 1) {
					copy($uploadImage['file'], $uploadImage['grt_file']);
				}
				copy($uploadImage['file'], $uploadImage['mid_file']);
				copy($uploadImage['file'], $uploadImage['sml_file']);

				//もしグレートサイズにフラグが立っていたら
				if($this->grt_flg['great_img_status'] == 1) {
					$this->convertGeometry($uploadImage['grt_file'], $this->grtWidth, $this->grtHeight);
				}
				$this->convertGeometry($uploadImage['file'], $this->width, $this->height);
				$this->convertGeometry($uploadImage['mid_file'], $this->midWidth, $this->midHeight);
				$this->convertGeometry($uploadImage['sml_file'], $this->smlWidth, $this->smlHeight);

				rename($uploadImage['file'], $perma_path);
				//もしグレートサイズにフラグが立っていたら
				if($this->grt_flg['great_img_status'] == 1) {
					rename($uploadImage['grt_file'], preg_replace('/\.((?!com).+?)$/u','_grt.$1',$perma_path));
				}
				rename($uploadImage['mid_file'], preg_replace('/\.((?!com).+?)$/u','_mid.$1',$perma_path));
				rename($uploadImage['sml_file'], preg_replace('/\.((?!com).+?)$/u','_sml.$1',$perma_path));
			}
		}
		//$up->remove($key);

		$db->begin();
		$db->statement('admin/admin/import_images/sql/update_book_image.sql');
		$db->commit();

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}
}
?>