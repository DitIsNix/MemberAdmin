<?php
//mailing_list.php
spl_autoload_register(function ($className) { require "Model/class.".str_replace('\\', '/', $className).".php"; });

$today18YearsAgo = (date('Y')-18).date('md');

// set which table to query
if($_GET['target'] == 'junior'){
	$query = 'SELECT email FROM members WHERE birth >= "'.$today18YearsAgo.'"
								ORDER BY email ASC';
}elseif($_GET['target'] == 'senior'){
	$query = 'SELECT email FROM members WHERE birth <= "'.$today18YearsAgo.'"
								ORDER BY email ASC';
}elseif($_GET['target'] == 'friday'){
	$query = 'SELECT email FROM friday ORDER BY email ASC';
}elseif($_GET['target'] == 'squash'){
	$query = 'SELECT email FROM squash ORDER BY email ASC';
}elseif($_GET['target'] == 'exmembers'){
	$query = 'SELECT email FROM exmembers ORDER BY email ASC';
}

// fetch data
$conn = new Communicator();
$stmt = $conn->runQuery($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_COLUMN);

// set file name for download
$fileName = 'MailLijst'.Constants::memberProperties[$_GET['target']].date('dMY');

// create a new csv file and store it in tmp directory
$newFile = fopen('tmp/mailing_list.csv', 'w');
fputcsv($newFile, $data, ',');
fclose($newFile);

// output headers so that the file is downloaded rather than displayed
header('Content-type: text/csv');
header('Content-disposition: attachment; filename = '.$fileName.'.csv');
readfile('tmp/mailing_list.csv');
unlink('tmp/mailing_list.csv');
?>

