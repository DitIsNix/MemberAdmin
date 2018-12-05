<?php
//class.InputHandler.php

class InputHandler
{
	public $property;
	public $value;
	
	public function __construct($property, $value) {
		$this->property = $property;
		$this->value = $value;
	}
	
	public function checkData() {
		$error = '';
		if(!empty($this->value)) {
			switch($this->property) {
				case 'email':
					$data = filter_var(trim($this->value), FILTER_SANITIZE_EMAIL);
					if(!filter_var($data, FILTER_VALIDATE_EMAIL)) {
						$error = Constants::valErrorEmail;
					}
					break;
				case 'birth':
					if (preg_match("/^[0-9]{2}\-[0-9]{2}\-[0-9]{4}$/", $this->value)) {
						$birthDay   = substr($this->value, 0, 2);
						$birthMonth = substr($this->value, 3, 2);
						$birthYear  = substr($this->value, -4, 4);
						if(!checkdate($birthMonth, $birthDay, $birthYear)) {
							$error = Constants::valErrorBirth;
						}
					} else {
						$error = Constants::valErrorBirthFormat;
					}
					break;
				default:
					switch($this->property) {
						case 'first_name':
						case 'last_name':
						case 'city':
							$regExp = "/^[a-z ,.'-יכüצךור]+$/i";
							break;
						case 'address':
							$regExp = "/^[a-z0-9 ,.'-יכüצךור]+$/i";
							break;
						case 'postal_code':
							$regExp = "/^[0-9]{4}[A-Z]{2}$/";
							break;
						case 'phone':
						case 'mobile':
							$regExp = "/^[0-9]{10}$/";
							break;
						case 'number':
							$regExp = "/^[0-9]{7}$/";
							break;
						case 'gender':
							$regExp = "/^[MV]{1}$/";
							break;
						case 'search':
							$regExp = "/^[a-z ,[\].'_%-]+$/i";
							break;
					}
					if(isset($regExp)) {
						$filter = array("options"=>array("regexp"=>$regExp));
						$data = filter_var(trim($this->value), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
						if(!filter_var($data, FILTER_VALIDATE_REGEXP, $filter)) {
							switch ($this->property) {
								case 'first_name':
									$error = Constants::valErrorFirstName;
									break;
								case 'last_name':
									$error = Constants::valErrorLastName;
									break;
								case 'city':
									$error = Constants::valErrorCity;
									break;
								case 'address':
									$error = Constants::valErrorAddress;
									break;
								case 'postal_code':
									$error = Constants::valErrorPostalCode;
									break;
								case 'phone':
								case 'mobile':
									$error = Constants::valErrorPhone;
									break;
								case 'number':
									$error = Constants::valErrorNumber;
									break;
								case 'gender':
									$error = Constants::valErrorGender;
									break;
								case 'search':
									$error = Constants::valErrorSearch;
									break;
							}
						}
					}
			}
		}
		return $error;
	}
}