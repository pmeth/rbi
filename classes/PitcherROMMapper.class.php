<?php

/**
 * Description of PitcherMapper
 *
 * @author Peter Meth
 */
class PitcherROMMapper extends PlayerROMMapper {

	protected $minOffset;
	protected $maxOffset;

	function __construct(RBI3Rom $rom) {
		parent::__construct($rom, 'Pitcher');

		$this->minOffset = $rom->getPitcherStart();
		$this->maxOffset = $rom->getPitcherEnd();
	}

	public function get($offset) {
		$playerhex = $this->getPitcherHex($offset);
		if ($playerhex) {
			$pitcher = new Pitcher($offset);

			// these 3 lines are repetitious from the player version of get()
			$pitcher->setLineupNumber($this->getLineupNumberFromHex($playerhex));
			$pitcher->setName($this->getNameFromHex($playerhex));
			$pitcher->setTeam($this->getTeamFromOffset($offset));

			$pitcher->setEra($this->getEraFromHex($playerhex));
			$pitcher->setThrows($this->getThrowsFromHex($playerhex));
			$pitcher->setSinkerSpeed($this->getSinkerSpeedFromHex($playerhex));
			$pitcher->setCurveSpeed($this->getCurveSpeedFromHex($playerhex));
			$pitcher->setFastballSpeed($this->getFastballSpeedFromHex($playerhex));
			$pitcher->setCurveLeft($this->getCurveLeftFromHex($playerhex));
			$pitcher->setCurveRight($this->getCurveRightFromHex($playerhex));
			$pitcher->setStamina($this->getStaminaFromHex($playerhex));
			$pitcher->setSink($this->getSinkFromHex($playerhex));

			return $pitcher;
		} else {
			return false;
		}
	}

	protected function getPitcherHex($offset) {
		// make sure offset is in the valid range and that it is a valid offset
		if ($offset >= $this->minOffset && $offset <= $this->maxOffset && ($offset - $this->minOffset) % $this->offsetSize == 0) {
			return $this->getPlayerHex($offset);
		} else {
			return false;
		}
	}

	protected function getThrowsFromHex($playerhex) {
		$throws = "";
		switch (substr($playerhex, 15, 1)) {
			case 0:
				$throws = "R";
				break;
			case 1:
				$throws = "L";
				break;
			case 2:
				$throws = "SR";
				break;
			case 3:
				$throws = "SL";
				break;
			default:
				throw new Exception('Invalid throwing arm.');
		}
		return $throws;
	}

	protected function getEraFromHex($playerhex) {
		$eraindex = $this->hexToDec(substr($playerhex, 16, 2));
		$eratable = $this->rom->getEraTable();
		return $eratable[$eraindex];
	}

	protected function getSinkerSpeedFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 18, 2));
	}

	protected function getCurveSpeedFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 20, 2));
	}

	protected function getFastballSpeedFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 22, 2));
	}

	protected function getCurveLeftFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 24, 1));
	}

	protected function getCurveRightFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 25, 1));
	}

	protected function getStaminaFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 26, 2));
	}

	protected function getSinkFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 14, 1));
	}

}

?>
