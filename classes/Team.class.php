<?php
/**
 * Description of Team
 *
 * @author Peter Meth
 */
class Team {
	protected $offset;
	protected $name;
	protected $abbreviation;
	function __construct($offset) {
		$this->offset = $offset;
	}

	public function getOffset() {
		return $this->offset;
	}

	public function setOffset($offset) {
		$this->offset = $offset;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getAbbreviation() {
		return $this->abbreviation;
	}

	public function setAbbreviation($abbreviation) {
		$this->abbreviation = $abbreviation;
	}


}

?>
