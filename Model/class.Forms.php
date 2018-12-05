<?php
//class.Forms.php

class Forms
{
	public $properties;
	
	public function __construct($array) {
		$this->properties = $array;
	}
	
	public static function getSearchForm() {
		if(isset($_POST['search'])) { $value = $_POST['search'];} else { $value = '';}
		$form = "<p><div class='search_form'><form action='".$_SERVER['PHP_SELF']."' method='post'>".
				"<input type='text' size='50' name='search' value='".$value."'> ".
				"<button type='submit' name='submit'>".Constants::search."</button>".
				"</form></div></p>";
		return $form;
	}

	public static function getLogInForm($number, $error, $loginORsignup) {
		if($loginORsignup == 'login') {
			$legend = Constants::logInLegend;
			$user = Constants::logInUser;
			$pass = '<tr><td>'.Constants::logInPass.'<td><input type="password" size="25" name="password">';
			$go = Constants::logInGo;
			$question = Constants::qstnNotRegistered.
						' <a href="'.htmlspecialchars($_SERVER['PHP_SELF']).'?action=signup">';
		}elseif($loginORsignup == 'signup') {
			$legend = Constants::signUpLegend;
			$user = Constants::signUpUser;
			$pass = '<tr><td>'.Constants::signUpPass.'<td><input type="password" size="25" name="newPassword">'.
					'<tr><td>'.Constants::signUpPassCheck.'<td><input type="password" size="25" name="chkPassword">';
			$go = Constants::signUpGo;
			$question = Constants::qstnRegistered.
						' <a href="'.htmlspecialchars($_SERVER['PHP_SELF']).'?action=login">';
		}
		$form = '<div id="login"><form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="post">'.
				'<fieldset><legend>';
		if($error) {
			$form .= '<div class="error">'.$error.'</div>';
		} else {
			$form .= $legend;
		}
		$form .= '</legend><table>'.
				'<tr><td>'.$user.'<td><input type="text" maxlength="7" size="25" name="number" value='.$number.'>'.
				$pass.
				'<tr><td><td><button type="submit" name="submit" value="'.$loginORsignup.'">'.$go.'</button>'.
				'</table></fieldset></form><P>'.$question.Constants::clickHere.'</a></div>';
		return $form;
	}

	public function getForm($db, $error) {
		$include = Member::getInclude($db);
		$form = '<p><form action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '" method="post">';
		$form .= '<div class="error">'.$error.'</div>';
		$form .= '<table class="form">';
		//if (isset($this->properties['ID'])) {
			foreach ($this->properties as $key => $value) {
				if($key == 'ID') {
					$id = $value;
				}
				if(in_array($key, $include, true)) {
					$maxLength = '';
					switch($key) {
						case 'first_name':
						case 'last_name':
						case 'address':
						case 'city':
						case 'email':
							$maxLength = '50';
							break;
						case 'postal_code':
							$maxLength = '6';
							break;
						case 'birth':
						case 'phone':
						case 'mobile':
							$maxLength = '10';
							break;
						case 'number':
							$maxLength = '7';
							break;
						case 'gender':
							$maxLength = '1';
							break;
					}
					foreach(Constants::memberProperties as $cKey => $cValue) {
						if($key == $cKey) {
							$translatedKey = $cValue;
						}
					}
					$form .= '<tr><td>'.$translatedKey.'</td>';
					$form .= '<td><input type="text"
									 size="30"
									 name="'.$key.'"
									 value="'.$value.'"
									 maxlength="'.$maxLength.'"></td>';
				}
			}
			$form .= '<tr><td><hr></td><td><hr></td></tr>';
			if(basename($_SERVER['PHP_SELF'], '.php') == 'new_member') {
				$form .= '<tr><td></td>';
				$form .= "<td><button type='submit' name='submit'>".Constants::submitNewMember."</button></td></tr>";
			}elseif(basename($_SERVER['PHP_SELF'], '.php') == 'mutation') {
				$form .= '<tr><td><button type="button" 
							onclick="window.location.href=\''.$_SERVER['PHP_SELF'].'?table='.$db.'&remove='.$id.'\'">'.
							Constants::submitRemoveMember.'</button></td>';
				$form .= "<td><button type='submit' name='submit'>".Constants::submitUpdateMember."</button></td></tr>";
			}
			$form .= "</table></form>";
		//} else {
		//	$form = Constants::memberIsGone;
		//}
		return $form;
	}
	
