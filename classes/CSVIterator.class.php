<?php

/**
 * Original from: http://snipplr.com/view/1986/
 */
class CSVIterator implements Iterator {
	const ROW_SIZE = 4096;

	private $filePointer;
	private $currentElement;
	private $rowCounter;
	private $delimiter;
	private $withheadings;
	private $headings = array();

	public function __construct($file, $withheadings = true, $delimiter = ',') {
		$this->filePointer = fopen($file, 'r');
		$this->delimiter = $delimiter;
		$this->withheadings = $withheadings;
		if ($this->withheadings) {
			$this->headings = fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
		}
	}

	public function rewind() {
		$this->rowCounter = 0;
		rewind($this->filePointer);
		if ($this->withheadings) { // skip over the headings
			fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
		}
	}

	public function current() {
		$this->currentElement = fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
		if ($this->withheadings) {
			$this->currentElement = array_combine($this->headings, $this->currentElement);
		}
		$this->rowCounter++;
		return $this->currentElement;
	}

	public function key() {
		return $this->rowCounter;
	}

	public function next() {
		return!feof($this->filePointer);
	}

	public function valid() {
		if (!$this->next()) {
			fclose($this->filePointer);
			return FALSE;
		}
		return TRUE;
	}

}

// end class
?>