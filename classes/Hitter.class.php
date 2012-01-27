<?php

class Hitter extends Player {

	protected $bats;
	protected $position;
	protected $homeruns;
	protected $average;
	protected $power;
	protected $contact;
	protected $speed;

	function __construct($offset) {
		parent::__construct($offset);
		$this->type = 'Hitter';
	}

	public function setHomeruns($homeruns) {
		$this->homeruns = $homeruns;
		if ($homeruns < 0 || $homeruns > 99) {
			$this->isValid = false;
			$this->error .= 'Invalid home runs.  Valid numbers are 0 to 99';
		}
	}

	public function getHomeruns() {
		return $this->homeruns;
	}

	public function setBats($bats) {
		$this->bats = $bats;
	}

	public function getBats() {
		return $this->bats;
	}

	public function setPower($power) {
		$this->power = $power;
		if ($power < 0 || $power > 65535) {
			$this->isValid = false;
			$this->error .= 'Invalid power.  Valid numbers are 0 to 65535';
		} elseif (!$this->acceptAbnormal && ($power < 500 || $power > 1200)) {
			$this->isValid = false;
			$this->error .= 'Normal Power range is 500 - 1200, to go outside that you must select Accept Abnormal';
		}

	}

	public function getPower() {
		return $this->power;
	}

	public function setContact($contact) {
		$this->contact = $contact;
		if ($contact < 0 || $contact > 255) {
			$this->isValid = false;
			$this->error .= 'Invalid contact.  Valid numbers are 0 to 255';
		} elseif (!$this->acceptAbnormal && ($contact > 50)) {
			$this->isValid = false;
			$this->error .= 'Normal Contact range is 0 - 50, to go outside that you must select Accept Abnormal';
		}
	}

	public function getContact() {
		return $this->contact;
	}

	public function setSpeed($speed) {
		$this->speed = $speed;
		if ($speed < 0 || $speed > 255) {
			$this->isValid = false;
			$this->error .= 'Invalid speed.  Valid numbers are 0 to 99';
		} elseif (!$this->acceptAbnormal && ($speed < 120 || $speed > 180)) {
			$this->isValid = false;
			$this->error .= 'Normal speed range is 120 - 180, to go outside that you must select Accept Abnormal';
		}
	}

	public function getSpeed() {
		return $this->speed;
	}

	public function setPosition($position) {
		$this->position = $position;
	}

	public function getPosition() {
		return $this->position;
	}

	public function setAverage($average) {
		$this->average = $average;
		if ($average < 111 || $average > 366) {
			$this->isValid = false;
			$this->error .= 'Invalid average.  Valid numbers are 111 to 366';
		}
	}

	public function getAverage() {
		return $this->average;
	}

}

