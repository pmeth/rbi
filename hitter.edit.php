<?php
namespace Pmeth\RBI;
include('bootstrap.php');
if (empty($_GET['offset'])) {
	die("Sorry, invalid offset. <a href='index.php'>Return to Home</a>");
}
$offset = $_GET['offset'];
$mapper = new HitterROMMapper($myrom);
$myhitter = $mapper->get($offset);

if (!empty($_POST['submit'])) {
	// this has to be first for it to do anything
	$myhitter->setAcceptAbnormal(isset($_POST['acceptabnormal']) && $_POST['acceptabnormal'] == 'true');
	$myhitter->setName($_POST['name']);

	$myhitter->setPosition($_POST['pos']);
	$myhitter->setBats($_POST['bats']);
	$myhitter->setAverage($_POST['avg']);
	$myhitter->setHomeruns($_POST['hr']);
	$myhitter->setPower($_POST['power']);
	$myhitter->setContact($_POST['contact']);
	$myhitter->setSpeed($_POST['speed']);

	if ($mapper->validate($myhitter)) {
		$mapper->save($myhitter);
		header("Location: hitter.view.php?offset=$offset&message=Player updated");
	} else {
		echo "Some fields did not validate:<br>";
		echo $myhitter->getError();
	}
}

$hitterdetails = '
	Hitter:<br />
	<form action="' . $_SERVER['PHP_SELF'] . '?offset=' . $offset . '" method="post">
	Team: ' . $myhitter->getTeam()->getName() . '<br />
	Offset: ' . $myhitter->getOffset() . '<br />
	Lineup #: ' . $myhitter->getLineupNumber() . '<br />
	Type: ' . $myhitter->getType() . '<br />
	Name: <input name="name" type="text" value="' . $myhitter->getName() . '" /><br />
	Pos: <input name="pos" type="text" value="' . $myhitter->getPosition() . '" /><br />
	Bats: <input name="bats" type="text" value="' . $myhitter->getBats() . '" /><br />
	Avg: <input name="avg" type="text" value="' . $myhitter->getAverage() . '" /><br />
	HR: <input name="hr" type="text" value="' . $myhitter->getHomeruns() . '" /><br />
	Power: <input name="power" type="text" value="' . $myhitter->getPower() . '" /><br />
	Contact: <input name="contact" type="text" value="' . $myhitter->getContact() . '" /><br />
	Speed: <input name="speed" type="text" value="' . $myhitter->getSpeed() . '" /><br />
	Accept Abnormal: <input name="acceptabnormal" type="checkbox" value="true" /><br />
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
