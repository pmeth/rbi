<?php

class Hitter extends Player {

	protected $bats;
	protected $position;
	protected $homeruns;
	protected $average;
	protected $power;
	protected $contact;
	protected $speed;


	public function __construct(Rom $rom, $offset) {
		parent::__construct($rom, $offset);

		$this->isValid = true;
		if ($this->type != 'hitter') {
			$this->isValid = false;
			$this->error .= '<br>This offset does not represent a hitter';
		}
		$this->generateBats();
		$this->generateHomeruns();
		$this->generateAverage();
		$this->generatePosition();
		$this->generatePower();
		$this->generateContact();
		$this->generateSpeed();
	}

	//TODO: update all the setters to actually change the hex.
	public function setHomeruns($newhomeruns) {
		$this->homeruns = $newhomeruns;
	}

	public function getHomeruns() {
		return $this->homeruns;
	}

	public function setBats($bats) {
		switch ($bats) {
			case "S":
				$lefty = '00';
				$switchhitter = '01';
				break;
			case "R":
				$lefty = '00';
				$switchhitter = '00';
				break;
			case "L";
				$lefty = '01';
				$switchhitter = '00';
				break;
			default:
				$this->isValid = false;
				$this->error .= 'Invalid bats.  Valid options are S, R, L';
				$lefty = '00';
				$switchhitter = '00';
		}
		$this->playerHex = substr_replace($this->playerHex, $lefty, 14, 2);
		$this->playerHex = substr_replace($this->playerHex, $switchhitter, 30, 2);
		$this->bats = $bats;

	}

	public function getBats() {
		return $this->bats;
	}

	public function setPower($newpower) {
		$this->power = $newpower;
	}

	public function getPower() {
		return $this->power;
	}

	public function setContact($newcontact) {
		$this->contact = $newcontact;
	}

	public function getContact() {
		return $this->contact;
	}

	public function setSpeed($newspeed) {
		$this->speed = $newspeed;
	}

	public function getSpeed() {
		return $this->speed;
	}

	public function setPosition($newposition) {
		$this->position = $newposition;
	}

	public function getPosition() {
		return $this->position;
	}

	public function setAverage($newaverage) {
		$this->average = $newaverage;
	}

	public function getAverage() {
		return $this->average;
	}

	public function generateHomeruns() {
		$this->homeruns = $this->hexToDec(substr($this->playerHex, 18, 2));
	}

	public function generateBats() {
		if (substr($this->playerHex, 30, 2) == "01") {
			$this->bats = "S";
		} elseif (substr($this->playerHex, 14, 2) == "00") {
			$this->bats = "R";
		} else {
			$this->bats = "L";
		}
	}

	public function generatePosition() {
		if (substr($this->playerHex, 28, 2) == "00") {
			$this->position = "C";
		} elseif (substr($this->playerHex, 28, 2) == "01") {
			$this->position = "I";
		} else {
			$this->position = "O";
		}
	}

	public function generateAverage() {
		$this->average = 111 + $this->hexToDec(substr($this->playerHex, 16, 2));
	}

	public function generatePower() {
		$this->power = $this->hexToDec(substr($this->playerHex, 24, 2) . substr($this->playerHex, 22, 2));
	}

	public function generateContact() {
		$this->contact = $this->hexToDec(substr($this->playerHex, 20, 2));
	}

	public function generateSpeed() {
		$this->speed = $this->hexToDec(substr($this->playerHex, 26, 2));
	}


}

