<?php

if (empty($_REQUEST['file'])) {
	die("No file specified");
}
$filename = $_REQUEST['file'];

//CODE BELOW ADDED TO REMEMBER ANY FILTERS
$args="";
foreach ($_GET AS $key => $value) {
	if($key <> 'offset') {
		$args.="&$key=$value";
	}
}
//END CODE TO REMEMBER FILTERS


//echo "	<a href='list.php?file=$filename'>BACK TO LIST</a><br>
//";
//echo "<pre>\n";
//print_r($_POST);
//echo "</pre>\n";

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

// open rom for reading
$handle = fopen($filename, "r");
$original = fread($handle, filesize($filename));
fclose($handle);

$hexoriginal = bin2hex($original);


$start = $_POST["offset"] / 2;
$numcharacters = 18;
$replacement = substr($original, $start, $numcharacters);

$playername_first6 = "";
$playername_last2 = "";
/*
Array
(
    [playername] => Hill
    [pos] => O
    [bats] => S
    [average] => 265
    [homeruns] => 3
    [power] => 750
    [contact] => 19
    [speed] => 138
    [offset] => 192568
    [playertype] => hitter
)


*/

//echo "$playername<br>";
for($i = 0; $i < 8; $i++) {
	// str_pad will put a space if there is no letter in that spot
	$letter = str_pad(substr($_POST["playername"], $i, 1), 1);
	if (empty($chartohex[$letter])) {
		$letter = " ";
	}
	if ($i < 6) {
		$playername_first6 .= hexToChar($chartohex[$letter]);
	} else {
		$playername_last2 .= hexToChar($chartohex[$letter]);
	}
}
$replacement = substr_replace($replacement, $playername_first6, 1, 6);
$replacement = substr_replace($replacement, $playername_last2, 16, 2);

