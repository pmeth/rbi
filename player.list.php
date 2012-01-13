<?php
include('bootstrap.php');


$myrom = new RBI3AndyBRom("../rbi2008.nes");
$playermapper = new PlayerROMMapper($myrom);
$teammapper = new TeamROMMapper($myrom);

//$playerlist = new PlayerCollection();
//$myhitter = new Hitter($myrom, 190444);
//$playerlist->addPlayer($myhitter);

if (isset($_GET['teamoffset'])) {
	$filteredteam = $teammapper->get($_GET['teamoffset']);
	$playerlist = $playermapper->getPlayersByTeam($filteredteam);
} else {
	$filteredteam = null;
	$playerlist = $playermapper->getAllPlayers();
}


$teamlist = $teammapper->getAllTeams();
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

			header, menu {
				margin: 0;
				padding: 0;
			}
			header a, menu a {
				color: yellow;
				text-decoration: none;
			}

			header a:hover, menu a:hover {
				text-decoration: underline;
			}
		</style>
		<title></title>
	</head>
	<body>
		<?php
		include('partials/header.partial.php');
		include('partials/menu.partial.php');
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" >
			Team: 
			<select name="teamoffset">
				<option value="">All Teams</option>
				<?php foreach ($teamlist as $team) : ?>

				<option value='<?php echo $team->getOffset(); ?>' <?php if (is_object($filteredteam) && $filteredteam->getOffset() == $team->getOffset()) { echo "selected='selected'"; } ?> ><?php echo $team->getName(); ?></option>
				<?php endforeach; ?>
			</select>
			<input type="submit" value="FILTER" />
		</form>	
		<?php
		echo $playerlist->toHTMLTable();
		?>
	</body>
</html>
