<?php
namespace Pmeth\RBI;
class Player {

	protected $offset;
	protected $name;
	protected $type;
	protected $lineupNumber;
	protected $team;
	protected $acceptAbnormal;
	protected $isValid;
	protected $error;
	protected $playerHex;

	public function __construct($offset) {
		$this->acceptAbnormal = false;
		$this->offset = $offset;
		$this->isValid = true;
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

	public function valid() {
		// todo: move this functionality into some other structure, perhaps a validator object
		return $this->isValid && $this->playerHex != "000000000000000000000000000000000000";
	}

}

