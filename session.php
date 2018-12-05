<?php
//session.php

//page should be included in all pages that require a log in

session_start();

//set a return location to sent the user back to the requested page
$currentPage = urlencode($_SERVER['REQUEST_URI']);
$location = "login.php?location=".$currentPage;

require_once __DIR__."/Model/class.User.php";

$user = new User();

if(!$user->isLoggedin()) {
	// redirect user to login if user is not logged in
	$content = $user->redirect($location, Constants::redirectToLogin);
	$page = new Page(NULL, $content);
	echo $page->getPage();
	die;
}

// else {  msg: u bent al ingelogd als NAAM NAAM