<?php
//list.php
spl_autoload_register(function ($className) { require "Model/class.".str_replace('\\', '/', $className).".php"; });

// 
require_once __DIR__."/session.php";

$today18YearsAgo = (date('Y')-18).date('md');

if($_GET['show'] == 'all') {
	$query = 'SELECT first_name, last_name, address, postal_code, city, email, birth, gender, phone,
								mobile, number FROM members ORDER BY last_name ASC, first_name ASC';
}elseif($_GET['show'] == 'junior'){
	$query = 'SELECT first_name, last_name, address, postal_code, city, email, birth, gender, phone,
								mobile, number FROM members WHERE birth >= "'.$today18YearsAgo.'" 
								ORDER BY last_name ASC, first_name ASC';
}elseif($_GET['show'] == 'senior'){
	$query = 'SELECT first_name, last_name, address, postal_code, city, email, birth, gender, phone,
								mobile, number FROM members WHERE birth <= "'.$today18YearsAgo.'" 
								ORDER BY last_name ASC, first_name ASC';
}elseif($_GET['show'] == 'friday'){
	$query = 'SELECT first_name, last_name, address, postal_code, city, email, phone, mobile
								FROM friday ORDER BY last_name ASC, first_name ASC';
}elseif($_GET['show'] == 'squash'){
	$query = 'SELECT first_name, last_name, email, phone, mobile FROM squash ORDER BY last_name ASC, first_name ASC';
}elseif($_GET['show'] == 'exmembers'){
	$query = 'SELECT first_name, last_name, address, postal_code, city, email, phone, mobile
								FROM exmembers ORDER BY last_name ASC, first_name ASC';
}elseif($_GET['show'] == 'board'){
	$query = 'SELECT board.position, members.first_name, members.last_name, members.email, members.phone,
								members.mobile FROM board LEFT JOIN members	ON board.mID=members.ID';
}

$conn = new Communicator();
$stmt = $conn->runQuery($query);
$stmt->execute();
$table = new Table($stmt->fetchAll(PDO::FETCH_ASSOC));
$content = $table->getTable('memberlist');
$page = new Page(Constants::titleMemberList, $content);
echo $page->getPage();




?>