<?php
namespace Pmeth\RBI;
include('bootstrap.php');

$rom = new RBI3AndyBRom('../rbi2008.nes');
$teammapper = new TeamROMMapper($rom);
for ($i = 1; $i < 20; $i++) {
	$team = $teammapper->get($i);
	echo "Team: " . $team->getName() . "<br />";
}