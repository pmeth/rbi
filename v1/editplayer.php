<?php
error_reporting(E_ALL & ~E_DEPRECATED);

if (empty($_REQUEST['file'])) {
	die("No file specified");
}
$filename = $_REQUEST['file'];


if (empty($_GET["offset"])) {
	die("No offset specified");
}

// now let's figure out if this is a hitter or pitcher
if ($_GET["offset"] >= hexToDec("16010") * 2 && $_GET["offset"] <= hexToDec("17f90") * 2) {
	$playertype = "hitter";
} elseif ($_GET["offset"] >= hexToDec("18010") * 2 && $_GET["offset"] <= hexToDec("19f48") * 2) {
	$playertype = "pitcher";
} else {
	die("Sorry, that offset is not allowed");
}


//CODE BELOW ADDED TO REMEMBER ANY FILTERS
$args="";
foreach ($_GET AS $key => $value) {
	if($key <> 'offset') {
		$args.="&$key=$value";
	}
}
//END CODE TO REMEMBER FILTERS


// open rom for reading
$handle = fopen($filename, "r");
$original = fread($handle, filesize($filename));
fclose($handle);

$hexoriginal = bin2hex($original);


// START TEAM SECTION //
$hextochar = array(
	"2A" => "A",
	"2B" => "B",
	"2C" => "C",
	"2D" => "D",
	"2E" => "E",
	"2F" => "F",
	"30" => "G",
	"31" => "H",
	"32" => "I",
	"33" => "J",
	"34" => "K",
	"35" => "L",
	"36" => "M",
	"37" => "N",
	"38" => "O",
	"39" => "P",
	"3A" => "Q",
	"3B" => "R",
	"3C" => "S",
	"3D" => "T",
	"3E" => "U",
	"3F" => "V",
	"40" => "W",
	"41" => "X",
	"42" => "Y",
	"43" => "Z",
);

foreach ($hextochar as $key => $value) {
//	$decimal = hexToDec($key);

	$hextochar[strtolower($key)] = $value;
	$chartohex[$value] = strtolower($key);
}

// now we have 2 arrays.
// $character maps hex to char
// $hexcode maps char to hex

$start = hexToDec("9e1d") * 2;
$end = hexToDec("9e5d") * 2;
$numcharacters = $end - $start;

//echo "$start - $end";
$newstring = substr($hexoriginal, $start, $numcharacters);
$chunked = chunk_split ($newstring, 4, ",");
$teamshex = explode(",", $chunked);

// strip off last entry (it's blank)
unset($teamshex[count($teamshex) - 1]);

// remove teams 30 & 31, they're not used
unset($teamshex[29]);
unset($teamshex[30]);

foreach($teamshex as $teamnum => $teamhex) {
	$teams[$teamnum + 1] = $hextochar[substr($teamhex, 0, 2)] . $hextochar[substr($teamhex, 2, 2)];
}
//print_r($teams);
//die;
// END TEAM SECTION //


// START PLAYER SECTION //
$hextochar = array(
	"0A" => "A",
	"0B" => "B",
	"0C" => "C",
	"0D" => "D",
	"0E" => "E",
	"0F" => "F",
	"10" => "G",
	"11" => "H",
	"12" => "I",
	"13" => "J",
	"14" => "K",
	"15" => "L",
	"16" => "M",
	"17" => "N",
	"18" => "O",
	"19" => "P",
	"1A" => "Q",
	"1B" => "R",
	"1C" => "S",
	"1D" => "T",
	"1E" => "U",
	"1F" => "V",
	"20" => "W",
	"21" => "X",
	"22" => "Y",
	"23" => "Z",
	"24" => " ",
	"25" => ".",
	"26" => "(",
	"27" => "'",
	"28" => "a",
	"29" => "b",
	"2A" => "c",
	"2B" => "d",
	"2C" => "e",
	"2D" => "f",
	"2E" => "g",
	"2F" => "h",
	"30" => "i",
	"31" => "j",
	"32" => "k",
	"33" => "l",
	"34" => "m",
	"35" => "n",
	"36" => "o",
	"37" => "p",
	"38" => "q",
	"39" => "r",
	"3A" => "s",
	"3B" => "t",
	"3C" => "u",
	"3D" => "v",
	"3E" => "w",
	"3F" => "x",
	"40" => "y",
	"41" => "z",
);

