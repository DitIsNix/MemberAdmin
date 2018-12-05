<?php
//class.Table.php

class Table
{
	private $data;
	private $caption = '';
	private $exceptionHeader = '';
	private $exceptionBody = '';
	private $databaseTable;			// refers to the table in the database
	private $header = TRUE;
			
	function __construct($data) {
		$this->data = $data;
	}
	
	public function getTable($cssClass) {
		// only return the table when there are results
		if(self::getHeaders()) {
				$table = '<table class="'.$cssClass.'">';
				$table .= '<caption>'.$this->caption.'</caption>';
				if($this->header) {$table .= self::getHeaders();}
				$table .= self::getBody();
				$table .= '</table>';
			return $table;
		} else {
			return Constants::noResults;
		}
	}
	
	public function setCaption($caption) {
		$this->caption = $caption;
	}
	
	public function setExceptionHeader($key, $value, $header) {
		$this->exceptionHeader = array('key'=>$key, 'value'=>$value, 'header'=>$setHeader);
	}
	
	public function setNoHeader() {
		$this->header = FALSE;
	}
	
	public function setExceptionBody($key, $value, $body) {
		$this->exceptionBody = array('key'=>$key, 'value'=>$value, 'body'=>$setBody);
	}
	
	public function setDatabaseTable($databaseTable) {
		$this->databaseTable = $databaseTable;
	}
	
	private function getHeaders() {
		// return FALSE in case there is no data (i.e. no results)
		if (empty($this->data)) {
			return FALSE;
		} else {
			$row = $this->data[0];
			$header = '<tr>';
			foreach ($row as $key=>$value) {
			// keep header empty for ID or board position columns
				if($key == 'ID' || $key == 'position') {
					$header .= '<th></th>';
				} else {
					//search in Const-array for corresponding translations of columns
					foreach(Constants::memberProperties as $cKey => $cValue) {
						if($key == $cKey) {
							$translatedKey = $cValue;
						}
					}
					$header .= '<th>'.$translatedKey.'</th>';
				}
			}
			$header .= '</tr>';
			return $header;
		}
	}
	
	private function getBody() {
		$body = '';
		foreach ($this->data as $rows) {
			$body .= '<tr>';
			foreach ($rows as $key=>$value) {
			// offer link to mutate member properties in a form
				if($key == 'ID') {
					$body .= '<td><a href="mutation.php?table='.$this->databaseTable.'&id='.$value.'" target="form_frame">'.
								Constants::changeValues.'</a></td>';
			// keep field empty if birth is 00-00-0000
				} elseif($key == 'birth') {
					if(strtotime($value) !== false){
						$date = new DateTime($value);
						$value = $date->format('d-m-Y');
					} else {
						$value = '';
					}
					$body .= '<td>'.$value.'</td>';
			// check and translate if field is board position
				} elseif(array_key_exists($value, Constants::memberProperties)) {
					$body .= '<td><b>'.Constants::memberProperties[$value].'</b></td>';
				} else {
					$body .= '<td>'.$value.'</td>';
				}
			}
			$body .= '</tr>';
		}
		return $body;
	}
		
	public function getMemberList() {			//up for destruction
		$table = "<table class='memberlist'>";
		$rowTH = $this->header->fetch(PDO::FETCH_ASSOC);
		if (empty($rowTH)) {
			$table = Constants::noResults;
		} else {
			$table .= "<tr>";
			foreach ($rowTH as $field=>$value) {
				if ($field == "Functie") {
					$table .= "<th>&nbsp;</th>";
				//} elseif ($field == "mID") {
				//	$table .= "";
				} else {
					$table .= "<th>$field</th>";
				}
			}
			$table .= "</tr>";
			$this->data->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($this->data as $rowTD) {
				$table .= "<tr>";
				foreach ($rowTD as $name=>$value) {
					if ($value == "N") {
						$table .= "<td bgcolor='red'>&nbsp;</td>";
					} elseif ($value == "Y") {
						$table .= "<td bgcolor='green'>&nbsp;</td>";
					} elseif ($name == "Functie") {
						$table .= "<td><b>$value</b></td>";
					} elseif ($name == "mID") {
						$table .= "<td><a href='mutation.php?remove=$value' target='form_frame'>".
								Constants::changeValues."</a></td>";
					} elseif ($name == "Email") {
						$table .= "<td><a href='mailto:$value'>$value</a></td>";
					} else {
						$table .= "<td>$value</td>";
					}
				}
				$table .= "</tr>";
			}
			$table .= "</table>";
		}
		return $table;
	}
	
}


