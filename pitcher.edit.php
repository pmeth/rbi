<?php

include('bootstrap.php');
if (empty($_GET['offset'])) {
	die("Sorry, invalid offset. <a href='index.php'>Return to Home</a>");
}
$offset = $_GET['offset'];

$mapper = new PitcherROMMapper($myrom);
$mypitcher = $mapper->get($offset);

if (!empty($_POST['submit'])) {
	// this has to be first for it to do anything
	$mypitcher->setAcceptAbnormal(isset($_POST['acceptabnormal']) && $_POST['acceptabnormal'] == 'true');
	$mypitcher->setName($_POST['name']);

	$mypitcher->setEra($_POST['era']);
	$mypitcher->setThrows($_POST['throws']);
	$mypitcher->setSinkerspeed($_POST['sinkspeed']);
	$mypitcher->setCurvespeed($_POST['curvespeed']);
	$mypitcher->setFastballspeed($_POST['fastspeed']);
	$mypitcher->setCurveleft($_POST['curveleft']);
	$mypitcher->setCurveright($_POST['curveright']);
	$mypitcher->setStamina($_POST['stamina']);
	$mypitcher->setSink($_POST['sink']);

	if ($mypitcher->valid()) {
		$mypitcher->writeToRom();
		echo "Player updated<br/>";
	} else {
		echo "Some fields did not validate:<br>";
		echo $mypitcher->getError();
	}
}

$pitcherdetails = '
	Pitcher:<br />
	<form action="' . $_SERVER['PHP_SELF'] . '?offset=' . $offset . '" method="post">
	Offset: <input name="offset" type="text" readonly="readonly" value="' . $mypitcher->getOffset() . '" /><br />
	Name: <input name="name" type="text" value="' . $mypitcher->getName() . '" /><br />
	Lineup #: <input name="lineupnumber" type="text" readonly="readonly" value="' . $mypitcher->getLineupNumber() . '" /><br />
	Type: <input name="type" type="text" readonly="readonly" value="' . $mypitcher->getType() . '" /><br />
	ERA: <input name="era" type="text" value="' . $mypitcher->getEra() . '" /><br />
	Throws: <input name="throws" type="text" value="' . $mypitcher->getThrows() . '" /><br />
	Sink Spd: <input name="sinkspeed" type="text" value="' . $mypitcher->getSinkerspeed() . '" /><br />
	Curv Spd: <input name="curvespeed" type="text" value="' . $mypitcher->getCurvespeed() . '" /><br />
	Fast Spd: <input name="fastspeed" type="text" value="' . $mypitcher->getFastballspeed() . '" /><br />
	Curv Left: <input name="curveleft" type="text" value="' . $mypitcher->getCurveleft() . '" /><br />
	Curv Rt: <input name="curveright" type="text" value="' . $mypitcher->getCurveright() . '" /><br />
	Stamina: <input name="stamina" type="text" value="' . $mypitcher->getStamina() . '" /><br />
	Sink: <input name="sink" type="text" value="' . $mypitcher->getSink() . '" /><br />
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
			$pitcherdetails
		</body>
	</html>
";
