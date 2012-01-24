<?php
include('bootstrap.php');

if(empty($_GET['offset'])) {
	die("Sorry, invalid offset. <a href='index.php'>Return to Home</a>");
}
$offset = $_GET['offset'];

try {
	$player = new Player($myrom, $offset);
	header("Location: " . $player->getType() . ".view.php?offset=$offset");
	exit;
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";

}

