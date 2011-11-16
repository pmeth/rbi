<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pitcher
 *
 * @author Peter Meth
 */
class pitcher extends Player {

	protected $era;
	protected $throws;
	protected $eratable;
	protected $sinkerspeed;
	protected $curvespeed;
	protected $fastballspeed;
	protected $curveleft;
	protected $curveright;
	protected $stamina;
	protected $sink;

	function __construct($offset) {
		parent::__construct($offset);

		$this->generateEra();
		$this->generateThrows();
		$this->generateSinkerSpeed();
		$this->generateCurveSpeed();
		$this->generateFastballSpeed();
		$this->generateCurveLeft();
		$this->generateCurveRight();
		$this->generateStamina();
		$this->generateSink();
	}

	public function generateThrows() {
		switch (substr($this->playerHex, 15, 1)) {
			case 0:
				$this->throws = "R";
				break;
			case 1:
				$this->throws = "L";
				break;
			case 2:
				$this->throws = "SR";
				break;
			case 3:
				$this->throws = "SL";
				break;
			default:
				throw new Exception('Invalid throwing arm.');
		}
	}

	protected function generateEra() {
		$this->generateEraTable();
		$eraindex = $this->hexToDec(substr($this->playerHex, 16, 2));
		$this->era = $this->eratable[$eraindex];
	}

	protected function generateEraTable() {
		$this->eratable = array();
// first, let's read the era tables
// the first table is 19d88 - 19e94
		$start = $this->hexToDec("19d88") * 2;
		$end = $this->hexToDec("19e94") * 2;
		$numcharacters = $end - $start;

//echo "$start - $end";
		$newstring = substr($this->romHex, $start, $numcharacters);
		$chunked = chunk_split($newstring, 2, ",");
		$era1hex = explode(",", $chunked);

// strip off last entry (it's blank)
		unset($era1hex[count($era1hex) - 1]);
//print_r($era1hex);
// the second table starts at 19f10, and has half the number of characters as table 1
		$start = $this->hexToDec("19f48") * 2;
		$numcharacters = round($numcharacters / 2);

//echo "$start - $end";
		$newstring = substr($this->romHex, $start, $numcharacters);
		$chunked = chunk_split($newstring, 1, ",");
		$era2hex = explode(",", $chunked);

// strip off last entry (it's blank)
		unset($era2hex[count($era2hex) - 1]);
//print_r($era2hex);
// now we combine them together
		foreach ($era1hex as $key => $value) {
			$this->eratable[] = substr($value, 0, 1) . "." . substr($value, 1, 1) . $era2hex[$key];
		}
//print_r($eras);
// now we should have a nice era reference table.  we will be using it in a moment
	}

	public function getThrows() {
		return $this->throws;
	}

	public function setThrows($throws) {
		$this->throws = $throws;
	}

	public function getEra() {
		return $this->era;
	}

	public function setEra($era) {
		$this->era = $era;
	}

	protected function generateSinkerSpeed() {
		$this->sinkerspeed = $this->hexToDec(substr($this->playerHex, 18, 2));
	}

	protected function generateCurveSpeed() {
		$this->curvespeed = $this->hexToDec(substr($this->playerHex, 20, 2));
	}

	protected function generateFastballSpeed() {
		$this->fastballspeed = $this->hexToDec(substr($this->playerHex, 22, 2));
	}

	protected function generateCurveLeft() {
		$this->curveleft = $this->hexToDec(substr($this->playerHex, 24, 1));
	}

	protected function generateCurveRight() {
		$this->curveright = $this->hexToDec(substr($this->playerHex, 25, 1));
	}

	protected function generateStamina() {
		$this->stamina = $this->hexToDec(substr($this->playerHex, 26, 2));
	}

	protected function generateSink() {
		$this->sink = $this->hexToDec(substr($this->playerHex, 30, 2));
	}

	public function getSinkerspeed() {
		return $this->sinkerspeed;
	}

	public function setSinkerspeed($sinkerspeed) {
		$this->sinkerspeed = $sinkerspeed;
	}

	public function getCurvespeed() {
		return $this->curvespeed;
	}

	public function setCurvespeed($curvespeed) {
		$this->curvespeed = $curvespeed;
	}

	public function getFastballspeed() {
		return $this->fastballspeed;
	}

	public function setFastballspeed($fastballspeed) {
		$this->fastballspeed = $fastballspeed;
	}

	public function getCurveleft() {
		return $this->curveleft;
	}

	public function setCurveleft($curveleft) {
		$this->curveleft = $curveleft;
	}

	public function getCurveright() {
		return $this->curveright;
	}

	public function setCurveright($curveright) {
		$this->curveright = $curveright;
	}

	public function getStamina() {
		return $this->stamina;
	}

	public function setStamina($stamina) {
		$this->stamina = $stamina;
	}

	public function getSink() {
		return $this->sink;
	}

	public function setSink($sink) {
		$this->sink = $sink;
	}

}
