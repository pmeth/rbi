<?php
include('bootstrap.php');

$offset = 180256;
$myrom = new RBI3AndyBRom("../rbi2008.nes");
$myhitter = new Hitter($myrom, $offset);
$myhitter->setBats("L");
$myhitter->writeToRom();
