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

	public function __construct(RBI3Rom $rom, $offset) {
		$this->rom = $rom;
		$this->offset = $offset;
		if ($offset >= $rom->getHitterStart() && $offset <= $rom->getHitterEnd()) {
			$this->type = "hitter";
		} elseif ($offset >= $rom->getPitcherStart() && $offset <= $rom->getPitcherEnd()) {
			$this->type = "pitcher";
		} else {
			throw new Exception('Offset does not map to a player.');
		}

		$this->generatePlayerHex();
		$this->generateLineupNumber();
		$this->generateName();
		$this->generateTeam();
	}

	public function valid() {
		return $this->playerHex != "000000000000000000000000000000000000";
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
	}

	public function setTeam($team) {
		$this->team = $team;
	}

	public function getType() {
		return $this->type;
	}

	protected function generateTeam() {
		
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

	protected function hexToDec($hex) {
		return base_convert($hex, 16, 10);
	}

}

