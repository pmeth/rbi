<?php

/**
 * Description of HitterROMMapper
 *
 * @author Peter Meth
 */
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
			$player = new Hitter($offset);

			// these 3 lines are repetitious from the player version of get()
			$player->setLineupNumber($this->getLineupNumberFromHex($playerhex));
			$player->setName($this->getNameFromHex($playerhex));
			$teammapper = new TeamROMMapper($this->rom);
			$player->setTeam($teammapper->get($this->getTeamOffset($offset)));

			$player->setAverage($this->getAverageFromHex($playerhex));
			$player->setBats($this->getBatsFromHex($playerhex));
			$player->setContact($this->getContactFromHex($playerhex));
			$player->setHomeruns($this->getHomerunsFromHex($playerhex));
			$player->setPosition($this->getPositionFromHex($playerhex));
			$player->setPower($this->getPowerFromHex($playerhex));
			$player->setSpeed($this->getSpeedFromHex($playerhex));

			return $player;
		} else {
			return false;
		}
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

