<?php
include('bootstrap.php');

if(empty($_GET['offset'])) {
	die("Sorry, invalid offset. <a href='index.php'>Return to Home</a>");
}
$offset = $_GET['offset'];
$myrom = new RBI3AndyBRom("../rbi2008.nes");
try {
	$player = new Player($myrom, $offset);
	header("Location: " . $player->getType() . ".view.php?offset=$offset");
	exit;
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";

}
