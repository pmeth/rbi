<?php
namespace Pmeth\RBI;
include('bootstrap.php');

$teammapper = new TeamROMMapper($myrom);

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
		<?php
		echo $teamlist->toHTMLTable();
		?>
	</body>
</html>
