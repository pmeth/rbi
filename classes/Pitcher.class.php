<?php

/**
 * Description of pitcher
 *
 * @author Peter Meth
 */
class Pitcher extends Player {

	protected $era;
	protected $throws;

	protected $sinkerspeed;
	protected $curvespeed;
	protected $fastballspeed;
	protected $curveleft;
	protected $curveright;
	protected $stamina;
	protected $sink;

	function __construct(Rom $rom, $offset) {
		parent::__construct($rom, $offset);

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


	
	
	//TODO: update all the setters to actually change the hex.
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
		$eraindex = $this->hexToDec(substr($this->playerHex, 16, 2));
		$eratable = $this->rom->getEratable();
		$this->era = $eratable[$eraindex];
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
