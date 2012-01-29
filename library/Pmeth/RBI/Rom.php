<?php

/**
 * Description of Rom
 *
 * @author Peter Meth
 */
 namespace Pmeth\RBI;
class Rom {

	protected $filename;
	protected $romBinary;
	protected $romHex;

	function __construct($filename) {
		$this->filename = $filename;

		$handle = @fopen($this->filename, "r+");
		if(is_resource($handle)) {
			$this->romBinary = fread($handle, filesize($this->filename));
			fclose($handle);
			$this->romHex = bin2hex($this->romBinary);
		} else {
			throw new Exception('Error trying to load the file ' . $this->filename . '. Perhaps you forgot to put it there and make it writeable.');
		}
	}

	public function getFilename() {
		return $this->filename;
	}

	public function getRomBinary() {
		return $this->romBinary;
	}

	public function getRomHex() {
		return $this->romHex;
	}

	public function getHexString($start, $numcharacters) {
		return substr($this->romHex, $start, $numcharacters);
	}

	public function setHexString($newhexstring, $start) {
		$this->romHex = substr_replace($this->romHex, $newhexstring, $start, strlen($newhexstring));

		$this->romBinary = $this->hexToBin($this->romHex);
	}

	public function save() {
		$handle = @fopen($this->filename, "w");
		if(is_resource($handle)) {
			fwrite($handle, $this->romBinary);
			fclose($handle);
		} else {
			throw new Exception('Error trying to save to the file ' . $this->filename . '. Perhaps you forgot to make it writeable.');
		}
	}

	public function setFilename($filename) {
		$this->filename = $filename;
	}

	public function hexToDec($hex) {
		return base_convert($hex, 16, 10);
	}

	public function hexToBin($hex) {
		return pack("H*", $hex);
	}

}

