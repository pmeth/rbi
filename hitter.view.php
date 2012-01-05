<?php
include('bootstrap.php');
if(empty($_GET['offset'])) {
	die("Sorry, invalid offset. <a href='index.php'>Return to Home</a>");
}
$offset = $_GET['offset'];
$myrom = new RBI3AndyBRom("../rbi2008.nes");
$mapper = new HitterROMMapper($myrom);
$myhitter = $mapper->get($offset);


$hitterdetails = '
	Hitter:<br />
	Offset: ' . $myhitter->getOffset() . '<br />
	Name: ' . $myhitter->getName() . '<br />
	Lineup #: ' . $myhitter->getLineupNumber() . '<br />
	Type: ' . $myhitter->getType() . '<br />
	Pos: ' . $myhitter->getPosition() . '<br />
	Bats: ' . $myhitter->getBats() . '<br />
	Avg: ' . $myhitter->getAverage() . '<br />
	HR: ' . $myhitter->getHomeruns() . '<br />
	Power: ' . $myhitter->getPower() . '<br />
	Contact: ' . $myhitter->getContact() . '<br />
	Speed: ' . $myhitter->getSpeed() . '<br />
';

echo "<!DOCTYPE html>
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<title></title>
		</head>
		<body>
			<div class='menu'><a href='player.list.php'>Return to List</a></div>
			$hitterdetails
			<a href='hitter.edit.php?offset=" . $myhitter->getOffset() . "'>Edit this player</a>
		</body>
	</html>
";