<?php
//TEST TEST TEST getsearch.php
spl_autoload_register(function ($className) { require 'Model/class.'.
		str_replace('\\', '/', $className).'.php'; });

function getSearchResults($db, $caption, $search) {
	$search = "%".$search."%";
	$conn = new Communicator();
	$stmt = $conn->runQuery(
			'SELECT first_name, last_name, ID FROM '.$db.
			' WHERE first_name LIKE :search OR last_name LIKE :search
				ORDER BY last_name ASC, first_name ASC');
	$stmt->execute(array(':search'=>$search));
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (empty($data)) {
		return NULL;
	} else {
		$table = new Table($data);
		$table->setDatabaseTable($db);
		$table->setCaption($caption);
		$table->setNoHeader();
		return $table->getTable('search');
	}
}

$content = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$search = strip_tags($_GET['search']);
	$handler = new InputHandler('search', $search);
	$error = $handler->checkData();
	$content .= $error;
	if(empty($error)) {
		$conn = new Communicator();
		$noResult = TRUE;
		foreach ($conn->getTables() as $k => $v) {
			if($v != 'board' && $v != 'users') {
				$caption = Constants::searchFound.'<i>'.Constants::memberProperties[$v].'</i>';
				$result = getSearchResults($v, $caption, $search);
				if($result) {
					$content .= $result.'<br>';
					$noResult = FALSE;
				}
			}
		}
		if($noResult) {
			$content .= Constants::searchNotFound;
		}
	}
}

echo $content;

?>
