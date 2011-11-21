<?php

class Player {

	protected $offset;
	protected $name;
	protected $type;
	protected $playerHex;
	protected $converter;
	protected $lineupNumber;
	protected $rom;
	protected $team;
	protected $error;
	protected $isValid;
	protected $acceptAbnormal;

	public function __construct(RBI3Rom $rom, $offset) {
		$this->isValid = true;
		$this->acceptAbnormal = false;
		$this->error = '';
		$this->rom = $rom;
		$this->offset = $offset;
		if ($offset >= $rom->getHitterStart() && $offset <= $rom->getHitterEnd()) {
			$this->type = "hitter";
		} elseif ($offset >= $rom->getPitcherStart() && $offset <= $rom->getPitcherEnd()) {
			$this->type = "pitcher";
		} else {
			$this->isValid = false;
			$this->error .= '<br>Offset does not map to a player';
		}

		$this->generatePlayerHex();
		$this->generateLineupNumber();
		$this->generateName();
		$this->generateTeam();
	}

	public function valid() {
		return $this->isValid && $this->playerHex != "000000000000000000000000000000000000";
	}

	public function getAcceptAbnormal() {
		return $this->acceptAbnormal;
	}

	public function setAcceptAbnormal($acceptAbnormal) {
		$this->acceptAbnormal = $acceptAbnormal;
	}

	public function getError() {
		return $this->error;
	}

	public function getTeam() {
		return $this->team;
	}

	public function getName() {
		return $this->name;
	}

	public function getOffset() {
		return $this->offset;
	}

	public function getLineupNumber() {
		return $this->lineupNumber;
	}

	public function setLineupNumber($newlineupNumber) {
		$this->lineupNumber = $newlineupNumber;
		if ($newlineupNumber < 0 || $newlineupnumber > 23) {
			$this->isValid = false;
			$this->error .= 'Invalid lineup number.  Valid numbers are 0 to 23';
		}
		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($newlineupNumber), 0, 2);
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getType() {
		return $this->type;
	}

	protected function generateTeam() {
		if ($this->type == 'hitter') {
			$start = $this->rom->getHitterStart();
		} else {
			$start = $this->rom->getPitcherStart();
		}
		$playeroffset = ($this->offset - $start) / 36;
		$teamoffset = floor($playeroffset / 14);
		$teams = $this->rom->getTeams();
		$this->team = $teams[$teamoffset + 1];
	}

	protected function generatePlayerHex() {
		$start = $this->offset;
		$numcharacters = 36;

		//echo "$start - $end";
		$this->playerHex = $this->rom->getHexString($start, $numcharacters);
		//echo $this->playerHex;
	}

	protected function generateLineupNumber() {
		$this->lineupNumber = $this->hexToDec(substr($this->playerHex, 0, 2));
	}

	protected function generateName() {
		//TODO: make this less ugly
		$lookuptable = $this->rom->getNameHexToChar();

		$this->name = $lookuptable[substr($this->playerHex, 2, 2)];
		$this->name .= $lookuptable[substr($this->playerHex, 4, 2)];
		$this->name .= $lookuptable[substr($this->playerHex, 6, 2)];
		$this->name .= $lookuptable[substr($this->playerHex, 8, 2)];
		$this->name .= $lookuptable[substr($this->playerHex, 10, 2)];
		$this->name .= $lookuptable[substr($this->playerHex, 12, 2)];
		$this->name .= $lookuptable[substr($this->playerHex, 32, 2)];
		$this->name .= $lookuptable[substr($this->playerHex, 34, 2)];
	}

	public function writeToRom() {
		$this->rom->setHexString($this->playerHex, $this->offset);
		$this->rom->save();
	}

	protected function hexToDec($hex) {
		return base_convert($hex, 16, 10);
	}

	protected function decToHex($dec) {
		return str_pad(base_convert($dec, 10, 16), 2, "0", STR_PAD_LEFT);
	}

}