//die( "$playername_first6<br>$playername_last2<br>");
if ($_POST["playertype"] == "hitter") {
	$lefty = 0;
	$switchhitter = 0;
	switch($_POST["bats"]) {
		case "L":
			$lefty = 1;
			break;
		case "S":
			$switchhitter = 1;
			break;
	}

	switch($_POST["pos"]) {
		case "I":
			$position = 1;
			break;
		case "O":
			$position = 2;
			break;
		case "C":
		default:
			$position = 0;
			break;
	}

	$average = $_POST["average"] - 111;
	$homeruns = $_POST["homeruns"];
	$power1 = floor($_POST["power"] / 256);
	$power2 = $_POST["power"] % 256;
	$contact = $_POST["contact"];
	$speed = $_POST["speed"];

	$replacement = substr_replace($replacement, chr($lefty), 7, 1);
	$replacement = substr_replace($replacement, chr($switchhitter), 15, 1);
	$replacement = substr_replace($replacement, chr($position), 14, 1);
	$replacement = substr_replace($replacement, chr($average), 8, 1);
	$replacement = substr_replace($replacement, chr($homeruns), 9, 1);
	$replacement = substr_replace($replacement, chr($contact), 10, 1);
	$replacement = substr_replace($replacement, chr($speed), 13, 1);
	$replacement = substr_replace($replacement, chr($power1), 12, 1);
	$replacement = substr_replace($replacement, chr($power2), 11, 1);
} elseif ($_POST["playertype"] == "pitcher") { // pitchers

	//// START ERA SECTION ////
	// first, let's read the era tables
	// the first table is 19d88 - 19e94
	$era1start = hexToDec("19d88") * 2;
	$era1end = hexToDec("19e94") * 2;
	$numcharacters = $era1end - $era1start;

	//echo "$start - $end";
	$newstring = substr($hexoriginal, $era1start, $numcharacters);
	$chunked = chunk_split ($newstring, 2, ",");
	$era1hex = explode(",", $chunked);

	// strip off last entry (it's blank)
	unset($era1hex[count($era1hex) - 1]);
	//print_r($era1hex);

	// the second table starts at 19f10, and has half the number of characters as table 1
	$era2start = hexToDec("19f48") * 2;
	$numcharacters = round($numcharacters / 2);

	//echo "$start - $end";
	$newstring = substr($hexoriginal, $era2start, $numcharacters);
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
	//exit;
	// now we should have a nice era reference table.  we will be using it in a moment

	// check if era is in era table, if so, set the value, else put it into the era table and set the value
	if (array_search($_POST["era"], $eras)) {
		$eraindex = array_search($_POST["era"], $eras);
	} else {
		// find second occurrence of 0.00.  first occurrence is the real 0.00
		$erakeys = array_keys($eras, "0.00");
		$eraindex = $erakeys[1];

		// insert era into 2 tables
		// first table (first 2 digits, not including the '.')
		$neweravalue = chr(hexToDec(substr($_POST["era"],0,1) . substr($_POST["era"],2,1)));
		$original = substr_replace($original, $neweravalue, $era1start / 2 + $eraindex, 1);
//		die(bin2hex($neweravalue));
		// second table (just last digit)
		if ($eraindex % 2 == 0) { // even number of elements, which means we can add our new number * 10
			$neweravalue = chr(hexToDec(substr($_POST["era"],3,1) . "0"));
		} else { // odd number of elements, now we have to work - last digit *10 + new digit
			// find last digit before our write index
			$existinghex = substr($hexoriginal, $era2start + $eraindex - 1, 1);
			$neweravalue = chr(hexToDec($existinghex . substr($_POST["era"],3,1)));
		}
		$original = substr_replace($original, $neweravalue, $era2start / 2 + floor($eraindex / 2), 1);
	}
	$replacement = substr_replace($replacement, chr($eraindex), 8, 1);
	//// END ERA SECTION ////


	$throws_post = $_POST["throws"];
	switch ($throws_post) {
		case "R":
			$throws = 0;
			break;
		case "L":
			$throws = 1;
			break;
		case "SR":
			$throws = 2;
			break;
		case "SL":
			$throws = 3;
			break;
	}

	$sinkerspeed = $_POST["sinkerspeed"];
	$curvespeed = $_POST["curvespeed"];
	$fastballspeed = $_POST["fastballspeed"];
	$curve = $_POST["curveleft"] * 16 + $_POST["curveright"];
	$stamina = $_POST["stamina"];
	$sink = $_POST["sink"];

	$replacement = substr_replace($replacement, chr($throws), 7, 1);
	$replacement = substr_replace($replacement, chr($sinkerspeed), 9, 1);
	$replacement = substr_replace($replacement, chr($curvespeed), 10, 1);
	$replacement = substr_replace($replacement, chr($fastballspeed), 11, 1);
	$replacement = substr_replace($replacement, chr($curve), 12, 1);
	$replacement = substr_replace($replacement, chr($stamina), 13, 1);
	$replacement = substr_replace($replacement, chr($sink), 15, 1);

/*
Array
(
    [playername] => Moyer
    [throws] => L
    [era] => 6.91
    [speed] => 194
    [curveleft] => 4
    [curveright] => 5
    [stamina] => 15
    [sink] => 138
    [offset] => 209564
    [playertype] => pitcher
)
*/
} else {
	die("Invalid Player Type");
}

//echo "replacement: " . bin2hex($replacement);

$newstring = substr_replace($original, $replacement, $_POST["offset"] / 2, 18);
//echo $original;

// open rom for writing
$handle = fopen($filename, "w");
$original = fwrite($handle, $newstring);
fclose($handle);

header("Location: list.php?message=$_POST[playername] successfully updated&$args");

function hexToChar($hexvalue) {
	$binaryvalue = base_convert($hexvalue, 16, 10);
	return chr($binaryvalue);
}

function hexToDec($hex) {
	return base_convert($hex, 16, 10);
}

