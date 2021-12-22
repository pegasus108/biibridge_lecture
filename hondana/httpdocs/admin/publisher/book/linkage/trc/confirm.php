<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	function validate() {
		$this->errors = array();
		$d = $this->getErrorMessages();

		if (!$this->issuer_1) {
			$this->errors['issuer_1'] = '記入をご確認ください。';
		} elseif($this->isGarbled($this->issuer_1)) {
			$this->errors['issuer_1'] = $d['garbled'];
		}

		$this->convert($this->issuer_kana_1,"KVC");
		if (!$this->issuer_kana_1) {
			$this->errors['issuer_kana_1'] = '記入をご確認ください。';
		} elseif(!$this->isKatakana($this->issuer_kana_1)) {
				$this->errors['issuer_kana_1'] = '全角カタカナでご入力ください';
		} elseif($this->isGarbled($this->issuer_kana_1)) {
			$this->errors['issuer_kana_1'] = $d['garbled'];
		}

		if (!$this->publisher_2) {
//			$this->errors['publisher_2'] = '記入をご確認ください。';
		} elseif($this->isGarbled($this->publisher_2)) {
			$this->errors['publisher_2'] = $d['garbled'];
		}

		$this->convert($this->publisher_kana_2,"KVC");
		if (!$this->publisher_kana_2) {
//			$this->errors['publisher_kana_2'] = '記入をご確認ください。';
		} elseif(!$this->isKatakana($this->publisher_kana_2)) {
				$this->errors['publisher_kana_2'] = '全角カタカナでご入力ください';
		} elseif($this->isGarbled($this->publisher_kana_2)) {
			$this->errors['publisher_kana_2'] = $d['garbled'];
		}

		if (!$this->name_1) {
			$this->errors['name_1'] = '記入をご確認ください。';
		} elseif($this->isGarbled($this->name_1)) {
			$this->errors['name_1'] = $d['garbled'];
		}

		$this->convert($this->kana_1,"KVC");
		if (!$this->kana_1) {
			$this->errors['kana_1'] = '記入をご確認ください。';
		} elseif(!$this->isKatakana($this->kana_1)) {
				$this->errors['kana_1'] = '全角カタカナでご入力ください';
		} elseif($this->isGarbled($this->kana_1)) {
			$this->errors['kana_1'] = $d['garbled'];
		}

		if (!$this->volume_1) {
		} elseif($this->isGarbled($this->volume_1)) {
			$this->errors['volume_1'] = $d['garbled'];
		}

		if (!$this->sub_1) {
		} elseif($this->isGarbled($this->sub_1)) {
			$this->errors['sub_1'] = $d['garbled'];
		}

		$this->convert($this->sub_kana_1,"KVC");
		if (!$this->sub_kana_1) {
		} elseif($this->isGarbled($this->sub_kana_1)) {
			$this->errors['sub_kana_1'] = $d['garbled'];
		}

		if (!$this->author_1) {
		} elseif($this->isGarbled($this->author_1)) {
			$this->errors['author_1'] = $d['garbled'];
		}

		$this->convert($this->author_kana_1,"KVC");
		if (!$this->author_kana_1) {
		} elseif($this->isGarbled($this->author_kana_1)) {
			$this->errors['author_kana_1'] = $d['garbled'];
		}

		if (!$this->author_note_1) {
		} elseif($this->isGarbled($this->author_note_1)) {
			$this->errors['author_note_1'] = $d['garbled'];
		}

		if (!$this->author_2) {
		} elseif($this->isGarbled($this->author_2)) {
			$this->errors['author_2'] = $d['garbled'];
		}

		$this->convert($this->author_kana_2,"KVC");
		if (!$this->author_kana_2) {
		} elseif($this->isGarbled($this->author_kana_2)) {
			$this->errors['author_kana_2'] = $d['garbled'];
		}

		if (!$this->author_note_3) {
		} elseif($this->isGarbled($this->author_note_3)) {
			$this->errors['author_note_3'] = $d['garbled'];
		}

		if (!$this->series_1) {
		} elseif($this->isGarbled($this->series_1)) {
			$this->errors['series_1'] = $d['garbled'];
		}

		$this->convert($this->release_date_1_year,"n");
		$this->convert($this->release_date_1_month,"n");
		$this->convert($this->release_date_1_day,"n");
		if (!($this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day)) {
			$this->errors['release_date_1'] = '記入をご確認ください。';
		}elseif ( !$this->isFormatedMonthDate($this->release_date_1_year.$this->release_date_1_month) ) {
			$this->errors['release_date_1'] = '記入内容をご確認ください。';
		} elseif($this->isGarbled($this->release_date_1_year.$this->release_date_1_month)) {
			$this->errors['release_date_1'] = $d['garbled'];
		}

		if (!$this->content_1) {
			$this->errors['content_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^.{0,105}$', $this->content_1) ){
			$this->errors['content_1'] = '105文字以内でご記入ください。';
		} elseif($this->isGarbled($this->content_1)) {
			$this->errors['content_1'] = $d['garbled'];
		}

		$this->convert($this->price_1,"n");
		if (!$this->price_1) {
			$this->errors['price_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^[0-9]{0,7}$', $this->price_1) ){
			$this->errors['price_1'] = '半角数字7桁以内でご記入ください。';
		} elseif($this->isGarbled($this->price_1)) {
			$this->errors['price_1'] = $d['garbled'];
		}

		$this->convert($this->price_special_1,"n");
		if (!$this->price_special_1) {
//			$this->errors['price_special_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^[0-9]{0,7}$', $this->price_special_1) ){
			$this->errors['price_special_1'] = '半角数字7桁以内でご記入ください。';
		} elseif($this->isGarbled($this->price_special_1)) {
			$this->errors['price_special_1'] = $d['garbled'];
		}

		$this->convert($this->price_special_policy_1_year,"n");
		$this->convert($this->price_special_policy_1_month,"n");
		$this->convert($this->price_special_policy_1_day,"n");
		if (!($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day)) {
//			$this->errors['price_special_policy_1'] = '記入をご確認ください。';
		}elseif ( !mb_ereg_match('^[0-9]{8}$', $this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day) ) {
			$this->errors['price_special_policy_1'] = '記入形式をご確認ください。';
		}elseif ( !$this->isFormatedDate($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day) ) {
			$this->errors['price_special_policy_1'] = '記入内容をご確認ください。';
		} elseif($this->isGarbled($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day)) {
			$this->errors['price_special_policy_1'] = $d['garbled'];
		}

		$this->convert($this->isbn_1,"rna");
		if (!$this->isbn_1) {
		}elseif( $this->isbn_1 == $this->new['isbn_1'] ){
			$this->errors['isbn_1'] = 'ハイフンを含めてご記入ください。';
		}elseif(!mb_ereg_match('^([0-9-]{11}-[0-9X]{1}|[0-9]{3}-[0-9-]{11}-[0-9X]{1})$', $this->isbn_1)){
			$this->errors['isbn_1'] = '半角13桁か17桁でご記入ください。';
		} elseif($this->isGarbled($this->isbn_1)) {
			$this->errors['isbn_1'] = $d['garbled'];
		}

		$this->convert($this->magazine_code_1_1,"n");
		$this->convert($this->magazine_code_1_2,"n");
		if (!$this->magazine_code_1_1.$this->magazine_code_1_2) {
		}elseif(!preg_match('/^[0-9]{7}$/u', $this->magazine_code_1_1.$this->magazine_code_1_2)){
			$this->errors['magazine_code_1'] = '半角数字5桁と2桁でご記入ください。';
		} elseif($this->isGarbled($this->magazine_code_1_1.$this->magazine_code_1_2)) {
			$this->errors['magazine_code_1'] = $d['garbled'];
		}

		$this->convert($this->page_1,"n");
		if (!$this->page_1) {
		}elseif(!preg_match('/^[0-9]{0,4}$/u', $this->page_1)){
			$this->errors['page_1'] = '半角数字4桁以内でご記入ください。';
		} elseif($this->isGarbled($this->page_1)) {
			$this->errors['page_1'] = $d['garbled'];
		}

		$this->convert($this->order_status_1_year,"n");
		$this->convert($this->order_status_1_month,"n");
		$this->convert($this->order_status_1_day,"n");
		if (!($this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day)) {
//			$this->errors['order_status_1'] = '記入をご確認ください。';
		}elseif ( !mb_ereg_match('^[0-9]{8}$', $this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day) ) {
			$this->errors['order_status_1'] = '記入形式をご確認ください。';
		}elseif ( !$this->isFormatedDate($this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day) ) {
			$this->errors['order_status_1'] = '記入内容をご確認ください。';
		} elseif($this->isGarbled($this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day)) {
			$this->errors['order_status_1'] = $d['garbled'];
		}

		$this->convert($this->circulation_1,"n");
		if (!$this->circulation_1) {
		}elseif( !mb_ereg_match('^[0-9]{0,7}$', $this->circulation_1) ){
			$this->errors['circulation_1'] = '半角数字7桁以内でご記入ください。';
		} elseif($this->isGarbled($this->circulation_1)) {
			$this->errors['circulation_1'] = $d['garbled'];
		}

		if (!$this->win_info_1) {
//			$this->errors['win_info_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^.{0,30}$', $this->win_info_1) ){
			$this->errors['win_info_1'] = '30文字以内でご記入ください。';
		} elseif($this->isGarbled($this->win_info_1)) {
			$this->errors['win_info_1'] = $d['garbled'];
		}

		$this->convert($this->reader_page_1,"n");
		if (!$this->reader_page_1) {
		}elseif( !mb_ereg_match('^[0-9]{0,3}$', $this->reader_page_1) ){
			$this->errors['reader_page_1'] = '半角数字3桁以内でご記入ください。';
		} elseif($this->isGarbled($this->reader_page_1)) {
			$this->errors['reader_page_1'] = $d['garbled'];
		}

		if(!$this->explain_1) {
		} elseif($this->isGarbled($this->explain_1)) {
			$this->errors['explain_1'] = $d['garbled'];
		}

		if (!$this->by_format_1) {
//			$this->errors['by_format_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^.{0,10}$', $this->by_format_1) ){
			$this->errors['by_format_1'] = '10文字以内でご記入ください。';
		} elseif($this->isGarbled($this->by_format_1)) {
			$this->errors['by_format_1'] = $d['garbled'];
		}

		if (!$this->by_obi_1) {
//			$this->errors['by_obi_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^.{0,100}$', $this->by_obi_1) ){
			$this->errors['by_obi_1'] = '100文字以内でご記入ください。';
		} elseif($this->isGarbled($this->by_obi_1)) {
			$this->errors['by_obi_1'] = $d['garbled'];
		}

		if (!$this->representative_editor_1) {
//			$this->errors['representative_editor_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^.{0,10}$', $this->representative_editor_1) ){
			$this->errors['representative_editor_1'] = '10文字以内でご記入ください。';
		} elseif($this->isGarbled($this->representative_editor_1)) {
			$this->errors['representative_editor_1'] = $d['garbled'];
		}

		if (!$this->representative_comment_1) {
//			$this->errors['representative_comment_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^.{0,200}$', ereg_replace("\r|\n","",$this->representative_comment_1)) ){
			$this->errors['representative_comment_1'] = '200文字以内でご記入ください。';
		} elseif($this->isGarbled($this->representative_comment_1)) {
			$this->errors['representative_comment_1'] = $d['garbled'];
		}

		if (!$this->conflicts_1) {
//			$this->errors['conflicts_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^.{0,100}$', $this->conflicts_1) ){
			$this->errors['conflicts_1'] = '100文字以内でご記入ください。';
		} elseif($this->isGarbled($this->conflicts_1)) {
			$this->errors['conflicts_1'] = $d['garbled'];
		}

		if (!$this->typist_1) {
//			$this->errors['typist_1'] = '記入をご確認ください。';
		}elseif( !mb_ereg_match('^.{0,10}$', $this->typist_1) ){
			$this->errors['typist_1'] = '10文字以内でご記入ください。';
		} elseif($this->isGarbled($this->typist_1)) {
			$this->errors['typist_1'] = $d['garbled'];
		}

		$this->convert($this->typist_tel_1,"n");
		if (!$this->typist_tel_1) {
		}elseif( !mb_ereg_match('^[0-9]{0,10}$', $this->typist_tel_1) ){
			$this->errors['typist_tel_1'] = '半角数字10桁以内でご記入ください。';
		} elseif($this->isGarbled($this->typist_tel_1)) {
			$this->errors['typist_tel_1'] = $d['garbled'];
		}

		$this->convert($this->type_date_1_year,"n");
		$this->convert($this->type_date_1_month,"n");
		$this->convert($this->type_date_1_day,"n");
		if (!($this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day)) {
//			$this->errors['type_date_1'] = '記入をご確認ください。';
		}elseif ( !mb_ereg_match('^[0-9]{8}$', $this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day) ) {
			$this->errors['type_date_1'] = '記入形式をご確認ください。';
		}elseif ( !$this->isFormatedDate($this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day) ) {
			$this->errors['type_date_1'] = '記入内容をご確認ください。';
		} elseif($this->isGarbled($this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day)) {
			$this->errors['type_date_1'] = $d['garbled'];
		}


		if(count($this->errors)) {
			return 'input';
		}
/*
		if ( !mb_ereg_match('^[0-9]{2}$', $this->release_date_1_day) ) {
			$this->release_date_1_day = mb_convert_kana($this->release_date_1_day,'N');
			$this->release_date_1_month = mb_convert_kana($this->release_date_1_month,'N');
		}
*/
		if ( !mb_ereg_match('^[0-9]{2}$', $this->release_date_1_day) ) {
			$this->release_date_1_ismb = true;
		}
		$this->magazine_code = $this->magazine_code_1.$this->magazine_code_2;

		$this->book_relate_list = $this->news_relate_list;

		return true;
	}
	function isKatakana($value) {
		return mb_ereg_match('^(\(|\)|（|）|．|\.|，|,|・|･|-|=|＝|[a-zａ-ｚＡ-ＺA-Zァ-ヶ]|ー|　| )+$', $value);
	}
}
?>