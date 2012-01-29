<?php
/**
 * Description of BaseROMMapper
 *
 * @author Peter Meth
 */
 namespace Pmeth\RBI;
class BaseROMMapper {
	protected $rom;
	protected $entityname;

	function __construct($rom, $entityname) {
		$this->rom = $rom;
		$this->entityname = $entityname;
	}

}


