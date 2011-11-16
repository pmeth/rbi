<?php
include_once('classes/Rom.class.php');
include_once('classes/RBI3AndyBRom.class.php');
include_once('classes/Player.class.php');
include_once('classes/Hitter.class.php');

$myrom = new RBI3AndyBRom("../rbi2008.nes");
$myhitter = new Hitter($myrom, 190444);


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
			<div class='menu'><a href='index.php'>Return to Home</a></div>
			$hitterdetails
		</body>
	</html>
";