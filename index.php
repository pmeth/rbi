<?php
error_reporting(E_ALL);
include_once('classes/player.class.php');
include_once('classes/hitter.class.php');
include_once('classes/baseConvert.class.php');

$myplayer = new Hitter(190444, new BaseConvert());

//$myplayer->setOffset(190444);
echo '
Offset: ' . $myplayer->getOffset() . '<br />
Name: ' . $myplayer->getName() . '<br />
Lineup #: ' . $myplayer->getLineupNumber() . '<br />
Type: ' . $myplayer->getType() . '<br />
Pos: ' . $myplayer->getPosition() . '<br />
Bats: ' . $myplayer->getBats() . '<br />
Avg: ' . $myplayer->getAverage() . '<br />
HR: ' . $myplayer->getHomeruns() . '<br />
Power: ' . $myplayer->getPower() . '<br />
Contact: ' . $myplayer->getContact() . '<br />
Speed: ' . $myplayer->getSpeed() . '<br />

';