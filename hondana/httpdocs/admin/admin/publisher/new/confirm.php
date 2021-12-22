<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {

	function prepare() {
		parent::prepare();

		$this->idDisallowList = array();
		$this->idDisallowList['core'] = 'core';
		$this->idDisallowList['lib'] = 'lib';
		$this->idDisallowList['css'] = 'css';
		$this->idDisallowList['design1-1'] = 'design1-1';
		$this->idDisallowList['design1-2'] = 'design1-2';
		$this->idDisallowList['design1-3'] = 'design1-3';
		$this->idDisallowList['design1-4'] = 'design1-4';
		$this->idDisallowList['design1-5'] = 'design1-5';
		$this->idDisallowList['design2-1'] = 'design2-1';
		$this->idDisallowList['design2-2'] = 'design2-2';
		$this->idDisallowList['design2-3'] = 'design2-3';
		$this->idDisallowList['design2-4'] = 'design2-4';
		$this->idDisallowList['design2-5'] = 'design2-5';
		$this->idDisallowList['design3-1'] = 'design3-1';
		$this->idDisallowList['design3-2'] = 'design3-2';
		$this->idDisallowList['design3-3'] = 'design3-3';
		$this->idDisallowList['design3-4'] = 'design3-4';
		$this->idDisallowList['design3-5'] = 'design3-5';

		$db =& $this->_db;

		$result = $db->statement('admin/admin/publisher/sql/publisher_list.sql');
		$tree = $db->buildTree($result, 'id');
		$this->idDisallowList = array_merge($this->idDisallowList, $tree);

		// 画像関係
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/img/uploads/common',
			$siteroot
		);

		// thumb size
		$this->width = 200;
		$this->height = 50;

		$properties = $this->_uploader->getProperties();

		$this->setProperties($properties);

		//画像処理
		$key = 'logo';
		if ($this->_uploader->exists($key)) {
			$temp_path = $this->_uploader->getTemporaryPath($key);
			$this->convertGeometry($temp_path, $this->width, $this->height);
		}
	}

	function execute() {
		if($this->pass_n) {
			$this->pass = $this->pass_n;
		}
	}

	function validate() {
		$this->errors = array();

		if(!$this->publisher_no) {
//			$this->errors['publisher_no'] = "エラーメッセージ";
		}
		if(!$this->name) {
			$this->errors['name'] = '会社名の入力をご確認ください。';
		}
		if (!$this->kana) {
			$this->errors['kana'] = '会社名カナの入力をご確認ください。';
		}elseif(!$this->isKatakana($this->kana)){
			$this->errors['kana'] = '会社名カナの記入は全角カタカナでお願いいたします。';
		}
		if(!$this->zipcode) {
			$this->errors['zipcode'] = '会社郵便番号の入力をご確認ください。';
		}elseif(!$this->isZip($this->zipcode)){
			$this->errors['zipcode'] = '会社郵便番号の形式をご確認ください。';
		}
		if(!$this->address) {
			$this->errors['address'] = '会社住所の入力をご確認ください。';
		}
		if(!$this->tel) {
			$this->errors['tel'] = '会社電話番号の入力をご確認ください。';
		}elseif(!$this->isTel($this->tel)){
			$this->errors['tel'] = '会社電話番号の形式をご確認ください。';
		}
		if(!$this->fax) {
//			$this->errors['fax'] = "エラーメッセージ";
		}elseif(!$this->isTel($this->fax)){
			$this->errors['fax'] = '会社FAXの形式をご確認ください。';
		}
		if(!$this->logo) {
//			$this->errors['logo'] = "エラーメッセージ";
		} elseif(!preg_match('/[^.]+(\.gif|\.png)$/ui', $this->logo['name'])) {
			$this->errors['logo'] = "ロゴ画像にはpngまたはgif画像をご指定ください。";
		}
		if(!$this->transaction_code) {
//			$this->errors['transaction_code'] = "エラーメッセージ";
		}elseif(!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->transaction_code)){
//			$this->errors['transaction_code'] = '取引コードは半角英数4桁でご記入ください。';
		}
		if(!$this->publisher_prefix) {
//			$this->errors['from_person_unit'] = '出版社記号の入力をご確認ください。';
		}else if(!mb_ereg_match ("^\d{2,7}$", $this->publisher_prefix)) {
			$this->errors['publisher_prefix'] = '出版社記号の形式をご確認ください。';
		}

		if(!$this->person_name) {
			$this->errors['person_name'] = '担当者名の入力をご確認ください。';
		}
		if(!$this->person_mail) {
			$this->errors['person_mail'] = '担当者メールアドレスの入力をご確認ください。';
		}elseif(!$this->isMail($this->person_mail)){
			$this->errors['person_mail'] = '担当者メールアドレスの形式をご確認ください。';
		}
		if(!$this->copyright) {
//			$this->errors['copyright'] = "エラーメッセージ";
		}
		if(!$this->url) {
//			$this->errors['url'] = "エラーメッセージ";
		} elseif(!$this->isHttpUrl($this->url)) {
			$this->errors['url'] = "URLはhttp://から始まり、/で終わる必要があります。";
		}
		if(!$this->id) {
			$this->errors['id'] = "HONDANA用IDをご確認ください。";
		} elseif(!$this->isId($this->id)) {
			$this->errors['id'] = "IDで使用出来る文字は、半角英数字、-のみです。";
		} elseif($this->idDisallowList[$this->id]) {
			$this->errors['id'] = "ご指定のIDは使用することが出来ません。";
		}
		if(!$this->pass_n) {
			$this->errors['pass_n'] = "HONDANA用パスワードをご確認ください。";
		} elseif($this->pass_n != $this->pass_c) {
			$this->errors['pass_n'] = "HONDANA用パスワードをご確認ください。";
		}
		if(!$this->linkage_person_name) {
			$this->errors['linkage_person_name'] = 'データ連携担当者名の入力をご確認ください。';
		}
		if(!$this->linkage_person_mail) {
			$this->errors['linkage_person_mail'] = 'データ連携担当者メールアドレスの入力をご確認ください。';
		}elseif(!$this->isMail($this->linkage_person_mail)){
			$this->errors['linkage_person_mail'] = 'データ連携担当者メールアドレスの形式をご確認ください。';
		}
		if(!$this->description) {
//			$this->errors['description'] = "エラーメッセージ";
		}
		if(!$this->catchphrase) {
//			$this->errors['catchphrase'] = "エラーメッセージ";
		}
		if(!$this->amazon_associates_id) {
//			$this->errors['amazon_associates_id'] = "エラーメッセージ";
		}
		if(!$this->rakuten_affiliate_id) {
//			$this->errors['rakuten_affiliate_id'] = "エラーメッセージ";
		}
		if(!$this->seven_and_y_url) {
//			$this->errors['seven_and_y_url'] = "エラーメッセージ";
		} elseif(!ereg('^http:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+$', $this->seven_and_y_url)) {
			$this->errors['seven_and_y_url'] = "http://から始まる必要がございます。";
		}
		if(!$this->google_analytics_tag) {
//			$this->errors['google_analytics_tag'] = "エラーメッセージ";
		}
		if(!$this->cart) {
//			$this->errors['cart'] = "エラーメッセージ";
		}
		if(!$this->bookservice_no) {
//			$this->errors['bookservice_no'] = "エラーメッセージ";
		}
		if(!$this->contact_mail) {
			$this->errors['contact_mail'] = 'お問い合わせ用メールアドレスの入力をご確認ください。';
		}elseif(!$this->isMail($this->contact_mail)){
			$this->errors['contact_mail'] = 'お問い合わせ用メールアドレスの形式をご確認ください。';
		}
		if($this->cart == 1) {
			if(!$this->cart_mail) {
				$this->errors['cart_mail'] = 'ショッピングカートメール送信先の入力をご確認ください。';
			}elseif(!$this->isMail($this->cart_mail)){
				$this->errors['cart_mail'] = 'ショッピングカートメール送信先の形式をご確認ください。';
			}
		} else {
			if(!$this->cart_mail) {
//				$this->errors['cart_mail'] = 'ショッピングカートメール送信先の入力をご確認ください。';
			}elseif(!$this->isMail($this->cart_mail)){
				$this->errors['cart_mail'] = 'ショッピングカートメール送信先の形式をご確認ください。';
			}
		}

		if(count($this->errors)) {
			return 'input';
		}

		return true;
	}

	function isId($value) {
		return preg_match('/^[0-9a-zA-Z-]+$/u', $value);
	}
}
?>