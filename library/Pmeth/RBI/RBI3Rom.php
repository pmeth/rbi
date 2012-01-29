<?php

/**
 * Description of RBI3Rom
 *
 * @author Peter Meth
 */
 namespace Pmeth\RBI;
class RBI3Rom extends Rom {

	protected $teamHexToChar;
	protected $teamCharToHex;
	protected $nameHexToChar;
	protected $nameCharToHex;
	protected $hexOffsets;
	protected $offsets;
	protected $eraTable;
	protected $allPlayersCollection;
	protected $maxPlayerNameLength;

	function __construct($filename) {
		parent::__construct($filename);

		// technically we don't really need these as hex.
		// it just makes it easier to read if you're using a hex editor
		$this->hexOffsets = array(
			 'hitterstart' => '16010',
			 'hitterend' => '17f90',
			 'pitcherstart' => '18010',
			 'pitcherend' => '19f48',
			 'teamstart' => '9e1d',
			 'teamend' => '9e5d',
			 'era1start' => '19d88',
			 'era1end' => '19e88',
			 'era2start' => '19f48',
		);

		$this->offsets = array();
		foreach ($this->hexOffsets as $key => $value) {
			$this->offsets[$key] = $this->offsetHexToDec($value);
		}

		$this->maxPlayerNameLength = 8;
		$this->generateTeamMappings();
		$this->generateNameMappings();
		$this->generateEraTable();
	}

	public function getHitterStart() {
		return $this->offsets['hitterstart'];
	}

	public function getHitterEnd() {
		return $this->offsets['hitterend'];
	}

	public function getTeamStart() {
		return $this->offsets['teamstart'];
	}
	
	public function getTeamEnd() {
		return $this->offsets['teamend'];
	}

	public function getTeamHexToChar() {
		return $this->teamHexToChar;
	}

	public function getTeamCharToHex() {
		return $this->teamCharToHex;
	}

	public function getNameHexToChar() {
		return $this->nameHexToChar;
	}

	public function getNameCharToHex() {
		return $this->nameCharToHex;
	}

	public function getEraTable() {
		return $this->eraTable;
	}

	public function getMaxPlayerNameLength() {
		return $this->maxPlayerNameLength;
	}

		public function setEratable($eratable) {
		$this->eraTable = $eratable;

		//write the new eratable to the binary
		$era1hex = '';
		$era2hex = '';
		foreach ($eratable as $key => $era) {
			$era1hex .= substr($era, 0, 1) . substr($era, 2, 1);
			$era2hex .= substr($era, 3, 1);
		}

		$this->setHexString($era1hex, $this->offsets['era1start']);
		$this->setHexString($era2hex, $this->offsets['era2start']);
		//TODO: have a way to reclaim unused era table indexes
	}

	public function getPitcherStart() {
		return $this->offsets['pitcherstart'];
	}

	public function getPitcherEnd() {
		return $this->offsets['pitcherend'];
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
			 "00" => " ",
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

	protected function generateEraTable() {
		$this->eraTable = array();

		// first, let's read the era tables
		// the first table is 19d88 - 19e94
		$start = $this->offsets['era1start'];
		$end = $this->offsets['era1end'];
		$numcharacters = $end - $start;

		$newstring = $this->getHexString($start, $numcharacters);
		$era1hex = str_split($newstring, 2);

		// the second table starts at 19f10, and has half the number of characters as table 1
		$start = $this->offsets['era2start'];
		$numcharacters = round($numcharacters / 2);

		$newstring = $this->getHexString($start, $numcharacters);
		$era2hex = str_split($newstring, 1);

		// now we combine them together
		foreach ($era1hex as $key => $value) {
			$this->eraTable[] = substr($value, 0, 1) . "." . substr($value, 1, 1) . $era2hex[$key];
		}
	}

	public function hexToDec($hex) {
		return base_convert($hex, 16, 10);
	}

	public function offsetHexToDec($hex) {
		return $this->hexToDec($hex) * 2;
	}

}

