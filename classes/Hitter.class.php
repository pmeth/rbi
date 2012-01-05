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
//		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($homeruns), 18, 2);
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
//		$this->playerHex = substr_replace($this->playerHex, $lefty, 14, 2);
//		$this->playerHex = substr_replace($this->playerHex, $switchhitter, 30, 2);
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

		/* 		
		  // POWER uses 2 hex digits (4 characters total)
		  $powerhex = str_pad($this->decToHex($power), 4, "0", STR_PAD_LEFT);
		  // power is stored in the rom with the 2 digits reversed for some reason, so let's reverse them
		  $power1 = substr($powerhex, -2);

		  $power2 = substr($powerhex, 0, 2);
		  $this->playerHex = substr_replace($this->playerHex, $power1 . $power2, 22, 4);

		 */
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
//		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($contact), 20, 2);
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
//		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($speed), 26, 2);
	}

	public function getSpeed() {
		return $this->speed;
	}

	public function setPosition($position) {
		$this->position = $position;
		switch ($position) {
			case "I":
				$positionhex = '01';
				break;
			case "O":
				$positionhex = '10';
				break;
			case "C";
				$positionhex = '00';
				break;
			default:
				$this->isValid = false;
				$this->error .= 'Invalid position.  Valid options are I, O, C';
				$positionhex = '00';
		}
//		$this->playerHex = substr_replace($this->playerHex, $positionhex, 28, 2);
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
		// average is set by adding 111 to the hex value
//		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($average - 111), 16, 2);
	}

	public function getAverage() {
		return $this->average;
	}

}

