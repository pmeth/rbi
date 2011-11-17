<?php

/**
 * Description of RBI3Rom
 *
 * @author Peter Meth
 */
class RBI3Rom extends Rom {

	//TODO: fill this in with stuff specific to RBI3 (the original, not the AndyB mod)
	protected $teamHexToChar;
	protected $teamCharToHex;
	protected $teams;

	function __construct($filename) {
		parent::__construct($filename);
		$this->generateTeamMappings();
		$this->generateTeams();
	}

	protected function generateTeams() {
		$start = $this->hexToDec("9e1d") * 2;
		$end = $this->hexToDec("9e5d") * 2;
		$numcharacters = $end - $start;

		//echo "$start - $end";
		$newstring = $this->getHexString($start, $numcharacters);
//		$chunked = chunk_split($newstring, 4, ",");
//		$teamshex = explode(",", $chunked);
//
//		// strip off last entry (it's blank)
//		unset($teamshex[count($teamshex) - 1]);

		$teamshex = str_split($newstring, 4);

		// remove teams 30 & 31, they're not used
		unset($teamshex[29]);
		unset($teamshex[30]);

		foreach ($teamshex as $teamnum => $teamhex) {
			$this->teams[$teamnum + 1] = $this->teamHexToChar[substr($teamhex, 0, 2)] . $this->teamHexToChar[substr($teamhex, 2, 2)];
		}
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

	protected function hexToDec($hex) {
		return base_convert($hex, 16, 10);
	}

}

?>
