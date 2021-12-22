<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/util/google/gapi.class.php');

class Action extends AuthAction {

	function execute() {
		if($this->pass_n) {

			if($this->pass != $this->pass_n) {
				$this->change_pass = true;
			} else {
				$this->change_pass = false;
			}

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
		} elseif(!preg_match('/[^.]+\.gif$/ui', $this->logo['name'])) {
//			$this->errors['logo'] = "ロゴ画像にはgif画像をご指定ください。";
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

		if(!empty($this->publisher_prefix_next)) {
			$prefixlist = explode(",", $this->publisher_prefix_next);
			foreach ($prefixlist as $k => $v) {
				if(!mb_ereg_match ("^\d{2,7}$", $v)) {
					$this->errors['publisher_prefix_next'] = '出版社記号の形式をご確認ください。';
					break;
				}
			}
		}

		if(!$this->from_person_unit) {
//			$this->errors['from_person_unit'] = '部署の入力をご確認ください。';
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
//			$this->errors['id'] = "HONDANA用IDをご確認ください。";
		} elseif(!$this->isId($this->id)) {
//			$this->errors['id'] = "IDで使用出来る文字は、半角英数字、-のみです。";
		} elseif($this->idDisallowList[$this->id]) {
//			$this->errors['id'] = "ご指定のIDは使用することが出来ません。";
		}
		if(!$this->pass_n) {
//			$this->errors['pass_n'] = "HONDANA用パスワードをご確認ください。";
		} elseif($this->pass_n != $this->pass_c) {
			$this->errors['pass_n'] = "HONDANA用パスワードをご確認ください。";
		}

		if(!$this->design) {
//			$this->errors['design'] = "デザインを選択してください。";
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

		$gaid = false;
		if(empty($this->ga_account)) {
//			$this->errors['ga_account'] = "HONDANA用IDをご確認ください。";
		} elseif(!$this->isId($this->ga_account)) {
//			$this->errors['id'] = "IDで使用出来る文字は、半角英数字、-のみです。";
		}else{
			$gaid = true;
		}

		$gapass = false;
		if(empty($this->ga_password)) {
//			$this->errors['ga_account'] = "HONDANA用IDをご確認ください。";
		}else{
			$gapass = true;
		}

		$garepo=false;
		if(empty($this->ga_report)) {
//			$this->errors['ga_account'] = "HONDANA用IDをご確認ください。";
		} elseif(!$this->isNumber($this->ga_report)) {
			$this->errors['ga_report'] = "Google Analitics用　レポートIDで使用出来る文字は、半角数字のみです。";
		}else{
			$garepo = true;
		}

		if($gaid && $gapass && $garepo){
			$reportCheck = true;
			try {
				$ga = new gapi($this->ga_account,$this->ga_password);
			} catch (Exception $exc) {
				$this->errors['ga_account'] = "ログイン出来ません。アカウント情報をご確認下さい。";
				$reportCheck=false;
			}
			if($reportCheck){
				try {
					$ga->requestReportData($this->ga_report,
						array("date"),
						array("pageviews","visits","visitors"),
						null,null,
						null,
						null,
						null,1);
				} catch (Exception $exc) {
					$this->errors['ga_report'] = "レポートを取得出来ません。アカウント情報をご確認下さい。";
				}
			}

		}

        $mails = array();
		if(!empty($this->book_notice_mail)) {
            $this->book_notice_mail = str_replace(" ", "", $this->book_notice_mail);
            $this->book_notice_mail = str_replace("　", "", $this->book_notice_mail);
            $mails = explode(",", $this->book_notice_mail);
            foreach( $mails as $key => $val){
                if(!$this->isMail($val)){
                    $this->errors['book_notice_mail'] = '書誌情報変更お知らせメールアドレスの形式をご確認ください。';
                    break;
                }
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