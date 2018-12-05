<?php
//class.Constants.php

class Constants {
	const loginTitle  = "Log in met uw bondsnummer en wachtwoord";
	const signupTitle = "Meldt u met uw bondsnummer aan als nieuwe gebruiker";
	
	const redirectToLogin = "U wordt doorverwezen om in te loggen.";
	const redirectAfterLogin = "U wordt terugverwezen naar de vorige pagina.";
	const redirectAfterRegister = "";
	const redirectAfterLogout = "U bent uitgelogd.";
	
	const logInLegend 		= "Log in voor toegang tot de ledenlijst";
	const logInUser  		= "Bondsnummer: ";
	const logInPass  		= "Wachtwoord: ";
	const logInGo    		= "Inloggen";
	
	const signUpLegend		= "Meldt u aan als nieuwe gebruiker";
	const signUpUser 		= "Bondsnummer: ";
	const signUpPass 		= "Wachtwoord: ";
	const signUpPassCheck 	= "Wachtwoord herhalen: ";
	const signUpGo   		= "Aanmelden";
	
	const qstnRegistered 	= "Al geregistreerd?";
	const qstnNotRegistered = "Nog niet geregistreerd?";
	
	const clickHere = "Klik hier";
	
	const pageStyle  = "style.css";
	const pageCharset= "utf-8";
	const pageTitle  = "TTV Spaarne";
	
	const valErrorEmptyUser = "Geen gebruiker ingevuld";
	const valErrorEmptyNumber = "Geen bondsnummer ingevuld";
	const valErrorEmptyPassword = "Geen wachtwoord ingevuld";
	const valErrorWrongPassword = "Het wachtwoord komt niet overeen met de gebruiker";
	const valErrorUserExists = "De gebruiker heeft al een account";
	const valErrorPasswordsDifferent = "De wachtwoorden komen niet overeen";
	const valErrorUserNotFound = "Gebruiker niet gevonden";
	const valErrorPasswordTooShort = "Het wachtwoord moet minstens 6 karakters lang zijn";
	const valErrorEmptyFirstName = "U heeft geen voornaam opgegeven";
	const valErrorEmptyLastName = "U heeft geen achternaam opgegeven";
	const valErrorFirstName = "U heeft een ongeldige voornaam opgegeven";
	const valErrorLastName = "U heeft een ongeldige achternaam opgegeven";
	const valErrorAddress = "U heeft een ongeldig adres opgegeven";
	const valErrorPostalCode = "U heeft een ongeldige postcode opgegeven";
	const valErrorCity = "U heeft een ongeldige woonplaats opgegeven";
	const valErrorEmail = "U heeft een ongeldig emailadres opgegeven";
	const valErrorBirth = "U heeft een ongeldige geboortedatum opgegeven";
	const valErrorBirthFormat = "Geef een geboortedatum op als DD-MM-JJJJ";
	const valErrorGender = "Kies bij 'Sexe' uit man (M) of vrouw (V)";
	const valErrorPhone = "Vul een geldig telefoonnummer van 10 cijfers in";
	const valErrorNumber = "Vul een geldig bondsnummer van 7 cijfers in";
	const valErrorSearch = "U heeft een ongeldige zoekterm opgegeven";
	
	const emptyUpdate = "Zoek hiernaast een lid om de gegevens te kunnen wijzigen.";
	const emptyRemove = "";
	
	const titleSearch     = "Lid zoeken";
	const titleMemberList = "Ledenlijst";
	const titleNewMember  = "Nieuw lid aanmelden";
	const titleUpdate     = "Gegevens wijzigen";
	const titleRemove	  = "Lid uitschrijven";
	const msgRemove		  = "<p>Wilt u het volgende lid uitschrijven en zijn of haar gegevens
							 verplaatsen naar de lijst Oud-leden?<p>";
	const titleBoard      = "Bestuur";
	const titleWelcome    = "Welkom in het ledenadministratiesysteem van TTV Spaarne!";
	const isRemoved       = " is uitgeschreven en zijn of haar gegevens zijn verplaatst naar de lijst Oud-leden.";
	const memberIsGone	  = "Dit lid is niet meer ingeschreven.";
	
	
	const changeValues = "Mutatie";
	const showValues = "<b>Toon gegevens</b>";
	
	const lastAdded  = "Laatst toegevoegd: ";
	const lastUpdate = "Gegevens gewijzigd van ";
	
	const queryDelimiter = ", ";
	
	const queryAdditionCont  = ", Cont";
	const queryAdditionUpdated = ", Bijgewerkt";
	
	const requiredFields = "Verplichte velden zijn gemarkeerd met een *\n";
	
	const search = "Zoeken";
	
	const submitUpdateMember = "Wijzigen";
	const backToUpdateMember = "Terug naar Wijzigen";
	const submitNewMember    = "Nieuw lid toevoegen";
	const submitRemoveMember = "Uitschrijven";
	
	const searchFound = "Gevonden in de lijst ";
	const searchNotFound = "Er is niets gevonden.";
	
	const noResults = "Niets gevonden.";
	
	const memberProperties = array( 'first_name'=> 'Voornaam',
									'last_name'	=> 'Achternaam',
									'address'	=> 'Adres',
									'postal_code'=>'Postcode',
									'city'		=> 'Plaats',
									'email'		=> 'Email',
									'birth'		=> 'Geboortedatum',
									'gender'	=> 'Sexe',
									'phone'		=> 'Telefoon',
									'mobile'	=> 'Mobiel',
									'number'	=> 'Bondsnummer',
									'pay'		=> 'Contributie',
									'Chairman'	=> 'Voorzitter',
									'Vice Chairman' => 'Vicevoorzitter',
									'Secretary' => 'Secretaris',
									'Treasurer' => 'Penningmeester',
									'Competition Secretary' => 'Wedstrijdsecretaris',
									'Honorary Chairman' => 'Erevoorzitter',
									'Member of Merit' => 'Lid van verdienste',
									'junior' => 'Junioren',
									'senior' => 'Senioren',
									'friday' => 'Vrijdagochtend',
									'squash' => 'Squash',
									'exmembers' => 'Oudleden',
									'members' => 'Leden'
	);
}