<?php
//create_databases.php
spl_autoload_register(function ($className) { require "Model/class.".str_replace('\\', '/', $className).".php"; });

function getCreateQuery($name) { 
	$query = 'CREATE TABLE `'.$name.'` (
			`ID` int(3) unsigned not null auto_increment,
			`last_name` varchar(100) not null,
			`first_name` varchar(100) not null,
			`address` varchar(100),
			`postal_code` varchar(6),
			`city` varchar(100),
			`email` varchar(100),
			`birth` date,
			`gender` varchar(1),
			`phone` varchar(10),
			`mobile` varchar(10),
			`number` varchar(7),
			`pay` char(1) not null,
			`last_updated` date not null,
			PRIMARY KEY (`ID`)
			)';
	return $query;
}

function doQuery() {
	$query = '';
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

//$databases = array('members', 'exmembers', 'friday', 'squash');
//foreach($databases as $db) {
$conn = new Communicator();
$conn->conn->query(getCreateQuery('members'));
echo "DONE";
}
//}
?>

<html>
<body>

<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
<input type="submit" value="HIT IT">
</form>

</body>
</html>