<?php
error_reporting(E_ALL);
include_once('classes/player.class.php');
include_once('classes/hitter.class.php');
include_once('classes/pitcher.class.php');
include_once('classes/baseConvert.class.php');

$myhitter = new Hitter(190444);
$mypitcher = new Pitcher(206936);
//$myplayer->setOffset(190444);
echo '
Player 1:<br />
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
<br />
Player 2:<br />
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
