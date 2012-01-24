<?php
include('bootstrap.php');

$offset = 180256;

$myhitter = new Hitter($myrom, $offset);
$myhitter->setBats("L");
$myhitter->writeToRom();
