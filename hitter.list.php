<?php
error_reporting(E_ALL);
include_once('classes/Rom.class.php');
include_once('classes/RBI3AndyBRom.class.php');
include_once('classes/Player.class.php');
include_once('classes/Hitter.class.php');
include_once('classes/PlayerCollection.class.php');

$myrom = new RBI3AndyBRom("../rbi2008.nes");
$playerlist = new PlayerCollection();
$myhitter = new Hitter($myrom, 190444);
$playerlist->addPlayer($myhitter);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title></title>
	</head>
	<body>
		<?php
		echo $playerlist->toHTMLTable();
		?>
	</body>
</html>
