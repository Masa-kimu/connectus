<?php

class validation{
	private $request;

	public function __construct(){
		$this->request = $_POST;
	}

	public function check_empty($checks):?Array {
		$error = [];

		foreach($checks as $check){
			if(empty($this->request[$check])){
				$error[$check] = '入力してください。';
			}
		}
		return $error;
	}

	public function check_email():?String {
		$error = '';

		if(!empty($this->request['email']) && !filter_var($this->request['email'], FILTER_VALIDATE_EMAIL)){
			$error = 'メールアドレスを入力してください。';
		}

		return $error;
	}

	public function check_len($checks):?Array {
		$error = [];

		foreach($checks as $check){
			if(!empty($this->request[$check]) && mb_strlen($this->request[$check]) > 20){
				$error[$check] = '20文字以内で入力してください。';
			}
		}

		return $error;
	}
}

?>