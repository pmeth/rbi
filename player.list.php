<?php
include('bootstrap.php');


$myrom = new RBI3AndyBRom("../rbi2008.nes");
//$playerlist = new PlayerCollection();
//$myhitter = new Hitter($myrom, 190444);
//$playerlist->addPlayer($myhitter);

if (isset($_GET['team'])) {
	$filteredteam = $_GET['team'];
	$playerlist = $myrom->getPlayersByTeam($filteredteam);
} else {
	$filteredteam = '';
	$playerlist = $myrom->getAllPlayers();
}

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
			<select name="team">
				<?php
				foreach ($teamlist as $team) {
					$selected = "";
					if ($filteredteam == $team) {
						$selected = "selected='selected'";
					}
					echo "<option value='$team' $selected>$team</option>";
				}
				?>
			</select>
			<input type="submit" value="FILTER" />
		</form>	
		<?php
		echo $playerlist->toHTMLTable();
		?>
	</body>
</html>
