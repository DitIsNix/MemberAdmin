<?php
//class.Member.php

class Member
{
	public $properties;
	public $error;
		
	public function __construct($memberIdOrArray, $table = null) {
		if (ctype_digit($memberIdOrArray)) {
			$conn = new Communicator();
			$stmt = $conn->runQuery("SELECT ID, first_name, last_name, address, postal_code,
					city, email, birth, gender, phone, mobile, number, last_updated FROM ".$table." WHERE ID=:id");
			$stmt->execute(array(':id'=>$memberIdOrArray));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($row) {
				$this->properties = $row;
				if (strtotime($this->properties['birth']) === false) {
					$this->properties['birth'] = '';
				} else {
					$date = new DateTime($this->properties['birth']);
					$this->properties['birth'] = $date->format('d-m-Y');
				}
			} else {
				$this->properties = self::template;
			}
		} elseif (is_array($memberIdOrArray)) {
			$this->properties = $memberIdOrArray;
		} else {
			$this->properties = self::template;
		}
	}
	
	public function getMember() {
		return $this->properties;
	}
	
	public static function getInclude($table) {
		if($table == 'members') {
			$include = array('first_name', 'last_name', 'address', 'postal_code',
					'city', 'email', 'birth', 'gender', 'phone', 'mobile', 'number');
		}elseif($table == 'exmembers') {
			$include = array('first_name', 'last_name', 'address', 'postal_code',
					'city', 'email', 'birth', 'gender', 'phone', 'mobile', 'number');
		}elseif($table == 'squash') {
			$include = array('first_name', 'last_name', 'email', 'phone', 'mobile');
		}elseif($table == 'friday') {
			$include = array('first_name', 'last_name', 'address', 'postal_code',
					'city', 'email', 'phone', 'mobile');
		}
		return $include;
	}
	
	public function checkInput() {
		$this->properties = array_merge($this->properties, $_POST);
		$this->properties['postal_code'] = strtoupper($this->properties['postal_code']);
		$this->properties['gender'] = strtoupper($this->properties['gender']);
		if($this->properties['first_name'] == '') {
			$this->error = Constants::valErrorEmptyFirstName;
		}elseif($this->properties['last_name'] == '') {
			$this->error = Constants::valErrorEmptyLastName;
		} else {
			foreach($this->properties as $property => $value) {
				if(empty($this->error)) {
					if(in_array($property, self::getInclude($_GET['table']), true)) {
						$handler = new InputHandler($property, $value);
						$this->error = $handler->checkData();
					}
				}
			}
		}
	}
	
	public function getMemberToRemove() {
		$table = '<form action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '" method="post">
						<table class="form">';
		$include = self::getInclude($_GET['table']);
		foreach($this->properties as $key => $value) {
			if(in_array($key, $include, true)) {
				foreach(Constants::memberProperties as $cKey => $cValue) {
					if($key == $cKey) {
						$key = $cValue;
					}
				}
				$table .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
			}
		}
		$table .= '<tr><td></td><td><button type="submit" name="remove" value="true">'.
									Constants::submitRemoveMember.'</button></td></tr>';
		$table .= '</table></form>';
		return $table;
	}
	
	public function getInsertQuery($table) {
		$query = 'INSERT INTO '.$table.' (';
		foreach($this->properties as $k=>$v) {
			if($k != 'submit')
				$query .= $k.', ';
		}
		$query = rtrim($query, ', ');
		$query .= ') VALUES (';
		foreach($this->properties as $k=>$v) {
			if($k != 'submit')
				$query .= ':'.$k.', ';
		}
		$query = rtrim($query, ', ');
		$query .= ')';
		return $query;
	}
		
	public function getUpdateQuery() {
		$query = 'UPDATE '.$_GET['table'].' SET ';
		foreach($this->properties as $k=>$v) {
			if($k != 'submit')
				$query .= $k.' = :'.$k.', ';
		}
		$query = rtrim($query, ', ');
		$query .= ' WHERE ID = :ID';
		return $query;
	}
	
	public function getDeleteQuery() {
		$query = 'UPDATE '.$_GET['table'].' SET ';
		foreach($this->properties as $k=>$v) {
			if($k != 'submit')
				$query .= $k.' = :'.$k.', ';
		}
		$query = rtrim($query, ', ');
		$query .= ' WHERE ID = :ID';
		return $query;
	}
	
	public function getFirstName($id, $msg) {
		$conn = new Communicator();
		$stmt = $conn->runQuery('SELECT first_name FROM '.
				$_GET['table'].' WHERE ID LIKE :ID');
		$stmt->bindparam(":ID", $id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$result = $msg.$row['first_name'].'.<br>';
		return $result;
	}
	
	public function getMemberDataFromId($server, $db_name, $user, $pass) {	//up for destruction
		$comm = new Communicator($server, $db_name, $user, $pass);
		$query = QueryController::searchMemberWithId($this->id);
		$data = $comm->getData($query);
		$this->memberData = $data->fetch(PDO::FETCH_ASSOC);
	}
	
	public function putPostIntoMemberArray() { //pKey being POST key, aKey being ARRAY key
		foreach($_POST as $pKey=>$pValue) {						//up for destruction
			if($pKey == "postcode" || $pKey == "sexe"|| $pKey == "h_pas") {
				$pValue = strtoupper($pValue);
			}
			foreach($this->memberArray as $aKey=>$aValue) {
				if ($aValue[1] === $pKey) {
					$this->memberArray[$aKey][2] = $pValue;
				}
			}
		}
	}
	
	public function putMemberDataIntoMemberArray() { // dKey being the DATA key, aKey being the ARRAY key
		foreach($this->memberData as $dKey=>$dValue) {			//up for destruction
			//			$dKey = $dKey;
			foreach($this->memberArray as $aKey=>$aValue) {
				if ($aValue[0] === $dKey) {
					$this->memberArray[$aKey][2] = $dValue;
				}
			}
		}
	}
	
	public function putMemberDataIntoList() {			//up for destruction
		$content = "";
		foreach($this->memberData as $key=>$value) {
			if ($key != "mID") {
				$content .= $key.": ".$value."<BR>";
			}
		}
		return $content;
	}
	
	public function validateMember() {			//up for destruction
		$count = count($this->memberArray);
		for ($row = 0; $row < $count; $row++) {
			if($this->memberArray[$row][2]) {
				$value = $this->memberArray[$row][2];
				$filter = $this->memberArray[$row][5];
				$check = new ErrorHandler($value, $filter);
				$array = $check->getResults();
				if (empty($array["validated"])) {
					$this->error = FALSE;
					$error = $array["error"];
					$this->memberArray[$row][3] = $error;
				} else {
					$this->memberArray[$row][2] = $array["validated"];
				}
			} else {
				$this->memberArray[$row][3] = Constants::emptyInput;
			}
		}
		$firstnameValue = $this->memberArray[0][2];
		$lastnameValue = $this->memberArray[1][2];
		// if (!$this->memberArray[0][2] || $this->memberArray[1][2]) {
		if (($firstnameValue == "") || ($lastnameValue == "")) {
			$this->error = FALSE;
		}
	}
	
	const template = array(	'first_name' => '',
							'last_name' =>	'',
							'address' =>	'',
							'postal_code' =>'',
							'city' =>		'',
							'email' =>		'',
							'birth' =>		'',
							'gender' =>		'',
							'phone' =>		'',
							'mobile' =>		'',
							'number' =>		'',
							'pay' =>		'',
							'last_updated'=>'');
	
}
