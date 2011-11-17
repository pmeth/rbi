<?php

/**
 * Description of RBI3Rom
 *
 * @author Peter Meth
 */
class RBI3Rom extends Rom {

	protected $teamHexToChar;
	protected $teamCharToHex;
	protected $teams;
	protected $nameHexToChar;
	protected $nameCharToHex;
	protected $hitterStartHex = "16010";
	protected $hitterEndHex = "17f90";
	protected $pitcherStartHex = "18010";
	protected $pitcherEndHex = "19f48";

	function __construct($filename) {
		parent::__construct($filename);
		$this->generateTeamMappings();
		$this->generateTeams();
		$this->generateNameMappings();
	}

	public function getHitterStart() {
		return $this->hexToDec($this->hitterStartHex) * 2;
		;
	}

	public function getHitterEnd() {
		return $this->hexToDec($this->hitterEndHex) * 2;
		;
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

	public function getPitcherStart() {
		return $this->hexToDec($this->pitcherStartHex) * 2;
	}

	public function getPitcherEnd() {
		return $this->hexToDec($this->pitcherEndHex) * 2;
	}

	public function getAllPlayers() {
		$players = new PlayerCollection();
		foreach(array('hitter', 'pitcher') as $playertype) {
			switch($playertype) {
				case 'hitter':
					$start = $this->getHitterStart();
					$end = $this->getHitterEnd();
					//$onEachTeam = 14;
					break;
				case 'pitcher':
					$start = $this->getPitcherStart();
					$end = $this->getPitcherEnd();
					//$onEachTeam = 10;
					break;
				default:
					throw new Exception('invalid playertype');
			}
			for ($offset = $start; $offset < $end; $offset += 36) {

				// TODO: make this less ugly
				$playeroffset = ($offset - $start) / 36;
				//$teamoffset = floor($playeroffset / $onEachTeam);
				$teamoffset = floor($playeroffset / 14);

				if ($teamoffset == 29 || $teamoffset == 30) {
					continue;
				}
				$player = new Player($this, $offset);
				if(!$player->valid()) {
					continue;
				}
				$player->setTeam($this->teams[$teamoffset + 1]);
				$players->addPlayer($player);
			}
		}
		return $players;
	}

	protected function generateTeams() {
		// TODO: turn the hardcoded values into variables
		$start = $this->hexToDec("9e1d") * 2;
		$end = $this->hexToDec("9e5d") * 2;
		$numcharacters = $end - $start;

		$newstring = $this->getHexString($start, $numcharacters);

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

	protected function hexToDec($hex) {
		return base_convert($hex, 16, 10);
	}

}

?>
