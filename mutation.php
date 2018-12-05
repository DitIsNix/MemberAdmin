<?php
//mutation.php
spl_autoload_register(function ($className) { require "Model/class.".
	str_replace('\\', '/', $className).".php"; });

/***
 * GET'id' 		> update member
 * GET'remove' 	> ask for definite removal
 * POST'remove'	> definite removal (delete from GET'table'
 * 					and insert into exmembers)
 */
var_dump($_POST);
var_dump($_GET);

// definite removal of member if POST'remove' is set
if(isset($_POST['remove'])) {
	$title = Constants::titleRemove;
	$member = new Member($_GET['remove'], $_GET['table']);
	var_dump($member);
	// insert member into ex members
	$sql = $member->getInsertQuery('exmembers');
	var_dump($sql);
	$conn = new Communicator();
	$stmt = $conn->runQuery($sql);
	// bind parameters to the statement
	foreach($member->properties as $k=>&$v) {
		if($k != 'submit') {
		// change birth back to format used in database
		// if birth has been set
			if($k == 'birth') {
				if (strtotime($v) !== false) {
					$date = new DateTime($v);
					$v = $date->format('Y-m-d');
				}
			}
		// set pay empty (exmembers don't pay)
			if($k == 'pay') {
				$v = '';
			}
		// set ID empty for auto increment
			if($k == 'ID') {
				$v = '';
			}
			$param = ':'.$k;
			$stmt->bindParam($param, $v);
		}
	}
	$check = $stmt->execute();
	
	$stmt = $conn->runQuery(
		'SELECT first_name FROM '.
		$_GET['table'].' WHERE ID LIKE :ID');
	$stmt->bindparam(":ID", $_GET['remove']);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$content = $row['first_name'].' '.Constants::isRemoved;
	// only delete old record if it has been inputted in exmembers
	if($check) {
		$stmt = $conn->runQuery(
				'DELETE FROM '.$_GET['table'].
				' WHERE ID=:ID');
		$stmt->bindparam(":ID", $_GET['remove']);
		$stmt->execute();
	}
} else {
// construct a member form with properties
	if(!empty($_GET['id'])) {
		$member = new Member($_GET['id'], $_GET['table']);
		// process POST to update member
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			// verify input
			$member->checkInput();
			if(empty($member->error)) {
				// construct a query
				$sql = $member->getUpdateQuery();
				$conn = new Communicator();
				$stmt = $conn->runQuery($sql);
				// bind parameters to the statement
				foreach($member->properties as $k=>&$v) {
					if($k != 'submit') {
						if($k == 'last_updated') {
							$v = date('Ymd');
						}
						if($k == 'birth') {
							if($v) {
							$date = new DateTime($v);
							$v = $date->format('Y-m-d');
							}
						}
						$param = ':'.$k;
						$stmt->bindParam($param, $v);
					}
				}
				$stmt->bindParam(':ID', $_GET['id']);
				$stmt->execute();
				$stmt = $conn->runQuery(
					'SELECT first_name FROM '.
					$_GET['table'].' WHERE ID LIKE :ID');
				$stmt->bindparam(":ID", $_GET['id']);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$lastUpdate = Constants::lastUpdate.
					$row['first_name'].'.<br>';
			}
		}
		if(($member->properties['birth'])) {
			$date = new DateTime($member->properties['birth']);
			$member->properties['birth'] = $date->format('d-m-Y');
		}
		$form = new Forms($member->properties);
		$content = $form->getForm($_GET['table'], $member->error);
		if(isset($lastUpdate)) {
			$content .= $lastUpdate;
		}
		$title = Constants::titleUpdate;
	}
// construct a list to show member properties when asking for definite removal
	if(!empty($_GET['remove'])) {
		$member = new Member($_GET['remove'], $_GET['table']);
		$content = Constants::msgRemove.$member->getMemberToRemove();
		$title = Constants::titleRemove;
	}
}

$page = new Page($title, $content);
echo $page->getPage();
