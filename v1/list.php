<?php
error_reporting(E_ALL & ~E_DEPRECATED);

$filename = "rbi_blaze.nes"; // hardcode for now
if (!empty($_REQUEST['file'])) {
	$filename = $_REQUEST['file'];
}

$message = "";
if(!empty($_GET['message'])) {
	$message = "<br /><font color='green'><b>$_GET[message]</b></font><br /><br />";
}
$downimage="<img border='0' src='../images/down.png'>&nbsp;";
$upimage="&nbsp;<img border='0' src='../images/up.png'>";

$searchid = "";
if(!empty($_GET['searchid'])) {
	$searchid = $_GET['searchid'];
}

//CODE BELOW ADDED TO REMEMBER ANY FILTERS ALREADY SET WHEN CLICKING ON THE ARROWS
$filterstring="";
$args="";
foreach ($_GET AS $key => $value) {
	if($key <> 'sortname' && $key <> 'sortorder' && $key <> 'message') {
		$filterstring.="&$key=$value";
	}
	if($key <> 'message') {
		$args.="&$key=$value";
	}
}
//END CODE TO REMEMBER FILTERS FOR ARROWS

// open rom for reading
$handle = fopen($filename, "r");
$original = fread($handle, filesize($filename));
fclose($handle);

$hexoriginal = bin2hex($original);


// START TEAM SECTION //
/*
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
$teamshex = split(",", $chunked);

//print_r($teamshex);
// strip off last entry (it's blank)
unset($teamshex[count($teamshex) - 1]);

// remove teams 30 & 31, they're not used
unset($teamshex[29]);
unset($teamshex[30]);

//print_r($teamshex);
foreach($teamshex as $teamnum => $teamhex) {
	$teams[$teamnum + 1] = $hextochar[substr($teamhex, 0, 2)] . $hextochar[substr($teamhex, 2, 2)];
}
*/
$teams = array (
  1 => 'AT',
  2 => 'CHC',
  3 => 'AZ',
  4 => 'BA',
  5 => 'CHW',
  6 => 'AN',
  7 => 'FL',
  8 => 'CI',
  9 => 'CO',
  10 => 'BO',
  11 => 'CL',
  12 => 'OK',
  13 => 'NYM',
  14 => 'HO',
  15 => 'LA',
  16 => 'NYY',
  17 => 'DT',
  18 => 'ST',
  19 => 'PH',
  20 => 'MW',
  21 => 'SD',
  22 => 'TB',
  23 => 'KC',
  24 => 'TX',
  25 => 'WA',
  26 => 'PI',
  27 => 'SF',
  28 => 'TO',
  29 => 'MN',
  32 => 'SL',
);
// END TEAM SECTION //
//var_export($teams);
//die;

// START - TEAM FILTER
$teamname = "";
if(!empty($_GET['teamname'])) {
	$teamname = $_GET['teamname'];
}

$teamfilter = "
	Team: <select name='teamname'>
		<option value=''>Any Team</option>
";

$sortedteams = array_flip($teams);
ksort($sortedteams);
foreach($sortedteams as $team=>$team_id) {
	$selected = "";
	if($teamname == $team) {
		$selected = "selected";
	}
	$teamfilter .= "<option $selected>$team</option>\n";
}
$teamfilter .= "
	</select>
";

// END - TEAM FILTER

// START - POSITION FILTER
$position_id = "";
if(!empty($_GET['position_id'])) {
	$position_id = $_GET['position_id'];
}

$positions = array(
	'I' => 'Infield',
	'O' => 'Outfield',
	'C' => 'Catcher',
	'P' => 'Pitcher',
);

$positionfilter = "
	Position: <select name='position_id'>
		<option value=''>Any Position</option>
";

foreach($positions as $key=>$position) {
	$selected = "";
	if($position_id == $key) {
		$selected = "selected";
	}
	$positionfilter .= "<option value='$key' $selected>$position</option>\n";
}
$positionfilter .= "
	</select>
";
// END - POSITION FILTER


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

$start = hexToDec("16010") * 2;
$end = hexToDec("17f90") * 2;
$numcharacters = $end - $start;

//echo "$start - $end";
$newstring = substr($hexoriginal, $start, $numcharacters);
$chunked = chunk_split ($newstring, 36, ",");
$playershex = split(",", $chunked);

// strip off last entry (it's blank)
unset($playershex[count($playershex) - 1]);
//print_r($players);


//CODE FOR SORT ARROWS
if(isset($_GET['sortname']) && $_GET['sortname'] <> '') {
	$sort=$_GET['sortname'];
	$order=$_GET['sortorder'];
} else {
	$sort="name";
	$order="ASC";
}
//END SORT ARROWS

