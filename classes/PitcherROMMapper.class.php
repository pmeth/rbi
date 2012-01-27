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
			$teammapper = new TeamROMMapper($this->rom);
			$pitcher->setTeam($teammapper->get($this->getTeamOffset($offset)));

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

	public function save(Pitcher $pitcher) {
		parent::save($pitcher);
		$newhex = $this->getPitcherHex($pitcher->getOffset());

		// thows
		switch ($pitcher->getThrows()) {
			case "R":
				$throwshex = '0';
				break;
			case "L";
				$throwshex = '1';
				break;
			case "SR":
				$throwshex = '2';
				break;
			case "SL":
				$throwshex = '3';
				break;
			default:
				throw new Exception('Invalid throws.  Valid options are R, L, SR, SL');
				$throwshex = '0';
		}
		$newhex = substr_replace($newhex, $throwshex, 15, 1);

		// era
		$era = $pitcher->getEra();
		$eraindex = null;
		$eratable = $this->rom->getEraTable();

		$found = array_search($era, $eratable);
		if(false !== $found) {
			$eraindex = $found;
		} else {
			// write a new record to the ERA table
			// find second occurrence of 0.00.  first occurrence is the real 0.00
			// todo: move this functionality to the RBI3Rom class
			$erakeys = array_keys($eratable, "0.00");
			$eraindex = $erakeys[1];
			$eratable[$eraindex] = $era;
			$this->rom->setEraTable($eratable);
		}
		$newhex = substr_replace($newhex, $this->decToHex($eraindex), 16, 2);

		// sinkerspeed
		$newhex = substr_replace($newhex, $this->decToHex($pitcher->getSinkerspeed()), 18, 2);

		// curvespeed
		$newhex = substr_replace($newhex, $this->decToHex($pitcher->getCurvespeed()), 20, 2);
		
		// fastballspeed
		$newhex = substr_replace($newhex, $this->decToHex($pitcher->getFastballspeed()), 22, 2);

		// curveleft
		$newhex = substr_replace($newhex, substr($this->decToHex($pitcher->getCurveleft()), -1), 24, 1);
	
		// curveright
		$newhex = substr_replace($newhex, substr($this->decToHex($pitcher->getCurveright()), -1), 25, 1);

		// stamina
		$newhex = substr_replace($newhex, $this->decToHex($pitcher->getStamina()), 26, 2);

		// sink
		$newhex = substr_replace($newhex, substr($this->decToHex($pitcher->getSink()), -1), 14, 1);

		$this->rom->setHexString($newhex, $pitcher->getOffset());
		$this->rom->save();
	}

	public function validate(Pitcher $pitcher) {
		// todo: add some real validation
		return true;
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
