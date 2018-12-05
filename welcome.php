<?php
//welcome.php

require_once __DIR__."/Model/class.Page.php";
require_once __DIR__."/Model/class.Constants.php";

//if(isset($_SESSION)) {var_dump($_SESSION);} else {echo "no session";}
$content = NULL;
$page = new Page(Constants::titleWelcome, $content);
echo $page->getPage();