	public function getRowOfForm($question, $name, $length, $value, $error) { //up for destruction
		$line = "<tr><td class='left'>".$question."</td>";
		$line .= "<td><input type='text' size='30'
                                  name='".$name."'
                                  maxlength='".$length."'
          	                      value='";
		//birthvalidator gives different values to use in the form or to add into the database
		if(is_array($value)) {
			$line .= $value["intoForm"];
		} else {
			$line .= $value;
		}
		$line .= "'></td>";
		$line .= "<td><span class='XX'>".$error."</span></td></tr>";
		return $line;
	}
	
	public function getMemberIntoQueryIntoTable($table) {   //up for destruction
		$query = "INSERT INTO ".$table." (";
		$count = count($this->member);
		for ($row = 0; $row < $count; $row++) {
			$question = $this->member[$row][0];
			$query .= $question;
			$query .= Constants::queryDelimiter;
		}
		$query = substr($query, 0, -2);
		$query .= Constants::queryAdditionCont;
		$query .= Constants::queryAdditionUpdated;
		$query .= ") VALUES ('";
		for ($row = 0; $row <$count; $row++) {
			$value = $this->member[$row][2];
			//birthvalidator gives different values to use in the form or to add into the database
			if(is_array($value)) {
				$query .= $value["intoDatabase"];
			} else {
				$query .= $value;
			}
			$query .= "'".Constants::queryDelimiter."'";
		}
		$query = substr($query, 0, -3);
		$query .= ", 'N'";
		$query .= ", '".date("Ymd");
		$query .= "')";
		$preparedQuery = <<<___SQL
			$query
___SQL;
		return $query;
	}
	
	public function getMemberIntoQueryToUpdateTable($table) {   //up for destruction
		/* UPDATE table
		 * SET column1 = value1, column2 = value2, ...
		 * WHERE condition;
		 */
		$query = "UPDATE ".$table." SET ";
		$count = count($this->member);
		for ($row = 0; $row < $count; $row++) {
			$question = $this->member[$row][0];
			$value = $this->member[$row][2];
			//TEST
			if(is_array($value)) {
				$value = $value["intoDatabase"];
			}
			//END TEST
			$query .= $question." = '".$value."'";
			$query .= Constants::queryDelimiter;
		}
		$query = substr($query, 0, -2);
		//$query .= Constants::queryAdditionCont;
		//$query .= Constants::queryAdditionUpdated;
		//$query .= ") VALUES ('";
		//for ($row = 0; $row <$count; $row++) {
		//	$value = $this->member[$row][2];
		//birthvalidator gives different values to use in the form or to add into the database
		//	if(is_array($value)) {
		//		$query .= $value["intoDatabase"];
		//	} else {
		//		$query .= $value;
		//	}
		//	$query .= "'".Constants::queryDelimiter."'";
		//}
		//$query = substr($query, 0, -3);
		//$query .= ", 'N'";
		//$query .= ", '".date("Ymd");
		//$query .= "')";
		$query .= " WHERE mID = '".$this->id."'";
		$preparedQuery = <<<___SQL
			$query
___SQL;
		return $query;
	}
	
	public function getMemberIntoForm() {    //up for destruction
		$tableRows = "";
		$count = count($this->member);
		for ($row = 0; $row < $count; $row++) {
			$question = $this->member[$row][0];
			$name = $this->member[$row][1];
			$length = $this->member[$row][4];
			$value = $this->member[$row][2];
			$error = $this->member[$row][3];
			$filter = $this->member[$row][5];
			$tableRows .= $this->getRowOfForm($question, $name, $length, $value, $error, $filter);
		}
		return $tableRows;
	}
	
	public function getFormXXX($leftSubmit, $rightSubmit) {		//up for destruction
		$form = "<p><form action='".$_SERVER['PHP_SELF']."' method='post'>";
		$form .=
		$form .= "<table>";
		$form .= $this->getMemberIntoForm();
		$form .= "<tr><td><hr><td><hr><td><hr><tr><td>";
		// left side will have no button if $leftSubmit is NULL
		if ($leftSubmit) {
			$form .= "<button type='button'
						onclick='window.location.href=\"remove.php?id=".$this->id.
						"\"'>".$leftSubmit."</button></td>";
		}
		$form .= "<td><button type='submit' name='submit'>".$rightSubmit."</button></td></tr>";
		$form .= "</table></form></p>";
		return $form;
	}

}

