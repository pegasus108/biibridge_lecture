<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	function validate() {

		$this->errors = array();
		$d = $this->getErrorMessages();

		if (!$this->category_code_1_big){
			$this->errors['category_code_1'] = '選択をご確認ください。';
			return 'input';
		}
		$this->category_code_1 = false;
		switch($this->category_code_1_big){
			case "mag":
				$this->category_code_1 = $this->category_code_1_mag;
				break;
			case "com":
				$this->category_code_1 = $this->category_code_1_com;
				break;
			case "sml":
				$this->category_code_1 = $this->category_code_1_sml;
				break;
			case "new":
				$this->category_code_1 = $this->category_code_1_new;
				break;
			case "all":
				$this->category_code_1 = $this->category_code_1_all;
				break;
			case "gen":
				$this->category_code_1 = $this->category_code_1_gen;
				break;
		}
		if(!$this->category_code_1){
			$this->errors['category_code_1'] = '選択をご確認ください。';
			return 'input';
		}



		$c = $this->category_code_1;
		$u = $this->getUsingFieldList();
		$i = $this->getImportantFieldList();

		$this->convert($this->trade_code_1,"n");
		if ($u['trade_code_1'][$c] && !$this->trade_code_1) {
			if($i['trade_code_1'][$c])
				$this->errors['trade_code_1'] = '記入をご確認ください。';
		}elseif($u['trade_code_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->trade_code_1)){
			//$this->errors['trade_code_1'] = '半角4文字でご記入ください。';
		} elseif($u['trade_code_1'][$c] && $this->isGarbled($this->trade_code_1)) {
			$this->errors['trade_code_1'] = $d['garbled'];
		}

		$this->convert($this->trade_code_branch_1,"n");
		if ($u['trade_code_branch_1'][$c] && !$this->trade_code_branch_1) {
			if($i['trade_code_branch_1'][$c])
				$this->errors['trade_code_branch_1'] = '記入をご確認ください。';
		}elseif($u['trade_code_branch_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{3}$/u', $this->trade_code_branch_1)){
			$this->errors['trade_code_branch_1'] = '半角3文字でご記入ください。';
		} elseif($u['trade_code_branch_1'][$c] && $this->isGarbled($this->trade_code_branch_1)) {
			$this->errors['trade_code_branch_1'] = $d['garbled'];
		}

		if ($u['issuer_1'][$c] && !$this->issuer_1) {
			if($i['issuer_1'][$c])
				$this->errors['issuer_1'] = '記入をご確認ください。';
		}elseif($u['issuer_1'][$c] &&!preg_match('/^.{0,40}$/u', $this->issuer_1)){
			$this->errors['issuer_1'] = '全角20文字以内でご記入ください。';
		} elseif($u['issuer_1'][$c] && $this->isGarbled($this->issuer_1)) {
			$this->errors['issuer_1'] = $d['garbled'];
		}

		$this->convert($this->issuer_kana_1,"kh");
		if ($u['issuer_kana_1'][$c] && !$this->issuer_kana_1) {
			if($i['issuer_kana_1'][$c] || $this->issuer_1)
				$this->errors['issuer_kana_1'] = '記入をご確認ください。';
		}elseif($u['issuer_kana_1'][$c] &&!preg_match('/^([｡-ﾟ]){0,20}$/u', $this->issuer_kana_1)){
			$this->errors['issuer_kana_1'] = '半角カタカナ20文字以内でご記入ください。';
		} elseif($u['issuer_kana_1'][$c] && $this->isGarbled($this->issuer_kana_1)) {
			$this->errors['issuer_kana_1'] = $d['garbled'];
		}

		if ($u['publisher_2'][$c] && !$this->publisher_2) {
			if($i['publisher_2'][$c])
				$this->errors['publisher_2'] = '記入をご確認ください。';
		}elseif($u['publisher_2'][$c] &&!preg_match('/^.{0,40}$/u', $this->publisher_2)){
			$this->errors['publisher_2'] = '全角20文字以内でご記入ください。';
		} elseif($u['publisher_2'][$c] && $this->isGarbled($this->publisher_2)) {
			$this->errors['publisher_2'] = $d['garbled'];
		}

		$this->convert($this->publisher_kana_2,"kh");
		if ($u['publisher_kana_2'][$c] && !$this->publisher_kana_2) {
			if($i['publisher_kana_2'][$c] || $this->publisher_2)
				$this->errors['publisher_kana_2'] = '記入をご確認ください。';
		}elseif($u['publisher_kana_2'][$c] &&!preg_match('/^([｡-ﾟ]){0,20}$/u', $this->publisher_kana_2)){
			$this->errors['publisher_kana_2'] = '半角カタカナ20文字以内でご記入ください。';
		} elseif($u['publisher_kana_2'][$c] && $this->isGarbled($this->publisher_kana_2)) {
			$this->errors['publisher_kana_2'] = $d['garbled'];
		}

		if ($u['handling_company_1'][$c] && !$this->handling_company_1) {
			if($i['handling_company_1'][$c])
				$this->errors['handling_company_1'] = '記入をご確認ください。';
		}elseif($u['handling_company_1'][$c] &&!preg_match('/^.{0,40}$/u', $this->handling_company_1)){
			$this->errors['handling_company_1'] = '全角20文字以内でご記入ください。';
		} elseif($u['handling_company_1'][$c] && $this->isGarbled($this->handling_company_1)) {
			$this->errors['handling_company_1'] = $d['garbled'];
		}

		if ($u['handling_type_1'][$c] && !($this->handling_type_1 != '')) {
			if($i['handling_type_1'][$c])
				$this->errors['handling_type_1'] = '選択をご確認ください。';
		}elseif($u['handling_type_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->handling_type_1)){
			//$this->errors['handling_type_1'] = '全角1文字でご記入ください。';
		} elseif($u['handling_type_1'][$c] && $this->isGarbled($this->handling_type_1)) {
			$this->errors['handling_type_1'] = $d['garbled'];
		}

		if ($u['series_1'][$c] && !$this->series_1) {
			if($i['series_1'][$c])
				$this->errors['series_1'] = '記入をご確認ください。';
		}elseif($u['series_1'][$c] &&!preg_match('/^.{0,50}$/u', $this->series_1)){
			$this->errors['series_1'] = '全角25文字以内でご記入ください。';
		} elseif($u['series_1'][$c] && $this->isGarbled($this->series_1)) {
			$this->errors['series_1'] = $d['garbled'];
		}

		$this->convert($this->series_kana_1,"kh");
		if ($u['series_kana_1'][$c] && !$this->series_kana_1) {
			if($i['series_kana_1'][$c] || $this->series_1)
				$this->errors['series_kana_1'] = '記入をご確認ください。';
		}elseif($u['series_kana_1'][$c] &&!preg_match('/^([｡-ﾟ]){0,25}$/u', $this->series_kana_1)){
			$this->errors['series_kana_1'] = '半角カタカナ25文字以内でご記入ください。';
		} elseif($u['series_kana_1'][$c] && $this->isGarbled($this->series_kana_1)) {
			$this->errors['series_kana_1'] = $d['garbled'];
		}

		if ($u['series_volume_1'][$c] && !$this->series_volume_1) {
			if($i['series_volume_1'][$c])
				$this->errors['series_volume_1'] = '記入をご確認ください。';
		}elseif($u['series_volume_1'][$c] &&!preg_match('/^.{0,8}$/u', $this->series_volume_1)){
			$this->errors['series_volume_1'] = '全角4文字以内でご記入ください。';
		} elseif($u['series_volume_1'][$c] && $this->isGarbled($this->series_volume_1)) {
			$this->errors['series_volume_1'] = $d['garbled'];
		}

		if ($u['sub_series_1'][$c] && !$this->sub_series_1) {
			if($i['sub_series_1'][$c])
				$this->errors['sub_series_1'] = '記入をご確認ください。';
		}elseif($u['sub_series_1'][$c] &&!preg_match('/^.{0,50}$/u', $this->sub_series_1)){
			$this->errors['sub_series_1'] = '全角4文字以内でご記入ください。';
		} elseif($u['sub_series_1'][$c] && $this->isGarbled($this->series_volume_1)) {
			$this->errors['sub_series_1'] = $d['garbled'];
		}

		$this->convert($this->sub_series_kana_1,"kh");
		if ($u['sub_series_kana_1'][$c] && !$this->sub_series_kana_1) {
			if($i['sub_series_kana_1'][$c] || $this->sub_series_1)
				$this->errors['sub_series_kana_1'] = '記入をご確認ください。';
		}elseif($u['sub_series_kana_1'][$c] &&!preg_match('/^([｡-ﾟ]){0,25}$/u', $this->sub_series_kana_1)){
			$this->errors['sub_series_kana_1'] = '半角カタカナ25文字以内でご記入ください。';
		} elseif($u['sub_series_kana_1'][$c] && $this->isGarbled($this->sub_series_kana_1)) {
			$this->errors['sub_series_kana_1'] = $d['garbled'];
		}

		if ($u['sub_series_volume_1'][$c] && !$this->sub_series_volume_1) {
			if($i['sub_series_volume_1'][$c])
				$this->errors['sub_series_volume_1'] = '記入をご確認ください。';
		}elseif($u['sub_series_volume_1'][$c] &&!preg_match('/^.{0,8}$/u', $this->sub_series_volume_1)){
			$this->errors['sub_series_volume_1'] = '全角4文字以内でご記入ください。';
		} elseif($u['sub_series_volume_1'][$c] && $this->isGarbled($this->sub_series_volume_1)) {
			$this->errors['sub_series_volume_1'] = $d['garbled'];
		}

		$this->convert($this->total_volume_1,"n");
		if ($u['total_volume_1'][$c] && !($this->total_volume_1 != '')) {
			if($i['total_volume_1'][$c])
				$this->errors['total_volume_1'] = '記入をご確認ください。';
		}elseif($u['total_volume_1'][$c] &&!preg_match('/^[0-9]{0,3}$/u', $this->total_volume_1)){
			$this->errors['total_volume_1'] = '半角数字3桁以内でご記入ください。';
		} elseif($u['total_volume_1'][$c] && $this->isGarbled($this->total_volume_1)) {
			$this->errors['total_volume_1'] = $d['garbled'];
		}

		$this->convert($this->total_other_volume_1,"n");
		if ($u['total_other_volume_1'][$c] && !($this->total_other_volume_1 != '')) {
			if($i['total_other_volume_1'][$c])
				$this->errors['total_other_volume_1'] = '記入をご確認ください。';
		}elseif($u['total_other_volume_1'][$c] &&!preg_match('/^[0-9]{0,2}$/u', $this->total_other_volume_1)){
			$this->errors['total_other_volume_1'] = '半角数字2桁以内でご記入ください。';
		} elseif($u['total_other_volume_1'][$c] && $this->isGarbled($this->total_other_volume_1)) {
			$this->errors['total_other_volume_1'] = $d['garbled'];
		}

		$this->convert($this->distribution_count_1,"n");
		if ($u['distribution_count_1'][$c] && !($this->distribution_count_1 != '')) {
			if($i['distribution_count_1'][$c])
				$this->errors['distribution_count_1'] = '記入をご確認ください。';
		}elseif($u['distribution_count_1'][$c] &&!preg_match('/^[0-9]{0,3}$/u', $this->distribution_count_1)){
			$this->errors['distribution_count_1'] = '半角数字3桁以内でご記入ください。';
		} elseif($u['distribution_count_1'][$c] && $this->isGarbled($this->distribution_count_1)) {
			$this->errors['distribution_count_1'] = $d['garbled'];
		}

		if ($u['name_1'][$c] && !$this->name_1) {
			if($i['name_1'][$c])
				$this->errors['name_1'] = '記入をご確認ください。';
		}elseif($u['name_1'][$c] &&!preg_match('/^.{0,60}$/u', $this->name_1)){
			$this->errors['name_1'] = '全角30文字以内でご記入ください。';
		} elseif($u['name_1'][$c] && $this->isGarbled($this->name_1)) {
			$this->errors['name_1'] = $d['garbled'];
		}

		$this->convert($this->kana_1,"kh");
		if ($u['kana_1'][$c] && !$this->kana_1) {
			if($i['kana_1'][$c])
				$this->errors['kana_1'] = '記入をご確認ください。';
		}elseif($u['kana_1'][$c] &&!preg_match('/^([｡-ﾟ]){0,30}$/u', $this->kana_1)){
			$this->errors['kana_1'] = '半角カタカナ30文字以内でご記入ください。';
		} elseif($u['kana_1'][$c] && $this->isGarbled($this->kana_1)) {
			$this->errors['kana_1'] = $d['garbled'];
		}

		if ($u['volume_2'][$c] && !$this->volume_2) {
			if($i['volume_2'][$c])
				$this->errors['volume_2'] = '記入をご確認ください。';
		}elseif($u['volume_2'][$c] &&!preg_match('/^.{0,8}$/u', $this->volume_2)){
			$this->errors['volume_2'] = '全角4文字以内でご記入ください。';
		} elseif($u['volume_2'][$c] && $this->isGarbled($this->volume_2)) {
			$this->errors['volume_2'] = $d['garbled'];
		}

		if ($u['sub_1'][$c] && !$this->sub_1) {
			if($i['sub_1'][$c])
				$this->errors['sub_1'] = '記入をご確認ください。';
		}elseif($u['sub_1'][$c] &&!preg_match('/^.{0,50}$/u', $this->sub_1)){
			$this->errors['sub_1'] = '全角25文字以内でご記入ください。';
		} elseif($u['sub_1'][$c] && $this->isGarbled($this->sub_1)) {
			$this->errors['sub_1'] = $d['garbled'];
		}

		$this->convert($this->sub_kana_1,"kh");
		if ($u['sub_kana_1'][$c] && !$this->sub_kana_1) {
			if($i['sub_kana_1'][$c] || $this->sub_1)
				$this->errors['sub_kana_1'] = '記入をご確認ください。';
		}elseif($u['sub_kana_1'][$c] &&!preg_match('/^([｡-ﾟ]){0,25}$/u', $this->sub_kana_1)){
			$this->errors['sub_kana_1'] = '半角カタカナ25文字以内でご記入ください。';
		} elseif($u['sub_kana_1'][$c] && $this->isGarbled($this->sub_kana_1)) {
			$this->errors['sub_kana_1'] = $d['garbled'];
		}

		if ($u['sub_volume_1'][$c] && !$this->sub_volume_1) {
			if($i['sub_volume_1'][$c])
				$this->errors['sub_volume_1'] = '記入をご確認ください。';
		}elseif($u['sub_volume_1'][$c] &&!preg_match('/^.{0,8}$/u', $this->sub_volume_1)){
			$this->errors['sub_volume_1'] = '全角4文字以内でご記入ください。';
		} elseif($u['sub_volume_1'][$c] && $this->isGarbled($this->sub_volume_1)) {
			$this->errors['sub_volume_1'] = $d['garbled'];
		}

		if ($u['end_1'][$c] && !($this->end_1 != '')) {
			if($i['end_1'][$c])
				$this->errors['end_1'] = '選択をご確認ください。';
		}elseif($u['end_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->end_1)){
			//$this->errors['end_1'] = '全角1文字でご記入ください。';
		} elseif($u['end_1'][$c] && $this->isGarbled($this->end_1)) {
			$this->errors['end_1'] = $d['garbled'];
		}

		if ($u['present_volume_1'][$c] && !$this->present_volume_1) {
			if($i['present_volume_1'][$c])
				$this->errors['present_volume_1'] = '記入をご確認ください。';
		}elseif($u['present_volume_1'][$c] &&!preg_match('/^.{0,24}$/u', $this->present_volume_1)){
			$this->errors['present_volume_1'] = '全角12文字以内でご記入ください。';
		} elseif($u['present_volume_1'][$c] && $this->isGarbled($this->present_volume_1)) {
			$this->errors['present_volume_1'] = $d['garbled'];
		}

		if ($u['author_1'][$c] && !$this->author_1) {
			if($i['author_1'][$c])
				$this->errors['author_1'] = '記入をご確認ください。';
		}elseif($u['author_1'][$c] &&!preg_match('/^.{0,50}$/u', $this->author_1)){
			$this->errors['author_1'] = '全角25文字以内でご記入ください。';
		} elseif($u['author_1'][$c] && $this->isGarbled($this->author_1)) {
			$this->errors['author_1'] = $d['garbled'];
		}

		$this->convert($this->author_kana_1,"kh");
		if ($u['author_kana_1'][$c] && !$this->author_kana_1) {
			if($i['author_kana_1'][$c] || $this->author_1)
				$this->errors['author_kana_1'] = '記入をご確認ください。';
		}elseif($u['author_kana_1'][$c] &&!preg_match('/^([｡-ﾟ]){0,25}$/u', $this->author_kana_1)){
			$this->errors['author_kana_1'] = '半角カタカナ25文字以内でご記入ください。';
		} elseif($u['author_kana_1'][$c] && $this->isGarbled($this->author_kana_1)) {
			$this->errors['author_kana_1'] = $d['garbled'];
		}

		if ($u['author_type_1'][$c] && !$this->author_type_1) {
			if($i['author_type_1'][$c])
				$this->errors['author_type_1'] = '記入をご確認ください。';
		}elseif($u['author_type_1'][$c] &&!preg_match('/^.{0,6}$/u', $this->author_type_1)){
			$this->errors['author_type_1'] = '全角3文字以内でご記入ください。';
		} elseif($u['author_type_1'][$c] && $this->isGarbled($this->author_type_1)) {
			$this->errors['trade_code_1'] = $d['garbled'];
		}

		if ($u['author_2'][$c] && !$this->author_2) {
			if($i['author_2'][$c])
				$this->errors['author_2'] = '記入をご確認ください。';
		}elseif($u['author_2'][$c] &&!preg_match('/^.{0,50}$/u', $this->author_2)){
			$this->errors['author_2'] = '全角25文字以内でご記入ください。';
		} elseif($u['author_2'][$c] && $this->isGarbled($this->author_2)) {
			$this->errors['author_2'] = $d['garbled'];
		}

		$this->convert($this->author_kana_2,"kh");
		if ($u['author_kana_2'][$c] && !$this->author_kana_2) {
			if($i['author_kana_2'][$c] || $this->author_2)
				$this->errors['author_kana_2'] = '記入をご確認ください。';
		}elseif($u['author_kana_2'][$c] &&!preg_match('/^([｡-ﾟ]){0,25}$/u', $this->author_kana_2)){
			$this->errors['author_kana_2'] = '半角カタカナ25文字以内でご記入ください。';
		} elseif($u['author_kana_2'][$c] && $this->isGarbled($this->author_kana_2)) {
			$this->errors['author_kana_2'] = $d['garbled'];
		}

		if ($u['author_type_2'][$c] && !$this->author_type_2) {
			if($i['author_type_2'][$c] || $this->author_2)
				$this->errors['author_type_2'] = '記入をご確認ください。';
		}elseif($u['author_type_2'][$c] &&!preg_match('/^.{0,6}$/u', $this->author_type_2)){
			$this->errors['author_type_2'] = '全角3文字以内でご記入ください。';
		} elseif($u['author_type_2'][$c] && $this->isGarbled($this->author_type_2)) {
			$this->errors['author_type_2'] = $d['author_type_2'];
		}

		if ($u['author_3'][$c] && !$this->author_3) {
			if($i['author_3'][$c])
				$this->errors['author_3'] = '記入をご確認ください。';
		}elseif($u['author_3'][$c] &&!preg_match('/^.{0,50}$/u', $this->author_3)){
			$this->errors['author_3'] = '全角25文字以内でご記入ください。';
		} elseif($u['author_3'][$c] && $this->isGarbled($this->author_3)) {
			$this->errors['author_3'] = $d['garbled'];
		}

		$this->convert($this->author_kana_3,"kh");
		if ($u['author_kana_3'][$c] && !$this->author_kana_3) {
			if($i['author_kana_3'][$c] || $this->author_3)
				$this->errors['author_kana_3'] = '記入をご確認ください。';
		}elseif($u['author_kana_3'][$c] &&!preg_match('/^(([｡-ﾟ])|[\(\)\.,-=a-zA-Z0-9 ]){0,25}$/u', $this->author_kana_3)){
			$this->errors['author_kana_3'] = '半角カタカナ25文字以内でご記入ください。';
		} elseif($u['author_kana_3'][$c] && $this->isGarbled($this->author_kana_3)) {
			$this->errors['author_kana_3'] = $d['garbled'];
		}

		if ($u['author_type_3'][$c] && !$this->author_type_3) {
			if($i['author_type_3'][$c] || $this->author_3)
				$this->errors['author_type_3'] = '記入をご確認ください。';
		}elseif($u['author_type_3'][$c] &&!preg_match('/^.{0,6}$/u', $this->author_type_3)){
			$this->errors['author_type_3'] = '全角3文字以内でご記入ください。';
		} elseif($u['author_type_3'][$c] && $this->isGarbled($this->author_type_3)) {
			$this->errors['author_type_3'] = $d['garbled'];
		}

		if ($u['content_1'][$c] && !$this->content_1) {
			if($i['content_1'][$c])
				$this->errors['content_1'] = '記入をご確認ください。';
		}elseif($u['content_1'][$c] && preg_match('/([｡-ﾟ])|[\x20-\x7E]/u',$this->content_1)){
			$this->errors['content_1'] = '半角文字のご記入はできません。';
		}elseif($u['content_1'][$c] &&!preg_match('/^(.|[\r\n]){0,124}$/u', $this->content_1)){
			$this->errors['content_1'] = '全角62文字以内でご記入ください。';
		} elseif($u['content_1'][$c] && $this->isGarbled($this->content_1)) {
			$this->errors['content_1'] = $d['garbled'];
		}

		if ($u['content_2'][$c] && !$this->content_2) {
			if($i['content_2'][$c])
				$this->errors['content_2'] = '記入をご確認ください。';
		}elseif($u['content_2'][$c] && preg_match('/([｡-ﾟ])|[\x20-\x7E]/u',$this->content_2)){
			$this->errors['content_2'] = '半角文字のご記入はできません。';
		}elseif($u['content_2'][$c] &&!preg_match('/^[.\r\n]{0,76}$/u', $this->content_2)){
			$this->errors['content_2'] = '全角38文字以内でご記入ください。';
		} elseif($u['content_2'][$c] && $this->isGarbled($this->content_2)) {
			$this->errors['content_2'] = $d['garbled'];
		}

		if ($u['preliminary_5'][$c] && !($this->preliminary_5 != '')) {
			if($i['preliminary_5'][$c])
				$this->errors['preliminary_5'] = '選択をご確認ください。';
		}elseif($u['preliminary_5'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->preliminary_5)){
			//$this->errors['preliminary_5'] = '全角1文字でご記入ください。';
		} elseif($u['preliminary_5'][$c] && $this->isGarbled($this->preliminary_5)) {
			$this->errors['preliminary_5'] = $d['garbled'];
		}

		if ($u['book_size_2'][$c] && !($this->book_size_2 != '') ) {
			if($i['book_size_2'][$c]&& !($this->book_size_3_l.$this->book_size_3_r))
				$this->errors['book_size_2'] = '選択をご確認ください。';
		}elseif($u['book_size_2'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->book_size_2)){
			//$this->errors['book_size_2'] = '全角1文字でご記入ください。';
		} elseif($u['book_size_2'][$c] && $this->isGarbled($this->book_size_2)) {
			$this->errors['book_size_2'] = $d['garbled'];
		}

		$this->convert($this->book_size_3_l,"n");
		$this->convert($this->book_size_3_r,"n");
		if ($u['book_size_3'][$c] && !($this->book_size_3_l.$this->book_size_3_r) ) {
			if($i['book_size_3'][$c] && !($this->book_size_2 != '') )
				$this->errors['book_size_3'] = '記入をご確認ください。';
		}elseif($u['book_size_3'][$c] &&!preg_match('/^[0-9]+$/u', $this->book_size_3_l.$this->book_size_3_r)){
			$this->errors['book_size_3'] = '半角数字でご記入ください。';
		} elseif($u['book_size_3'][$c] && $this->isGarbled($this->book_size_3_l.$this->book_size_3_r)) {
			$this->errors['book_size_3'] = $d['garbled'];
		}

		if ($u['book_size_2'][$c] && ($this->book_size_2 != '') && ($this->book_size_3_l.$this->book_size_3_r)) {
			if($i['book_size_2'][$c])
				$this->errors['book_size_2'] = '判型（実寸）とどちらか一方にのみご記入ください。';
		}

		$this->convert($this->page_1,"n");
		if ($u['page_1'][$c] && !$this->page_1) {
			if($i['page_1'][$c])
				$this->errors['page_1'] = '記入をご確認ください。';
		}elseif($u['page_1'][$c] &&!preg_match('/^[0-9]{0,4}$/u', $this->page_1)){
			$this->errors['page_1'] = '半角数字4文字以内でご記入ください。';
		} elseif($u['page_1'][$c] && $this->isGarbled($this->page_1)) {
			$this->errors['page_1'] = $d['garbled'];
		}

		if ($u['bound_1'][$c] && !$this->bound_1) {
			if($i['bound_1'][$c])
				$this->errors['bound_1'] = '選択をご確認ください。';
		}elseif($u['bound_1'][$c] && $this->bound_1=='x' && !$this->bound_1_other){
			$this->errors['bound_1'] = '「その他」のご記入をご確認ください。';
		}elseif($u['bound_1'][$c] && $this->bound_1=='x' && !preg_match('/^.{0,12}$/u', $this->bound_1_other)){
			$this->errors['bound_1'] = '全角6文字以内でご記入ください。';
		} elseif($u['bound_1'][$c] && $this->bound_1=='x' && $this->isGarbled($this->bound_1_other)) {
			$this->errors['bound_1'] = $d['garbled'];
		}

		$this->convert($this->release_date_1_year,"N");
		$this->convert($this->release_date_1_month,"N");
		$this->convert($this->release_date_1_day,"N");
		if ($u['release_date_1'][$c] && !($this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day)) {
			if($i['release_date_1'][$c])
				$this->errors['release_date_1'] = '記入をご確認ください。';
		}elseif($u['release_date_1'][$c] &&!mb_ereg_match('^(([０-９]{8})|([０-９]{6}(上旬|中旬|下旬)))$', ($this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day))){
			$this->errors['release_date_1'] = '記入形式をご確認ください。';
		}elseif($u['release_date_1'][$c] && !($this->isFormatedDate(mb_convert_kana($this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day,'n')) || $this->isFormatedDate(mb_convert_kana($this->release_date_1_year.$this->release_date_1_month.'01','n'))) ){
			$this->errors['release_date_1'] = '記入内容をご確認ください。';
		} elseif($u['release_date_1'][$c] && $this->isGarbled($this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day)) {
			$this->errors['release_date_1'] = $d['garbled'];
		}

		$this->convert($this->return_date_1_year,"n");
		$this->convert($this->return_date_1_month,"n");
		$this->convert($this->return_date_1_day,"n");
		if ($u['return_date_1'][$c] && !($this->return_date_1_year.$this->return_date_1_month.$this->return_date_1_day)) {
			if($i['return_date_1'][$c])
				$this->errors['return_date_1'] = '記入をご確認ください。';
		}elseif($u['return_date_1'][$c] &&!preg_match('/^[0-9]{8}$/u', ($this->return_date_1_year.$this->return_date_1_month.$this->return_date_1_day))){
			$this->errors['return_date_1'] = '記入形式をご確認ください。';
		}elseif($u['return_date_1'][$c] &&!$this->isFormatedDate($this->return_date_1_year.$this->return_date_1_month.$this->return_date_1_day)){
			$this->errors['return_date_1'] = '記入内容をご確認ください。';
		} elseif($u['return_date_1'][$c] && $this->isGarbled($this->return_date_1_year.$this->return_date_1_month.$this->return_date_1_day)) {
			$this->errors['return_date_1'] = $d['garbled'];
		}

		if ($u['notation_price_1'][$c] && !($this->notation_price_1 != '')) {
			if($i['notation_price_1'][$c])
				$this->errors['notation_price_1'] = '選択をご確認ください。';
		}elseif($u['notation_price_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->notation_price_1)){
			//$this->errors['notation_price_1'] = '全角1文字でご記入ください。';
		} elseif($u['notation_price_1'][$c] && $this->isGarbled($this->notation_price_1)) {
			$this->errors['notation_price_1'] = $d['garbled'];
		}

		$this->convert($this->price_1,"n");
		if ($u['price_1'][$c] && !$this->price_1) {
			if($i['price_1'][$c])
				$this->errors['price_1'] = '記入をご確認ください。';
		}elseif($u['price_1'][$c] &&!preg_match('/^[0-9]{0,7}$/', $this->price_1)){
			$this->errors['price_1'] = '半角数字7文字以内でご記入ください。';
		} elseif($u['price_1'][$c] && $this->isGarbled($this->price_1)) {
			$this->errors['price_1'] = $d['garbled'];
		}

		$this->convert($this->price_tax_1,"n");
		if ($u['price_tax_1'][$c] && !$this->price_tax_1) {
			if($i['price_tax_1'][$c])
				$this->errors['price_tax_1'] = '記入をご確認ください。';
		}elseif($u['price_tax_1'][$c] &&!preg_match('/^[0-9]{0,7}$/u', $this->price_tax_1)){
			$this->errors['price_tax_1'] = '半角数字7文字以内でご記入ください。';
		}elseif($u['price_tax_1'][$c] && $this->price_tax_1 != floor($this->price_1 * 1.08 + 0.5)){
			$this->errors['price_tax_1'] = '定価と本体価格の価格差に誤りがあります。ご確認ください。';
		} elseif($u['price_tax_1'][$c] && $this->isGarbled($this->price_tax_1)) {
			$this->errors['price_tax_1'] = $d['garbled'];
		}

		$this->convert($this->price_special_1,"n");
		if ($u['price_special_1'][$c] && !$this->price_special_1) {
			if($i['price_special_1'][$c])
				$this->errors['price_special_1'] = '記入をご確認ください。';
		}elseif($u['price_special_1'][$c] &&!preg_match('/^[0-9]{0,7}$/u', $this->price_special_1)){
			$this->errors['price_special_1'] = '半角数字7文字以内でご記入ください。';
		} elseif($u['price_special_1'][$c] && $this->isGarbled($this->price_special_1)) {
			$this->errors['trade_code_1'] = $d['garbled'];
		}

		$this->convert($this->price_special_tax_1,"n");
		if ($u['price_special_tax_1'][$c] && !$this->price_special_tax_1) {
			if($i['price_special_tax_1'][$c])
				$this->errors['price_special_tax_1'] = '記入をご確認ください。';
		}elseif($u['price_special_tax_1'][$c] &&!preg_match('/^[0-9]{0,7}$/u', $this->price_special_tax_1)){
			$this->errors['price_special_tax_1'] = '半角数字7文字以内でご記入ください。';
		}elseif($u['price_special_tax_1'][$c] && $this->price_special_tax_1 != floor($this->price_special_1 * 1.08 + 0.5)){
			$this->errors['price_special_tax_1'] = '定価と本体価格の価格差に誤りがあります。ご確認ください。';
		} elseif($u['price_special_tax_1'][$c] && $this->isGarbled($this->price_special_tax_1)) {
			$this->errors['price_special_tax_1'] = $d['garbled'];
		}

		$this->convert($this->price_special_policy_1_year,"n");
		$this->convert($this->price_special_policy_1_month,"n");
		$this->convert($this->price_special_policy_1_day,"n");
		if ($u['price_special_policy_1'][$c] && !($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day)) {
			if($i['price_special_policy_1'][$c])
				$this->errors['price_special_policy_1'] = '記入をご確認ください。';
		}elseif($u['price_special_policy_1'][$c] &&!preg_match('/^[0-9]{8}$/u', ($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day))){
			$this->errors['price_special_policy_1'] = '記入形式をご確認ください。';
		}elseif($u['price_special_policy_1'][$c] &&!$this->isFormatedDate($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day)){
			$this->errors['price_special_policy_1'] = '記入内容をご確認ください。';
		} elseif($u['price_special_policy_1'][$c] && $this->isGarbled($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day)) {
			$this->errors['price_special_policy_1'] = $d['garbled'];
		}

		if ($u['distribution_type_1'][$c] && !($this->distribution_type_1 != '')) {
			if($i['distribution_type_1'][$c])
				$this->errors['distribution_type_1'] = '選択をご確認ください。';
		}elseif($u['distribution_type_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->distribution_type_1)){
			//$this->errors['distribution_type_1'] = '全角1文字でご記入ください。';
		} elseif($u['distribution_type_1'][$c] && $this->isGarbled($this->distribution_type_1)) {
			$this->errors['distribution_type_1'] = $d['garbled'];
		}

		if ($u['distribut_1'][$c] && !$this->distribut_1) {
			if($i['distribut_1'][$c])
				$this->errors['distribut_1'] = '選択をご確認ください。';
		}elseif($u['distribut_1'][$c] && $this->distribut_1=='x' &&!preg_match('/^.{0,12}$/u', $this->distribut_1_other)){
			$this->errors['distribut_1'] = '全角6文字以内でご記入ください。';
		} elseif($u['distribut_1'][$c] && $this->isGarbled($this->distribut_1)) {
			$this->errors['distribut_1'] = $d['garbled'];
		}

		$this->convert($this->isbn_1,"rn");
		if ($u['isbn_1'][$c] && !$this->isbn_1) {
			if($i['isbn_1'][$c])
				$this->errors['isbn_1'] = '記入をご確認ください。';
		}elseif($u['isbn_1'][$c] &&!preg_match('/^([0-9]{12}[0-9X]{1})$/ui', $this->isbn_1)){
			$this->errors['isbn_1'] = '半角13文字でご記入ください。';
		} elseif($u['isbn_1'][$c] && $this->isGarbled($this->isbn_1)) {
			$this->errors['isbn_1'] = $d['garbled'];
		}

		$this->convert($this->category_1,"n");
		if ($u['category_1'][$c] && !$this->category_1) {
			if($i['category_1'][$c])
				$this->errors['category_1'] = '記入をご確認ください。';
		}elseif($u['category_1'][$c] &&!preg_match('/^[0-9]{4}$/u', $this->category_1)){
			$this->errors['category_1'] = '半角数字4桁でご記入ください。';
		} elseif($u['category_1'][$c] && $this->isGarbled($this->category_1)) {
			$this->errors['category_1'] = $d['garbled'];
		}

		if ($u['magazine_code_2'][$c] && !$this->magazine_code_2) {
			if($i['magazine_code_2'][$c])
				$this->errors['magazine_code_2'] = '記入をご確認ください。';
		}elseif($u['magazine_code_2'][$c] &&!preg_match('/^[0-9a-zA-Z-\/]{0,15}$/u', $this->magazine_code_2)){
			//$this->errors['magazine_code_2'] = '半角15文字以内でご記入ください。';
		} elseif($u['magazine_code_2'][$c] && $this->isGarbled($this->magazine_code_2)) {
			$this->errors['magazine_code_2'] = $d['garbled'];
		}

		$this->convert($this->magazine_code_1_1_1,"n");
		$this->convert($this->magazine_code_1_1_2,"n");
		$this->convert($this->magazine_code_1_2_1,"n");
		$this->convert($this->magazine_code_1_2_2,"n");
		$this->convert($this->magazine_code_1_2_3,"n");
		$this->convert($this->typist_tel_1,"n");
		if ($u['magazine_code_1'][$c] && !(($this->magazine_code_1_1_1.$this->magazine_code_1_1_2)||($this->magazine_code_1_2_1.$this->magazine_code_1_2_2.$this->magazine_code_1_2_3)) ) {
			if($i['magazine_code_1'][$c])
				$this->errors['magazine_code_1'] = '記入をご確認ください。';
		}elseif($u['magazine_code_1'][$c]&& (($this->magazine_code_1_1_1.$this->magazine_code_1_1_2)&&($this->magazine_code_1_2_1.$this->magazine_code_1_2_2.$this->magazine_code_1_2_3)) ){
			$this->errors['magazine_code_1'] = '週刊誌以外、週刊誌のどちらか一方にのみご入力ください。';
		}elseif($u['magazine_code_1'][$c]
			&& (!preg_match('/^[0-9a-zA-Z]{5}-[0-9a-zA-Z]{2}$/u', $this->magazine_code_1_1_1.'-'.$this->magazine_code_1_1_2)
			&&!preg_match('/^[0-9a-zA-Z]{5}-[0-9a-zA-Z]{2}\/[0-9a-zA-Z]{2}$/u', $this->magazine_code_1_2_1.'-'.$this->magazine_code_1_2_2.'/'.$this->magazine_code_1_2_3))
		){
			$this->errors['magazine_code_1'] = '記入形式をご確認ください。';
		} elseif($u['magazine_code_1'][$c] && $this->isGarbled($this->magazine_code_1_1_1.$this->magazine_code_1_1_2.$this->magazine_code_1_2_1.$this->magazine_code_1_2_2.$this->magazine_code_1_2_3)) {
			$this->errors['magazine_code_1'] = $d['garbled'];
		}

		if ($u['adult_1'][$c] && !($this->adult_1 != '')) {
			if($i['adult_1'][$c])
				$this->errors['adult_1'] = '記入をご確認ください。';
		}elseif($u['adult_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->adult_1)){
			//$this->errors['adult_1'] = '全角1文字でご記入ください。';
		} elseif($u['adult_1'][$c] && $this->isGarbled($this->adult_1)) {
			$this->errors['adult_1'] = $d['garbled'];
		}

		if ($u['pre_order_1'][$c] && !( $this->pre_order_1!= '')) {
			if($i['pre_order_1'][$c])
				$this->errors['pre_order_1'] = '記入をご確認ください。';
		}elseif($u['pre_order_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->pre_order_1)){
			//$this->errors['pre_order_1'] = '全角1文字でご記入ください。';
		} elseif($u['pre_order_1'][$c] && $this->isGarbled($this->pre_order_1)) {
			$this->errors['pre_order_1'] = $d['garbled'];
		}

		$this->convert($this->order_status_1_year,"n");
		$this->convert($this->order_status_1_month,"n");
		$this->convert($this->order_status_1_day,"n");
		if ($u['order_status_1'][$c] && !($this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day)) {
			if($this->pre_order_1== 1)
				$this->errors['order_status_1'] = '記入をご確認ください。';
		}elseif($u['order_status_1'][$c] &&!preg_match('/^[0-9]{8}$/u', ($this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day))){
			$this->errors['order_status_1'] = '記入形式をご確認ください。';
		}elseif($u['order_status_1'][$c] &&!$this->isFormatedDate($this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day)){
			$this->errors['order_status_1'] = '記入内容をご確認ください。';
		} elseif($u['order_status_1'][$c] && $this->isGarbled($this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day)) {
			$this->errors['order_status_1'] = $d['garbled'];
		}

		$this->convert($this->circulation_1,"n");
		if ($u['circulation_1'][$c] && !$this->circulation_1) {
			if($i['circulation_1'][$c])
				$this->errors['circulation_1'] = '記入をご確認ください。';
		}elseif($u['circulation_1'][$c] &&!preg_match('/^[0-9]{0,7}$/u', $this->circulation_1)){
			$this->errors['circulation_1'] = '半角数字7桁以内でご記入ください。';
		} elseif($u['circulation_1'][$c] && $this->isGarbled($this->circulation_1)) {
			$this->errors['circulation_1'] = $d['garbled'];
		}

		if ($u['fix_1'][$c] && !$this->fix_1) {
			if($i['fix_1'][$c])
				$this->errors['fix_1'] = '記入をご確認ください。';
		}elseif($u['fix_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->fix_1)){
			//$this->errors['fix_1'] = '全角1文字でご記入ください。';
		} elseif($u['fix_1'][$c] && $this->isGarbled($this->fix_1)) {
			$this->errors['fix_1'] = $d['garbled'];
		}

		if ($u['typist_1'][$c] && !$this->typist_1) {
			if($i['typist_1'][$c])
				$this->errors['typist_1'] = '記入をご確認ください。';
		}elseif($u['typist_1'][$c] &&!preg_match('/^.{0,10}$/u', $this->typist_1)){
			$this->errors['typist_1'] = '全角5文字以内でご記入ください。';
		} elseif($u['typist_1'][$c] && $this->isGarbled($this->typist_1)) {
			$this->errors['typist_1'] = $d['garbled'];
		}

		$this->convert($this->typist_tel_1,"n");
		if ($u['typist_tel_1'][$c] && !$this->typist_tel_1) {
			if($i['typist_tel_1'][$c])
				$this->errors['typist_tel_1'] = '記入をご確認ください。';
		}elseif($u['typist_tel_1'][$c] &&!preg_match('/^[0-9]{0,11}$/u', $this->typist_tel_1)){
			$this->errors['typist_tel_1'] = '記入形式をご確認ください。';
		} elseif($u['typist_tel_1'][$c] && $this->isGarbled($this->typist_tel_1)) {
			$this->errors['typist_tel_1'] = $d['garbled'];
		}

		$this->convert($this->type_date_1_year,"n");
		$this->convert($this->type_date_1_month,"n");
		$this->convert($this->type_date_1_day,"n");
		if ($u['type_date_1'][$c] && !($this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day)) {
			if($i['type_date_1'][$c])
				$this->errors['type_date_1'] = '記入をご確認ください。';
		}elseif($u['type_date_1'][$c] &&!preg_match('/^[0-9]{8}$/u', ($this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day))){
			$this->errors['type_date_1'] = '記入形式をご確認ください。';
		}elseif($u['type_date_1'][$c] &&!$this->isFormatedDate($this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day)){
			$this->errors['type_date_1'] = '記入内容をご確認ください。';
		} elseif($u['type_date_1'][$c] && $this->isGarbled($this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day)) {
			$this->errors['type_date_1'] = $d['garbled'];
		}

		if ($u['edit_time_stamp_1'][$c] && !$this->edit_time_stamp_1) {
			if($i['edit_time_stamp_1'][$c])
				$this->errors['edit_time_stamp_1'] = '記入をご確認ください。';
		}elseif($u['edit_time_stamp_1'][$c] &&!preg_match('/^[0-9a-zA-Z]{4}$/u', $this->edit_time_stamp_1)){
			//$this->errors['edit_time_stamp_1'] = '全角1文字でご記入ください。';
		} elseif($u['edit_time_stamp_1'][$c] && $this->isGarbled($this->edit_time_stamp_1)) {
			$this->errors['edit_time_stamp_1'] = $d['garbled'];
		}

		$this->convert($this->edit_time_stamp_1_hour,"n");
		$this->convert($this->edit_time_stamp_1_min,"n");
		$this->convert($this->edit_time_stamp_1_sec,"n");
		if ($u['edit_time_stamp_1'][$c] && !($this->edit_time_stamp_1_hour.$this->edit_time_stamp_1_min.$this->edit_time_stamp_1_sec)) {
			if($i['edit_time_stamp_1'][$c])
				$this->errors['edit_time_stamp_1'] = '記入をご確認ください。';
		}elseif($u['edit_time_stamp_1'][$c] && (!preg_match('/^[0-9]{2}$/u',$this->edit_time_stamp_1_hour) || !preg_match('/^[0-9]{2}$/',$this->edit_time_stamp_1_min) || !preg_match('/^[0-9]{2}$/',$this->edit_time_stamp_1_sec))){
			$this->errors['edit_time_stamp_1'] = '記入形式をご確認ください。';
		} elseif($u['edit_time_stamp_1'][$c] && $this->isGarbled($this->edit_time_stamp_1_hour.$this->edit_time_stamp_1_min.$this->edit_time_stamp_1_sec)) {
			$this->errors['edit_time_stamp_1'] = $d['garbled'];
		}

		if ($u['win_info_1'][$c] && !$this->win_info_1) {
			if($i['win_info_1'][$c])
				$this->errors['win_info_1'] = '記入をご確認ください。';
		}elseif($u['win_info_1'][$c] &&!preg_match('/^(.|[\r\n]){0,60}$/u', $this->win_info_1)){
			$this->errors['win_info_1'] = '全角30文字以内でご記入ください。';
		} elseif($u['win_info_1'][$c] && $this->isGarbled($this->win_info_1)) {
			$this->errors['win_info_1'] = $d['garbled'];
		}

		if(count($this->errors)) {
			return 'input';
		}

		$this->uFields = $u;
		$this->iFields = $i;
		$this->cType = $c;

		if($this->book_size_3_l.$this->book_size_3_r != '')
			$this->book_size_3 = $this->book_size_3_l . 'x' .$this->book_size_3_r;

		if($this->magazine_code_1_1_1.$this->magazine_code_1_1_2 != '')
			$this->magazine_code_1 = $this->magazine_code_1_1_1 . '-' .$this->magazine_code_1_1_2;
		else
			$this->magazine_code_1 = $this->magazine_code_1_2_1 . '-' .$this->magazine_code_1_2_2 . '/' .$this->magazine_code_1_2_3;

		if($this->bound_1 == 'x')
			$this->bound_1 = $this->bound_1_other;

		$this->release_date_1 = $this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day;

		$this->return_date_1 = $this->return_date_1_year.$this->return_date_1_month.$this->return_date_1_day;

		$this->price_special_policy_1 = $this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day;

		if($this->distribut_1 == 'x')
			$this->distribut_1 = $this->distribut_1_other;

		$this->release_date_1 = $this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day;

		$this->order_status_1 = $this->order_status_1_year.$this->order_status_1_month.$this->order_status_1_day;

		$this->type_date_1 = $this->type_date_1_year.$this->type_date_1_month.$this->type_date_1_day;

		$this->edit_time_stamp_1 = $this->edit_time_stamp_1_hour.$this->edit_time_stamp_1_min.$this->edit_time_stamp_1_sec;


		return true;
	}

	function getUsingFieldList(){
		return array(
			"cancell_type_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"category_code_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"trade_code_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"trade_code_branch_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"issuer_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"issuer_kana_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"publisher_2" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"publisher_kana_2" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"handling_company_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"handling_type_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"series_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true),
			"series_kana_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true),
			"series_volume_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true),
			"sub_series_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"sub_series_kana_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"sub_series_volume_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"total_volume_1" => array("30" => true,"29" => true,"36" => true,"31" => true),
			"total_other_volume_1" => array("30" => true,"29" => true),
			"distribution_count_1" => array("30" => true),
			"name_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"kana_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"volume_2" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"sub_1" => array("51" => true,"81" => true,"86" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"sub_kana_1" => array("51" => true,"81" => true,"86" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"sub_volume_1" => array("51" => true,"81" => true,"86" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"end_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"30" => true),
			"present_volume_1" => array("81" => true,"86" => true,"71" => true),
			"author_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_kana_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_type_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_2" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_kana_2" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_type_2" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_3" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_kana_3" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_type_3" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"content_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"preliminary_5" => array("23" => true),
			"book_size_2" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"book_size_3" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"page_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"bound_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"release_date_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"return_date_1" => array("51" => true,"81" => true,"86" => true,"71" => true),
			"notation_price_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"price_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"price_tax_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"price_special_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"price_special_tax_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"price_special_policy_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"distribution_type_1" => array("30" => true,"29" => true),
			"distribut_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"isbn_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"category_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"magazine_code_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true),
			"adult_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true),
			'pre_order_1' => array("44" => true,"42" => true,"43" => true),
			'order_status_1' => array("44" => true,"42" => true,"43" => true),
			"circulation_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"typist_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"typist_tel_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"type_date_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"edit_time_stamp_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"win_info_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true)
		);
	}
	function getImportantFieldList(){
		return array(
			"cancell_type_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"category_code_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"trade_code_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"issuer_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"issuer_kana_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"handling_type_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"series_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"2" => true),
			"series_kana_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"2" => true),
			"sub_series_1" => array("30" => true),
			"sub_series_kana_1" => array("30" => true),
			"total_volume_1" => array("30" => true , "29" => true),
			"total_other_volume_1" => array("30" => true , "29" => true),
			"distribution_count_1" => array("30" => true),
			"name_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"kana_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"sub_1" => array("81" => true,"86" => true),
			"sub_kana_1" => array("81" => true,"86" => true),
			"present_volume_1" => array("71" => true),
			"author_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_kana_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"author_type_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"content_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"preliminary_5" => array("23" => true),
			"book_size_2" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"book_size_3" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"bound_1" => array("51" => true,"81" => true,"86" => true,"71" => true),
			"release_date_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"price_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"price_tax_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"distribution_type_1" => array("30" => true,"29" => true),
			"distribut_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"isbn_1" => array("51" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"category_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true),
			"magazine_code_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true),
			"adult_1" => array("44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true),
			'pre_order_1' => array(),
			"typist_1" => array("51" => true,"81" => true,"86" => true,"71" => true,"44" => true,"42" => true,"41" => true,"43" => true,"36" => true,"31" => true,"2" => true,"30" => true,"23" => true,"1" => true,"3" => true,"4" => true,"5" => true,"6" => true,"7" => true,"9" => true,"10" => true,"11" => true,"12" => true,"13" => true,"14" => true,"15" => true,"16" => true,"17" => true,"18" => true,"19" => true,"20" => true,"21" => true,"22" => true,"24" => true,"29" => true)
		);
	}
}
?>