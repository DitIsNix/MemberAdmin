<?php
//login.php

session_start();

require_once __DIR__."/Model/class.User.php";
require_once __DIR__."/Model/class.Forms.php";
require_once __DIR__."/Model/class.Constants.php";
require_once __DIR__."/Model/class.Page.php";

$user = new User();

$error = NULL;
$number = NULL;
$content = Forms::getLogInForm($number, $error, 'login');

if(isset($_GET['location'])) {
	$_SESSION['returnLocation'] = htmlspecialchars($_GET['location']);
}

if(isset($_GET['registered'])) {
	echo "You are registered, please log in to continue";
	var_dump($_GET['registered']);
}

$title = Constants::loginTitle;
if(isset($_GET['action'])) {
	if($_GET['action']=='login') {
		$content = Forms::getLogInForm($number, $error, 'login');
	}
	if($_GET['action']=='signup') {
		$content = Forms::getLogInForm($number, $error, 'signup');
		$title = Constants::signupTitle;
	}
}

if(isset($_POST['submit'])) {
	$number = $_POST['number'];
	//logging in existing user
	if($_POST['submit'] == 'login') {
		$number = strip_tags($_POST['number']);
		$password = strip_tags($_POST['password']);
		if($_POST['number']=="") {
			$error = Constants::valErrorEmptyNumber;
		} elseif ($_POST['password']=="") {
			$error = Constants::valErrorEmptyPassword;
		} else {
			if($user->doLogin($number, $password)) {
				$user->redirect($_SESSION['returnLocation'], Constants::redirectAfterLogin);
			} else {
				$error = Constants::valErrorWrongPassword;
			}
		}
		$content = Forms::getLogInForm($number, $error, 'login');
	}
	//signing up new user 
	if($_POST['submit'] == 'signup') {
		$number = strip_tags($_POST['number']);
		$newPassword = strip_tags($_POST['newPassword']);
		$chkPassword = strip_tags($_POST['chkPassword']);
		try {			//check if userNumber already sign up
			$stmt = $user->runQuery("SELECT userName FROM users WHERE userName=:Number");
			$stmt->execute(array(':Number'=>$number));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		if($row) {
			$error = Constants::valErrorUserExists;
		} else if($newPassword !== $chkPassword) {
			$error = Constants::valErrorPasswordsDifferent;
		} else if ($number=="")	{
			$error = Constants::valErrorEmptyNumber;
		} else {
			try {		//check if user is on memberlist
				$stmt = $user->runQuery("SELECT Bondsnummer FROM members WHERE Bondsnummer=:Number");
				$stmt->execute(array(':Number'=>$number));
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
			} catch(PDOException $e) {
				echo $e->getMessage();
			}
			if(!$row) {
				$error = Constants::valErrorUserNotFound;
			} else {
				if($newPassword=="") {
					$error = Constants::valErrorEmptyPassword;
				} else if(strlen($newPassword) <6) {
					$error = Constants::valErrorPasswordTooShort;
				} else {
					if($user->register($number, $newPassword)){
						$user->redirect('login.php?registered', Constants::redirectAfterRegister);
					}
				}
			}
		}
		$content = Forms::getLogInForm($number, $error, 'signup');
	}
}


echo "<p>GET<br>";
var_dump($_GET);
echo "POST<br>";
var_dump($_POST);
echo "SESSION<br>";
var_dump($_SESSION);

$page = new Page($title, $content);
echo $page->getPage();