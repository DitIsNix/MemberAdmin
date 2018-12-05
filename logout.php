<?php
//logout.php
require_once __DIR__."/Model/class.User.php";
require_once __DIR__."/Model/class.Constants.php";
require_once __DIR__."/Model/class.Page.php";
//require_once __DIR__."/session.php";
session_start();
var_dump($_GET);
if(isset($_SESSION)) {var_dump($_SESSION);} else {echo "no session";}
$user = new User();

if(isset($_GET['logout']) && $_GET['logout']=="true")
{
	$user->doLogout();
	echo $user->redirect('welcome.php', Constants::redirectAfterLogout);
}

/*
require_once('session.php');
require_once('class.user.php');
$user_logout = new USER();

if($user_logout->is_loggedin()!="")
{
	$user_logout->redirect('home.php');
}
if(isset($_GET['logout']) && $_GET['logout']=="true")
{
	$user_logout->doLogout();
	$user_logout->redirect('index.php');
}
*/