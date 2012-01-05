<?php

/**
 * Description of pitcher
 *
 * @author Peter Meth
 */
class Pitcher extends Player {

	protected $eraIndex;
	protected $era;
	protected $throws;
	protected $sinkerspeed;
	protected $curvespeed;
	protected $fastballspeed;
	protected $curveleft;
	protected $curveright;
	protected $stamina;
	protected $sink;

	function __construct($offset) {
		parent::__construct($offset);
		$this->type = 'Pitcher';
	}

	public function getThrows() {
		return $this->throws;
	}

	public function setThrows($throws) {
		switch ($throws) {
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
//		$this->playerHex = substr_replace($this->playerHex, $throwshex, 15, 1);
		$this->throws = $throws;
	}

	public function getEra() {
		return $this->era;
	}

	public function setEra($era) {
		$this->era = $era;
		
		/*
		$eratable = $this->rom->getEraTable();
		//print_r($eratable);
		$found = array_search($era, $eratable);
		if(false !== $found) {
			$this->eraIndex = $found;
		} else {
			// find second occurrence of 0.00.  first occurrence is the real 0.00
			$erakeys = array_keys($eratable, "0.00");
			$this->eraIndex = $erakeys[1];
			$eratable[$this->eraIndex] = $era;
			$this->rom->setEraTable($eratable);
		}
		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($this->eraIndex), 16, 2);
		*/
	}

	public function getEraIndex() {
		return $this->eraIndex;
	}

	public function setEraIndex($eraIndex) {
		$this->eraIndex = $eraIndex;
	}

	public function getSinkerspeed() {
		return $this->sinkerspeed;
	}

	public function setSinkerspeed($sinkerspeed) {
		$this->sinkerspeed = $sinkerspeed;
		if ($sinkerspeed < 0 || $sinkerspeed > 255) {
			$this->isValid = false;
			$this->error .= 'Invalid sinker speed.  Valid numbers are 0 to 255';
		} elseif (!$this->acceptAbnormal && ($sinkerspeed < 130 || $sinkerspeed > 200)) {
			$this->isValid = false;
			$this->error .= 'Normal sinker speed range is 130 - 200, to go outside that you must select Accept Abnormal';
		}
//		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($sinkerspeed), 18, 2);
	}

	public function getCurvespeed() {
		return $this->curvespeed;
	}

	public function setCurvespeed($curvespeed) {
		$this->curvespeed = $curvespeed;
		if ($curvespeed < 0 || $curvespeed > 255) {
			$this->isValid = false;
			$this->error .= 'Invalid curve speed.  Valid numbers are 0 to 255';
		} elseif (!$this->acceptAbnormal && ($curvespeed < 150 || $curvespeed > 210)) {
			$this->isValid = false;
			$this->error .= 'Normal curve speed range is 150 - 210, to go outside that you must select Accept Abnormal';
		}
//		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($curvespeed), 20, 2);
	}

	public function getFastballspeed() {
		return $this->fastballspeed;
	}

	public function setFastballspeed($fastballspeed) {
		$this->fastballspeed = $fastballspeed;
		if ($fastballspeed < 0 || $fastballspeed > 255) {
			$this->isValid = false;
			$this->error .= 'Invalid fastball speed.  Valid numbers are 0 to 255';
		} elseif (!$this->acceptAbnormal && ($fastballspeed < 160 || $fastballspeed > 230)) {
			$this->isValid = false;
			$this->error .= 'Normal fastball speed range is 160 - 230, to go outside that you must select Accept Abnormal';
		}
//		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($fastballspeed), 22, 2);
	}

	public function getCurveleft() {
		return $this->curveleft;
	}

	public function setCurveleft($curveleft) {
		$this->curveleft = $curveleft;
		if ($curveleft < 0 || $curveleft > 15) {
			$this->isValid = false;
			$this->error .= 'Invalid curve left.  Valid numbers are 0 to 15';
		}
//		$this->playerHex = substr_replace($this->playerHex, substr($this->decToHex($curveleft), -1), 24, 1);
	}

	public function getCurveright() {
		return $this->curveright;
	}

	public function setCurveright($curveright) {
		$this->curveright = $curveright;
		if ($curveright < 0 || $curveright > 15) {
			$this->isValid = false;
			$this->error .= 'Invalid curve right.  Valid numbers are 0 to 15';
		}
//		$this->playerHex = substr_replace($this->playerHex, substr($this->decToHex($curveright), -1), 25, 1);
	}

	public function getStamina() {
		return $this->stamina;
	}

	public function setStamina($stamina) {
		$this->stamina = $stamina;
		if ($stamina < 0 || $stamina > 255) {
			$this->isValid = false;
			$this->error .= 'Invalid stamina.  Valid numbers are 0 to 255';
		} elseif (!$this->acceptAbnormal && ($stamina < 12 || $stamina > 90)) {
			$this->isValid = false;
			$this->error .= 'Normal stamina range is 12 - 90, to go outside that you must select Accept Abnormal';
		}
//		$this->playerHex = substr_replace($this->playerHex, $this->decToHex($stamina), 26, 2);
	}

	public function getSink() {
		return $this->sink;
	}

	public function setSink($sink) {
		$this->sink = $sink;
		if ($sink < 0 || $sink > 16) {
			$this->isValid = false;
			$this->error .= 'Invalid sink.  Valid numbers are 0 to 16';
		}
//		$this->playerHex = substr_replace($this->playerHex, substr($this->decToHex($sink), -1), 14, 1);
	}

}
