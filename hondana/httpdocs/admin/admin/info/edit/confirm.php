<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');

class Action extends AuthAction {
	function validate() {
		$publicDateString = $this->public_year.$this->public_month.$this->public_day.
			$this->public_hour.$this->public_minute.$this->public_second;

		$this->public_date = $this->public_year.'-'.$this->public_month.'-'.$this->public_day.' '.
			$this->public_hour.':'.$this->public_minute.':'.$this->public_second;

		$this->errors = array();

		if (!$this->name) {
			$this->errors['name'] = 'タイトルの記入をご確認ください。';
		}
		if (!$this->value) {
			$this->errors['value'] = '内容の記入をご確認ください。';
		}
		/*if (!$this->public_status) {
			$this->errors['public_status'] = '公開の状態の選択をご確認ください。';
		}*/
		if ($publicDateString != ''){
			if(!$this->isDateTime($this->public_date)) {
				$this->errors['public_date'] = '公開日時の記入が正しくありません。ご確認ください。';
			}elseif(!$this->isDate($this->public_year , $this->public_month , $this->public_day)){
				$this->errors['public_date'] = '公開日付の記入が正しくありません。ご確認ください。';
			}
		}


		if(count($this->errors)) {
			return 'input';
		}else if($publicDateString == ''){
			$this->public_date ='0000-00-00 00:00:00';
		}


		return true;
	}
}
?>