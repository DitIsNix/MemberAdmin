<?php
//class.Communicator.php

require_once dirname("MemberAdministration")."/config.php";

class Communicator
{
	private $host = DB_SERVER;
	private $db_name = DB_DATABASE;
	private $username = DB_USERNAME;
	private $password = DB_PASSWORD;
	
	public $conn;
	
	public function __construct() {
		$this->conn = null;
		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $exception) {
			echo "Connection error: ".$exception->getMessage();
		} 
	}
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
		
	public function getData($query) {  //should be removed later
		try {
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$data = $this->conn->query($query);
			return $data;
		} catch(PDOException $error) {
			echo "CAUGHT ERROR: ".$error;
		}
	}
	
	public function getLastId() {
		$lastId = $this->conn->lastInsertId();
		return $lastId;
	}
	
	public function getTables() {
		$stmt = self::runQuery('show tables');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	
	public function getNameFromLastId() {   		 //up for destruction
		$lastId = $this->conn->lastInsertId();
		if ($lastId == "0") {
			return NULL;
		} else {
			$row = $this->getData(QueryController::searchNameWithId($lastId))->fetch(PDO::FETCH_ASSOC);
			//--array into variables
			foreach ($row as $key=>$value) {
				if ($key=="Voornaam") {
					$firstName=$value;
				}
				if ($key=="Achternaam") {
					$lastName=$value;
				}
			}
			return Constants::lastAdded.$firstName." ".$lastName.".<br>";
		}
	}
	
}