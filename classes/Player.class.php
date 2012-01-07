<?php

class Player {

	protected $offset;
	protected $name;
	protected $type;
	protected $lineupNumber;
	protected $team;
	protected $acceptAbnormal;

	public function __construct($offset) {
		$this->acceptAbnormal = false;
		$this->offset = $offset;
	}

	public function getAcceptAbnormal() {
		return $this->acceptAbnormal;
	}

	public function setAcceptAbnormal($acceptAbnormal) {
		$this->acceptAbnormal = $acceptAbnormal;
	}

	public function getTeam() {
		return $this->team;
	}

	public function setTeam($team) {
		$this->team = $team;
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
		if ($newlineupNumber < 0 || $newlineupNumber > 23) {
			throw new Exception('Invalid lineup number.  Valid numbers are 0 to 23');
		}
		//$this->playerHex = substr_replace($this->playerHex, $this->decToHex($newlineupNumber), 0, 2);
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

}