foreach ($hextochar as $key => $value) {
//	$decimal = hexToDec($key);

	$hextochar[strtolower($key)] = $value;
	$chartohex[$value] = strtolower($key);
}

// now we have 2 arrays.
// $character maps hex to char
// $hexcode maps char to hex

$start = $_GET["offset"];
$numcharacters = 36;

//echo "$start - $end";
$playerhex = substr($hexoriginal, $start, $numcharacters);
//echo $playerhex;
$index = hexToDec(substr($playerhex, 0, 2));

$playername = $hextochar[substr($playerhex, 2, 2)];
$playername .= $hextochar[substr($playerhex, 4, 2)];
$playername .= $hextochar[substr($playerhex, 6, 2)];
$playername .= $hextochar[substr($playerhex, 8, 2)];
$playername .= $hextochar[substr($playerhex, 10, 2)];
$playername .= $hextochar[substr($playerhex, 12, 2)];
$playername .= $hextochar[substr($playerhex, 32, 2)];
$playername .= $hextochar[substr($playerhex, 34, 2)];

if ($playertype == "hitter") {
	if (substr($playerhex, 30, 2) == "01") {
		$bats = "S";
	} elseif (substr($playerhex, 14, 2) == "00") {
		$bats = "R";
	} else {
		$bats = "L";
	}

	if (substr($playerhex, 28, 2) == "00") {
		$pos = "C";
	} elseif (substr($playerhex, 28, 2) == "01") {
		$pos = "I";
	} else {
		$pos = "O";
	}

	$average = 111 + hexToDec(substr($playerhex, 16, 2));

	$homeruns = hexToDec(substr($playerhex, 18, 2));

	$power = hexToDec(substr($playerhex, 24, 2) . substr($playerhex, 22, 2));

	$contact = hexToDec(substr($playerhex, 20, 2));

	$speed = hexToDec(substr($playerhex, 26, 2));


	$record = array (
		"index" => $index,
		"name" => $playername,
		"position" => $pos,
		"hand" => $bats,
		"average" => $average,
		"homeruns" => $homeruns,
		"power" => $power,
		"contact" => $contact,
		"speed" => $speed
	);

	$playerdata = "
		<tr><th align=right width=50%>INDEX</th><td>$index</td></tr>
		<tr><th align=right width=50%>PLAYERNAME</th><td><input type='text' name='playername' value='$playername'></td></tr>
		<tr><th align=right width=50%>POS</th><td><input type='text' name='pos' value='$pos'></td></tr>
		<tr><th align=right width=50%>BATS</th><td><input type='text' name='bats' value='$bats'></td></tr>
		<tr><th align=right width=50%>AVERAGE</th><td><input type='text' name='average' value='$average'></td></tr>
		<tr><th align=right width=50%>HOMERUNS</th><td><input type='text' name='homeruns' value='$homeruns'></td></tr>
		<tr><th align=right width=50%>POWER</th><td><input type='text' name='power' value='$power'></td></tr>
		<tr><th align=right width=50%>CONTACT</th><td><input type='text' name='contact' value='$contact'></td></tr>
		<tr><th align=right width=50%>SPEED</th><td><input type='text' name='speed' value='$speed'></td></tr>
	";
} elseif ($playertype == "pitcher") {

	//// START PITCHERS ////
	// first, let's read the era tables
	// the first table is 19d88 - 19e94
	$start = hexToDec("19d88") * 2;
	$end = hexToDec("19e94") * 2;
	$numcharacters = $end - $start;

	//echo "$start - $end";
	$newstring = substr($hexoriginal, $start, $numcharacters);
	$chunked = chunk_split ($newstring, 2, ",");
	$era1hex = explode(",", $chunked);

	// strip off last entry (it's blank)
	unset($era1hex[count($era1hex) - 1]);
	//print_r($era1hex);

	// the second table starts at 19f10, and has half the number of characters as table 1
	$start = hexToDec("19f48") * 2;
	$numcharacters = round($numcharacters / 2);

	//echo "$start - $end";
	$newstring = substr($hexoriginal, $start, $numcharacters);
	$chunked = chunk_split ($newstring, 1, ",");
	$era2hex = explode(",", $chunked);

	// strip off last entry (it's blank)
	unset($era2hex[count($era2hex) - 1]);
	//print_r($era2hex);

	// now we combine them together
	foreach ($era1hex as $key => $value) {
		$eras[] = substr($value, 0, 1) . "." . substr($value, 1, 1) . $era2hex[$key];
	}
	//print_r($eras);
	// now we should have a nice era reference table.  we will be using it in a moment

	switch (substr($playerhex, 15, 1)) {
		case 0:
			$throws = "R";
			break;
		case 1:
			$throws = "L";
			break;
		case 2:
			$throws = "SR";
			break;
		case 3:
			$throws = "SL";
			break;
	}

	$eraindex = hexToDec(substr($playerhex, 16, 2));
	$era = $eras[$eraindex];

	$sinkerspeed = hexToDec(substr($playerhex, 18, 2));
	$curvespeed = hexToDec(substr($playerhex, 20, 2));
	$fastballspeed = hexToDec(substr($playerhex, 22, 2));

//	$speed = hexToDec(substr($playerhex, 22, 2));

	// from pitcher's perspective
	$curveleft = hexToDec(substr($playerhex, 24, 1));
	$curveright = hexToDec(substr($playerhex, 25, 1));

	$stamina = hexToDec(substr($playerhex, 26, 2));
	$sink = hexToDec(substr($playerhex, 30, 2));

	$record = array (
		"index" => $index,
		"name" => $playername,
		"hand" => $throws,
		"position" => "P",
		"era" => $era,
		"sinkerspeed" => $sinkerspeed,
		"curvespeed" => $curvespeed,
		"fastballspeed" => $fastballspeed,
		"curveleft" => $curveleft,
		"curveright" => $curveright,
		"stamina" => $stamina,
		"sink" => $sink
	);
	$playerdata = "
		<tr><th align=right width=50%>INDEX</th><td>$index</td></tr>
		<tr><th align=right width=50%>PLAYERNAME</th><td><input type='text' name='playername' value='$playername'></td></tr>
		<tr><th align=right width=50%>POS</th><td>P</td></tr>
		<tr><th align=right width=50%>THROWS</th><td><input type='text' name='throws' value='$throws'></td></tr>
		<tr><th align=right width=50%>ERA</th><td><input type='text' name='era' value='$era'></td></tr>
		<tr><th align=right width=50%>SINKER SPEED</th><td><input type='text' name='sinkerspeed' value='$sinkerspeed'></td></tr>
		<tr><th align=right width=50%>CURVE SPEED</th><td><input type='text' name='curvespeed' value='$curvespeed'></td></tr>
		<tr><th align=right width=50%>FASTBALL SPEED</th><td><input type='text' name='fastballspeed' value='$fastballspeed'></td></tr>
		<tr><th align=right width=50%>CURVE LEFT</th><td><input type='text' name='curveleft' value='$curveleft'></td></tr>
		<tr><th align=right width=50%>CURVE RIGHT</th><td><input type='text' name='curveright' value='$curveright'></td></tr>
		<tr><th align=right width=50%>STAMINA</th><td><input type='text' name='stamina' value='$stamina'></td></tr>
		<tr><th align=right width=50%>SINK</th><td><input type='text' name='sink' value='$sink'></td></tr>
	";
}

$output="
<html>
<head>
	<title>RBI 3 Editor</title>
	<link rel='stylesheet' type='text/css' href='css/list.css' />
</head>
<body>
<div align='center'>
	<h2>RBI 3 Editor</h2>
	<a href='list.php?file=$filename'>BACK TO LIST</a><br>
	<br>
	<form action='playerhandler.php?$args' method='post'>
	<table width='300' align=center border=1 cellpadding=3 cellspacing=0>
		$playerdata
		<tr><td align='center' colspan='2'><input type='submit' value='Save Changes'></td></tr>
	</table>
	<input type='hidden' name='offset' value='$_GET[offset]'>
	<input type='hidden' name='playertype' value='$playertype'>
	<input type='hidden' name='file' value='$filename'>

	</form>
</div>
</body>
</html>
";

echo $output;

function hexToDec($hex) {
	return base_convert($hex, 16, 10);
}

