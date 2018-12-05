<?php
//new_member.php
spl_autoload_register(function ($className) { require "Model/class.".
	 str_replace('\\', '/', $className).".php"; });

// construct an empty member
$member = new Member(null);

// process POST to input new member
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// verify input
	$member->checkInput();
	if(empty($member->error)) {
		var_dump($_POST);
		var_dump($member);
		// construct a query
		$sql = $member->getInsertQuery($_GET['table']);
		$conn = new Communicator();
		$stmt = $conn->runQuery($sql);
		// bind parameters to the statement
		foreach($member->properties as $k=>&$v) {
			if($k != 'submit') {
				if($k == 'last_updated') {
					$v = date('Ymd');
				}
				if($k == 'birth') {
					$date = new DateTime($v);
					$v = $date->format('Y-m-d');
				}
				if($k == 'pay') {
					$v = 'N';
				}
				$param = ':'.$k;
				$stmt->bindParam($param, $v);
			}
		}
		$stmt->execute();
		// return first name of the last inserted member
		$lastId = $conn->getLastId();
		$stmt = $conn->runQuery('SELECT first_name FROM '.
					$_GET['table'].' WHERE ID LIKE :ID');
		$stmt->bindparam(":ID", $lastId);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$lastAdded = Constants::lastAdded.$row['first_name'].'.<br>';
		// after succesfull addition: clear member properties to empty the form
		$member = new Member(NULL);
	}
}

// construct a form based on which table member will be added to
if(isset($_GET['table'])) {
	$form = new Forms($member->properties);
	$content = $form->getForm($_GET['table'], $member->error);
	if(isset($lastAdded)) {
		$content .= $lastAdded;
	}
	$page = new Page(Constants::titleNewMember, $content);
} else {
	$page = new Page('', '');
}
echo $page->getPage();

?>