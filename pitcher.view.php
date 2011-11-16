<?php
include_once('classes/Rom.class.php');
include_once('classes/RBI3AndyBRom.class.php');
include_once('classes/Player.class.php');
include_once('classes/Pitcher.class.php');

$myrom = new RBI3AndyBRom("../rbi2008.nes");
$mypitcher = new Pitcher($myrom, 206936);


$pitcherdetails = '
	Pitcher:<br />
	Offset ' . $mypitcher->getOffset() . '<br />
	Name: ' . $mypitcher->getName() . '<br />
	Lineup #: ' . $mypitcher->getLineupNumber() . '<br />
	Type: ' . $mypitcher->getType() . '<br />
	ERA: ' . $mypitcher->getEra() . '<br />
	Throws: ' . $mypitcher->getThrows() . '<br />
	Sink Spd: ' . $mypitcher->getSinkerspeed() . '<br />
	Curv Spd: ' . $mypitcher->getCurvespeed() . '<br />
	Fast Spd: ' . $mypitcher->getFastballspeed() . '<br />
	Curv Left: ' . $mypitcher->getCurveleft() . '<br />
	Curv Rt: ' . $mypitcher->getCurveright() . '<br />
	Stamina: ' . $mypitcher->getStamina() . '<br />
	Sink: ' . $mypitcher->getSink() . '<br />
';

echo "<!DOCTYPE html>
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<title></title>
		</head>
		<body>
			<div class='menu'><a href='index.php'>Return to Home</a></div>
			$pitcherdetails
		</body>
	</html>
";