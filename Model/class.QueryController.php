<?php
//class.QueryController.php

class QueryController						// up for destruction
{
	private $search;
	private $query;
	
	public function __construct($search) {
		$this->search = $search;
		$this->query = "";
	}
	
	public static function searchQuery($searchTerm) {
		$query = <<<___SQL
             SELECT
		Voornaam,
		Achternaam,
		Plaats,
		mID
		FROM members
		WHERE Achternaam LIKE '$searchTerm'
		OR Voornaam LIKE '$searchTerm'
		ORDER BY Achternaam ASC, Voornaam ASC
___SQL;
		return $query;
	}
	
	public static function customMemberList($customTerm) {
		$query = <<<___SQL
            SELECT
		Voornaam,
		Achternaam,
		Adres,
		Postcode,
		Plaats,
		Email,
		Geboortedatum,
		Telefoon,
		Mobiel
                FROM members
                WHERE $customTerm
                ORDER BY Achternaam, Voornaam
___SQL;
		return $query;
	}
	
	public static function getMemberWithId($id) {
		$query = <<<___SQL
			SELECT
		Voornaam,
		Achternaam,
		Adres,
		Postcode,
		Plaats,
		Email,
		Geboortedatum,
		Sexe,
		Telefoon,
		Mobiel,
		Bondsnummer,
		Haarlempas
			FROM
				members
		WHERE mID LIKE $id
___SQL;
		return $query;
	}
	
	public static function searchNameWithId($id) {
		$query = <<<___SQL
			SELECT
		Voornaam,
		Achternaam
			FROM
		members
			WHERE mID LIKE $id
___SQL;
		return $query;
	}
	
	const memberList = <<<___SQL
            SELECT
                Voornaam,
                Achternaam,
                Adres,
                Postcode,
                Plaats,
                Email,
                Geboortedatum,
                Sexe,
                Telefoon,
                Mobiel,
                Bondsnummer,
                Cont,
                Bijgewerkt
            FROM
                members
            ORDER BY Achternaam ASC, Voornaam ASC
___SQL;
	
	const boardMemberList = <<<___SQL
            SELECT
		Bestuur.Functie,
		Members.Voornaam,
		Members.Achternaam,
		Members.Email,
		Members.Telefoon,
		Members.Mobiel
				FROM Bestuur
				LEFT JOIN Members
				ON Bestuur.mID=Members.mID
___SQL;
	
}