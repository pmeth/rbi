<?php

/**
 * Description of HitterROMMapper
 *
 * @author Peter Meth
 */
 namespace Pmeth\RBI;
class HitterROMMapper extends PlayerROMMapper {

	protected $minOffset;
	protected $maxOffset;

	function __construct(RBI3Rom $rom) {
		parent::__construct($rom, 'Hitter');

		$this->minOffset = $rom->getHitterStart();
		$this->maxOffset = $rom->getHitterEnd();
	}

	public function get($offset) {
		$playerhex = $this->getHitterHex($offset);
		if ($playerhex) {
			$hitter = new Hitter($offset);

			// we can get the lineupnumber, player name, and team object from the parent
			$player = parent::get($offset);
			$hitter->setLineupNumber($player->getLineupNumber());
			$hitter->setName($player->getName());
			$hitter->setTeam($player->getTeam());

			$hitter->setAverage($this->getAverageFromHex($playerhex));
			$hitter->setBats($this->getBatsFromHex($playerhex));
			$hitter->setContact($this->getContactFromHex($playerhex));
			$hitter->setHomeruns($this->getHomerunsFromHex($playerhex));
			$hitter->setPosition($this->getPositionFromHex($playerhex));
			$hitter->setPower($this->getPowerFromHex($playerhex));
			$hitter->setSpeed($this->getSpeedFromHex($playerhex));

			return $hitter;
		} else {
			return false;
		}
	}

	public function save(Hitter $hitter) {
		parent::save($hitter);
		$newhex = $this->getHitterHex($hitter->getOffset());

		// homeruns
		$newhex = substr_replace($newhex, $this->decToHex($hitter->getHomeruns()), 18, 2);

		// bats
		switch ($hitter->getBats()) {
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

		$newhex = substr_replace($newhex, $lefty, 14, 2);
		$newhex = substr_replace($newhex, $switchhitter, 30, 2);

		// power
		// POWER uses 2 hex digits (4 characters total)
		$powerhex = str_pad($this->decToHex($hitter->getPower()), 4, "0", STR_PAD_LEFT);
		// power is stored in the rom with the 2 digits reversed for some reason, so let's reverse them
		$power1 = substr($powerhex, -2);
		$power2 = substr($powerhex, 0, 2);
		$newhex = substr_replace($newhex, $power1 . $power2, 22, 4);

		// contact
		$newhex = substr_replace($newhex, $this->decToHex($hitter->getContact()), 20, 2);

		// speed
		$newhex = substr_replace($newhex, $this->decToHex($hitter->getSpeed()), 26, 2);

		// position
		switch ($hitter->getPosition()) {
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
//				$this->isValid = false;
//				$this->error .= 'Invalid position.  Valid options are I, O, C';
				$positionhex = '00';
		}
		$newhex = substr_replace($newhex, $positionhex, 28, 2);

		// average
		// average is set by adding 111 to the hex value
		$newhex = substr_replace($newhex, $this->decToHex($hitter->getAverage() - 111), 16, 2);

		$this->rom->setHexString($newhex, $hitter->getOffset());
		$this->rom->save();
	}

	public function validate(Hitter $hitter) {
		// todo: add some real validation
		return true;
	}

	protected function getHitterHex($offset) {
		// make sure offset is in the valid range and that it is a valid offset
		if ($offset >= $this->minOffset && $offset <= $this->maxOffset && ($offset - $this->minOffset) % $this->offsetSize == 0) {
			return $this->getPlayerHex($offset);
		} else {
			return false;
		}
	}

	public function getHomerunsFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 18, 2));
	}

	public function getBatsFromHex($playerhex) {
		$bats = "";
		if (substr($playerhex, 30, 2) == "01") {
			$bats = "S";
		} elseif (substr($playerhex, 14, 2) == "00") {
			$bats = "R";
		} else {
			$bats = "L";
		}
		return $bats;
	}

	public function getPositionFromHex($playerhex) {
		$position = "";
		if (substr($playerhex, 28, 2) == "00") {
			$position = "C";
		} elseif (substr($playerhex, 28, 2) == "01") {
			$position = "I";
		} else {
			$position = "O";
		}
		return $position;
	}

	public function getAverageFromHex($playerhex) {
		return 111 + $this->hexToDec(substr($playerhex, 16, 2));
	}

	public function getPowerFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 24, 2) . substr($playerhex, 22, 2));
	}

	public function getContactFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 20, 2));
	}

	public function getSpeedFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 26, 2));
	}

}

