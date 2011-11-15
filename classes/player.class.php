<?php

class Player {
	protected $offset;
	protected $name;
	protected $type;
	protected $filename = "../rbi2008.nes";
	protected $romBinary;
	protected $romHex;
	protected $playerHex;
	protected $nameHexToChar;
	protected $nameCharToHex;
	protected $teamHexToChar;
	protected $teamCharToHex;
	protected $teams;
	protected $converter;
	protected $lineupNumber;

	public function __construct($offset, BaseConvert $converter) {
		$this->converter = $converter;

		$this->offset = $offset;
		if ($offset >= $this->converter->hexToDec("16010") * 2 && $offset <= $this->converter->hexToDec("17f90") * 2) {
			$this->type = "hitter";
		} elseif ($offset >= $this->converter->hexToDec("18010") * 2 && $offset <= $this->converter->hexToDec("19f48") * 2) {
			$this->type = "pitcher";
		} else {
			throw new Exception('Offset does not map to a player.');
		}


		// start - this is a candidate to be separated into it's own class.  too lazy right now.
		$handle = fopen($this->filename, "r");
		$this->romBinary = fread($handle, filesize($this->filename));
		fclose($handle);
		$this->romHex = bin2hex($this->romBinary);
		// end - this is a candidate to be separated into it's own class.  too lazy right now.

		$this->generateNameMappings();
		$this->generateTeamMappings();
		$this->generateTeams();
		$this->generatePlayerHex();
		$this->generateLineupNumber();
		$this->generateName();
	}

	public function getOffset() {
		return $this->offset;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($newname) {
		$this->name = $newname;
	}

	public function getLineupNumber() {
		return $this->lineupNumber;
	}

	public function setLineupNumber($newlineupNumber) {
		$this->lineupNumber = $newlineupNumber;
	}

	public function getType() {
		return $this->type;
	}

	protected function generateTeamMappings() {
		$this->teamHexToChar = array(
			"2a" => "A",
			"2b" => "B",
			"2c" => "C",
			"2d" => "D",
			"2e" => "E",
			"2f" => "F",
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
			"3a" => "Q",
			"3b" => "R",
			"3c" => "S",
			"3d" => "T",
			"3e" => "U",
			"3f" => "V",
			"40" => "W",
			"41" => "X",
			"42" => "Y",
			"43" => "Z",
		);

		$this->teamCharToHex = array_flip($this->teamHexToChar);

	}

	protected function generateNameMappings() {
		$this->nameHexToChar = array(
			"0a" => "A",
			"0b" => "B",
			"0c" => "C",
			"0d" => "D",
			"0e" => "E",
			"0f" => "F",
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
			"1a" => "Q",
			"1b" => "R",
			"1c" => "S",
			"1d" => "T",
			"1e" => "U",
			"1f" => "V",
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
			"2a" => "c",
			"2b" => "d",
			"2c" => "e",
			"2d" => "f",
			"2e" => "g",
			"2f" => "h",
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
			"3a" => "s",
			"3b" => "t",
			"3c" => "u",
			"3d" => "v",
			"3e" => "w",
			"3f" => "x",
			"40" => "y",
			"41" => "z",
		);

		$this->nameCharToHex = array_flip($this->nameHexToChar);
	}

	protected function generateTeams() {
		$start = $this->converter->hexToDec("9e1d") * 2;
		$end = $this->converter->hexToDec("9e5d") * 2;
		$numcharacters = $end - $start;

		//echo "$start - $end";
		$newstring = substr($this->romHex, $start, $numcharacters);
		$chunked = chunk_split ($newstring, 4, ",");
		$teamshex = explode(",", $chunked);

		// strip off last entry (it's blank)
		unset($teamshex[count($teamshex) - 1]);

		// remove teams 30 & 31, they're not used
		unset($teamshex[29]);
		unset($teamshex[30]);

		foreach($teamshex as $teamnum => $teamhex) {
			$this->teams[$teamnum + 1] = $this->teamHexToChar[substr($teamhex, 0, 2)] . $this->teamHexToChar[substr($teamhex, 2, 2)];
		}

	}

	protected function generatePlayerHex() {
		$start = $this->offset;
		$numcharacters = 36;

		//echo "$start - $end";
		$this->playerHex = substr($this->romHex, $start, $numcharacters);
		//echo $this->playerHex;

	}

	protected function generateLineupNumber() {
		$this->lineupNumber = $this->converter->hexToDec(substr($this->playerHex, 0, 2));
	}

	protected function generateName() {
		$this->name = $this->nameHexToChar[substr($this->playerHex, 2, 2)];
		$this->name .= $this->nameHexToChar[substr($this->playerHex, 4, 2)];
		$this->name .= $this->nameHexToChar[substr($this->playerHex, 6, 2)];
		$this->name .= $this->nameHexToChar[substr($this->playerHex, 8, 2)];
		$this->name .= $this->nameHexToChar[substr($this->playerHex, 10, 2)];
		$this->name .= $this->nameHexToChar[substr($this->playerHex, 12, 2)];
		$this->name .= $this->nameHexToChar[substr($this->playerHex, 32, 2)];
		$this->name .= $this->nameHexToChar[substr($this->playerHex, 34, 2)];
	}
}

/*






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


*/