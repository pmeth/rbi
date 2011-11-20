<?php
include('bootstrap.php');


$myrom = new RBI3AndyBRom("../rbi2008.nes");
//$playerlist = new PlayerCollection();
//$myhitter = new Hitter($myrom, 190444);
//$playerlist->addPlayer($myhitter);
$teamlist = $myrom->getTeams();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<style type="text/css">
			body {
				font-family: 'Courier New';
				font-weight: bold;
				background-color: black;
				color: white;
			}
		</style>
		<title></title>
	</head>
	<body>
		<ul>

			<?php
			foreach ($teamlist as $id => $team) {

				echo "<li>$id => $team</li>";
			}
			?>
		</ul>
	</body>
</html>
