<?php
//search.php
spl_autoload_register(function ($className) { require 'Model/class.'.
	str_replace('\\', '/', $className).'.php'; });

$script = "search.js";
$form = '<form>'.Constants::search.': <input type="text" onkeyup="showResults(this.value)"></form>';
$results = '<p><span id="searchResults"></span></p>';
$content = $form.$results;


$page = new Page(Constants::titleSearch, $content, $script);
echo $page->getPage();

?>