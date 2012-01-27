<?php

/**
 * Description of RBI3AndyBRom
 *
 * @author Peter Meth
 */
class RBI30TeamRom extends RBI3Rom {

	function __construct($filename) {
		parent::__construct($filename);
		$this->hexOffsets = array(
			 'hitterstart' => '6010',
			 'hitterend' => '7f90',
			 'pitcherstart' => '18010',
			 'pitcherend' => '19f48',
			 'teamstart' => '9e1d',
			 'teamend' => '9e5d',
			 'era1start' => '19d88',
			 'era1end' => '19e88',
			 'era2start' => '19f48',
		);
	}

}