$teamid = 0;
foreach($playershex as $id => $playerhex) {
	$offset = $start + 36 * $id;
//	$index = hexToDec(substr($playerhex, 0, 2));
	$index = $id % 14;
	//echo "index: $index<br>";
	if ($index == 0) {
		$teamid++;
		//echo "$teams[$teamid]\n";
	}
	if ($teamid == 30 || $teamid == 31) {
		continue;
	}


	$playername = $hextochar[substr($playerhex, 2, 2)];
	$playername .= $hextochar[substr($playerhex, 4, 2)];
	$playername .= $hextochar[substr($playerhex, 6, 2)];
	$playername .= $hextochar[substr($playerhex, 8, 2)];
	$playername .= $hextochar[substr($playerhex, 10, 2)];
	$playername .= $hextochar[substr($playerhex, 12, 2)];
	$playername .= $hextochar[substr($playerhex, 32, 2)];
	$playername .= $hextochar[substr($playerhex, 34, 2)];

//	echo "index: $index, player: $playername, teamid: $teamid, teamname: $teams[$teamid]<br>";
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
	$average = ".$average";

	$homeruns = hexToDec(substr($playerhex, 18, 2));

	$power = hexToDec(substr($playerhex, 24, 2) . substr($playerhex, 22, 2));

	$contact = hexToDec(substr($playerhex, 20, 2));

	$speed = hexToDec(substr($playerhex, 26, 2));


	$record = array (
		"id" => $id,
		"offset" => $offset,
		"team_id" => $teamid,
		"teamname" => $teams[$teamid],
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
	$hitters[] = $record;
	if (empty($record[$sort])) {
		$players[$record["name"] . "-" . $id] = $record;
	} elseif ($sort == "id") {
		$players[$id] = $record;
	} else {
		$players[$record[$sort] . "-" . $id] = $record;
	}
	//echo "$id,$teamid,$index,$playername,$pos,$bats,$average,$homeruns,$power,$contact,$speed\n";
}

//// START PITCHERS ////
// first, let's read the era tables
// the first table is 19d88 - 19f0f
$start = hexToDec("19d88") * 2;
$end = hexToDec("19e94") * 2;
$numcharacters = $end - $start;

//echo "$start - $end";
$newstring = substr($hexoriginal, $start, $numcharacters);
$chunked = chunk_split ($newstring, 2, ",");
$era1hex = split(",", $chunked);

// strip off last entry (it's blank)
unset($era1hex[count($era1hex) - 1]);
//print_r($era1hex);

// the second table starts at 19f10, and has half the number of characters as table 1
$start = hexToDec("19f48") * 2;
$numcharacters = round($numcharacters / 2);

//echo "$start - $end";
$newstring = substr($hexoriginal, $start, $numcharacters);
$chunked = chunk_split ($newstring, 1, ",");
$era2hex = split(",", $chunked);

// strip off last entry (it's blank)
unset($era2hex[count($era2hex) - 1]);
//print_r($era2hex);

// now we combine them together
foreach ($era1hex as $key => $value) {
	$eras[] = substr($value, 0, 1) . "." . substr($value, 1, 1) . $era2hex[$key];
}
//print_r($eras);
// now we should have a nice era reference table.  we will be using it in a moment

// now get all the pitcher info
$start = hexToDec("18010") * 2;
$end = hexToDec("19f48") * 2;
$numcharacters = $end - $start;

//echo "$start - $end";
$newstring = substr($hexoriginal, $start, $numcharacters);
$chunked = chunk_split ($newstring, 36, ",");
$playershex = split(",", $chunked);

// strip off last entry (it's blank)
unset($playershex[count($playershex) - 1]);
//print_r($playershex);

// blank lines look like 000000000000000000000000000000000000
$teamid = 0;
//echo "teamid,index,playername,era,throws,speed,curveleft,curveright,stamina,sink\n";

$idoffset = count($players);

foreach($playershex as $relativeid => $playerhex) {
	$offset = $start + 36 * $relativeid;

	if ($playerhex == "000000000000000000000000000000000000") {
		continue;
	}

	// skip over the sections with the era's
	if ($relativeid > 415 && $relativeid <= 433) {
//		echo "skipping $relativeid<br>";
		continue;
	}
//	echo "relative: $relativeid<br>";

	$index = hexToDec(substr($playerhex, 0, 2));
	if ($index == 14) {
		$teamid++;
		//echo "$teams[$teamid]\n";
	}

	if ($offset >= 212264 && $offset <= 212588) {
//		continue;
//		$teamid == 31;
	}

	if ($teamid == 30) {
		$teamid = 32;
	}

	$playername = $hextochar[substr($playerhex, 2, 2)];
	$playername .= $hextochar[substr($playerhex, 4, 2)];
	$playername .= $hextochar[substr($playerhex, 6, 2)];
	$playername .= $hextochar[substr($playerhex, 8, 2)];
	$playername .= $hextochar[substr($playerhex, 10, 2)];
	$playername .= $hextochar[substr($playerhex, 12, 2)];
	$playername .= $hextochar[substr($playerhex, 32, 2)];
	$playername .= $hextochar[substr($playerhex, 34, 2)];

//	echo "$playername, $teamid, $teams[$teamid] <br>";

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

	// from pitcher's perspective
	$curveleft = hexToDec(substr($playerhex, 24, 1));
	$curveright = hexToDec(substr($playerhex, 25, 1));

	$stamina = hexToDec(substr($playerhex, 26, 2));
	$sink = hexToDec(substr($playerhex, 14, 1));

	$record = array (
		"id" => $id,
		"offset" => $offset,
		"team_id" => $teamid,
		"teamname" => $teams[$teamid],
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

	//print_r($record);
	$pitchers[] = $record;
	if (empty($record[$sort])) {
		$players[$record["name"] . "-" . $id] = $record;
	} elseif ($sort == "id") {
		$players[$id] = $record;
	} else {
		$players[$record[$sort] . "-" . $id] = $record;
	}
	//echo "$teamid,$index,$playername,$era,$throws,$speed,$curveleft,$curveright,$stamina,$sink\n";

	$id++;
}

//print_r($players);
//exit;
//// END PITCHERS ////
// END PLAYER SECTION //

// alright, now let's take a break and look at what we've got
// 1. an array of teams - $teams
// 2. an array of players - $players (both hitters and pitchers)
// 3. an array of hitters - $hitters
// 4. an array of pitchers - $pitchers
// 5. an array of era's - $eras
// so basically, we have our relational database, all set to go

$alpha = false;
if(
	$sort == "name" ||
	$sort == "teamname" ||
	$sort == "position" ||
	$sort == "hand"
) {
	$alpha = true;
}

if ($order == "ASC") {
	if($alpha) {
		ksort($players);
	} else {
		ksort($players, SORT_NUMERIC);
	}
} else {
	if($alpha) {
		krsort($players);
	} else {
		krsort($players, SORT_NUMERIC);
	}
}

$total = count($players);
$output="
<html>
<head>
	<title>RBI 3 Editor</title>
	<link rel='stylesheet' type='text/css' href='../css/list.css' />
</head>
<body>
<div align='center'>
	<h2>RBI 3 Editor</h2>
	<a href='$filename'>Download ROM</a><br><br>
	<form action='$_SERVER[PHP_SELF]' method='GET'>
		<table border=1 bordercolor='#666666' style='border-collapse: collapse;' cellpadding=3 cellspacing=0>
			<tr>
				<td bgcolor='#E1E1E1' align='center' colspan=6><b>Search by Player Name</b>
					<input type='text' name='searchid' style='width:220px' value='$searchid' />
				</td>
			</tr>
			<tr>
				<td>$teamfilter $positionfilter</td>
			</tr>
			<tr>
				<td align='center' bgcolor='#E1E1E1' colspan='60'>
					<input class='submitbutton' type='submit' value='SHOW SELECTION'>
				</td>
			</tr>
		</table>
	</form>
	$message
	<b>Total Players: $total</b><br /><br />
	<table border=1 bordercolor='#D4D0C8' cellpadding=3 cellspacing=0 style='border-collapse: collapse;'>
		<tr>
			<th>
				Offset
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=index&sortorder=DESC$filterstring'>$downimage</a> Order
				<a href='$_SERVER[SCRIPT_NAME]?sortname=index&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=name&sortorder=DESC$filterstring'>$downimage</a> Player Name
				<a href='$_SERVER[SCRIPT_NAME]?sortname=name&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=teamname&sortorder=DESC$filterstring'>$downimage</a> Team
				<a href='$_SERVER[SCRIPT_NAME]?sortname=teamname&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=position&sortorder=DESC$filterstring'>$downimage</a> Pos
				<a href='$_SERVER[SCRIPT_NAME]?sortname=position&sortorder=ASC$filterstring'>$upimage</a>
				
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=hand&sortorder=DESC$filterstring'>$downimage</a> Hand
				<a href='$_SERVER[SCRIPT_NAME]?sortname=hand&sortorder=ASC$filterstring'>$upimage</a>
				
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=average&sortorder=DESC$filterstring'>$downimage</a> AVG
				<a href='$_SERVER[SCRIPT_NAME]?sortname=average&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=homeruns&sortorder=DESC$filterstring'>$downimage</a> HR
				<a href='$_SERVER[SCRIPT_NAME]?sortname=homeruns&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=power&sortorder=DESC$filterstring'>$downimage</a> PWR
				<a href='$_SERVER[SCRIPT_NAME]?sortname=power&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=contact&sortorder=DESC$filterstring'>$downimage</a> CONT
				<a href='$_SERVER[SCRIPT_NAME]?sortname=contact&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=speed&sortorder=DESC$filterstring'>$downimage</a> SPD
				<a href='$_SERVER[SCRIPT_NAME]?sortname=speed&sortorder=ASC$filterstring'>$upimage</a>
			</th>

			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=era&sortorder=DESC$filterstring'>$downimage</a> ERA
				<a href='$_SERVER[SCRIPT_NAME]?sortname=era&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=sinkerspeed&sortorder=DESC$filterstring'>$downimage</a> SK VEL
				<a href='$_SERVER[SCRIPT_NAME]?sortname=sinkerspeed&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=curvespeed&sortorder=DESC$filterstring'>$downimage</a> CV VEL
				<a href='$_SERVER[SCRIPT_NAME]?sortname=curvespeed&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=fastballspeed&sortorder=DESC$filterstring'>$downimage</a> FB VEL
				<a href='$_SERVER[SCRIPT_NAME]?sortname=fastballspeed&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=curveleft&sortorder=DESC$filterstring'>$downimage</a> CV L
				<a href='$_SERVER[SCRIPT_NAME]?sortname=curveleft&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=curveright&sortorder=DESC$filterstring'>$downimage</a> CV R
				<a href='$_SERVER[SCRIPT_NAME]?sortname=curveright&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=stamina&sortorder=DESC$filterstring'>$downimage</a> STM
				<a href='$_SERVER[SCRIPT_NAME]?sortname=stamina&sortorder=ASC$filterstring'>$upimage</a>
			</th>
			<th>
				<a href='$_SERVER[SCRIPT_NAME]?sortname=sink&sortorder=DESC$filterstring'>$downimage</a> SINK
				<a href='$_SERVER[SCRIPT_NAME]?sortname=sink&sortorder=ASC$filterstring'>$upimage</a>
			</th>
		</tr>
";


foreach ($players as $player) {
	$player['name'] = trim($player['name']);

	if(empty($searchid)) {
		// START - APPLY TEAM FILTER
		if(!empty($teamname) && $player['teamname'] != $teamname) {
			continue;
		}
		// END - APPLY TEAM FILTER

		// START - APPLY POSITION FILTER
		if(!empty($position_id) && $player['position'] != $position_id) {
			continue;
		}
		// END - APPLY POSITION FILTER
	} else {
		// START - APPLY SEARCH OPTION
		if(stripos($player['name'], $searchid) === false) {
			continue;
		}
		// END - APPLY SEARCH OPTION
	}


	if ($player["position"] == "P") {
		$player["average"] = "";
	} else {
		$player["era"] = "";
	}

	$hexoffset = base_convert($player["offset"] / 2, 10, 16) ;

/*
when we're ready, add this to the <tr>
*/

 
		

	$output .= "
		<tr
			onmouseover='
				this.style.cursor=\"hand\";
				this.style.background=\"#EFEFE8\";
			'
			onmouseout='
				this.style.cursor=\"auto\";
				this.style.background=\"white\";
			'
			onclick='window.location=\"editplayer.php?offset=$player[offset]&file=$filename&$args\"'
		>
			<td>$player[offset]</td>
			<td>$player[index]</td>
			<td>$player[name]</td>
			<td>$player[teamname]</td>
			<td>$player[position]</td>
			<td>$player[hand]</td>
	";

	if ($player["position"] == "P") {
		$output .= "
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>$player[era]</td>
				<td>$player[sinkerspeed]</td>
				<td>$player[curvespeed]</td>
				<td>$player[fastballspeed]</td>
				<td>$player[curveleft]</td>
				<td>$player[curveright]</td>
				<td>$player[stamina]</td>
				<td>$player[sink]</td>
			</tr>
		";
	} else {
		$output .= "
				<td>$player[average]</td>
				<td>$player[homeruns]</td>
				<td>$player[power]</td>
				<td>$player[contact]</td>
				<td>$player[speed]</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		";
	}
}
$output.="</table></body></html>";

echo $output;

function hexToDec($hex) {
	return base_convert($hex, 16, 10);
}
?>