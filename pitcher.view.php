<?php
include('bootstrap.php');
if(empty($_GET['offset'])) {
	die("Sorry, invalid offset. <a href='index.php'>Return to Home</a>");
}
$offset = $_GET['offset'];
$myrom = new RBI3AndyBRom("../rbi2008.nes");
$mapper = new PitcherROMMapper($myrom);
$mypitcher = $mapper->get($offset);


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
			<div class='menu'><a href='player.list.php'>Return to List</a></div>
			$pitcherdetails
			<a href='pitcher.edit.php?offset=" . $mypitcher->getOffset() . "'>Edit this player</a>
		</body>
	</html>
";