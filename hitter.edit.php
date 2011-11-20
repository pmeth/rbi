<?php

include('bootstrap.php');
if (empty($_GET['offset'])) {
	die("Sorry, invalid offset. <a href='index.php'>Return to Home</a>");
}
$offset = $_GET['offset'];
$myrom = new RBI3AndyBRom("../rbi2008.nes");
$myhitter = new Hitter($myrom, $offset);
if (!empty($_POST['submit'])) {
	$myhitter->setName($_POST['name']);
	$myhitter->setPosition($_POST['pos']);
	$myhitter->setBats($_POST['bats']);
	$myhitter->setAverage($_POST['avg']);
	$myhitter->setHomeruns($_POST['hr']);
	$myhitter->setPower($_POST['power']);
	$myhitter->setContact($_POST['contact']);
	$myhitter->setSpeed($_POST['speed']);

	if ($myhitter->valid()) {
		$myhitter->writeToRom();
		echo "Player updated<br/>";
	} else {
		echo "Some fields did not validate:<br>";
		echo $myhitter->getError();
	}
}

$hitterdetails = '
	Hitter:<br />
	<form action="' . $_SERVER['PHP_SELF'] . '?offset=' . $offset . '" method="post">
	Offset: <input name="offset" type="text" readonly="readonly" value="' . $myhitter->getOffset() . '" /><br />
	Name: <input name="name" type="text" value="' . $myhitter->getName() . '" /><br />
	Lineup #: <input name="lineupnumber" type="text" readonly="readonly" value="' . $myhitter->getLineupNumber() . '" /><br />
	Type: <input name="type" type="text" readonly="readonly" value="' . $myhitter->getType() . '" /><br />
	Pos: <input name="pos" type="text" value="' . $myhitter->getPosition() . '" /><br />
	Bats: <input name="bats" type="text" value="' . $myhitter->getBats() . '" /><br />
	Avg: <input name="avg" type="text" value="' . $myhitter->getAverage() . '" /><br />
	HR: <input name="hr" type="text" value="' . $myhitter->getHomeruns() . '" /><br />
	Power: <input name="power" type="text" value="' . $myhitter->getPower() . '" /><br />
	Contact: <input name="contact" type="text" value="' . $myhitter->getContact() . '" /><br />
	Speed: <input name="speed" type="text" value="' . $myhitter->getSpeed() . '" /><br />
		<input type="submit" name="submit" value="Save" /><br />
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
		</body>
	</html>
";