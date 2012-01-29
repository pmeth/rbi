<?php

require_once('PlayerTest.php');
require_once dirname(__FILE__) . '/../../classes/Hitter.class.php';

/**
 * Description of HitterTest
 *
 * @author Peter Meth
 */
class HitterTest extends RBI\PlayerTest {

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new RBI\Hitter('123456');
		$this->object->setAcceptAbnormal(true);
		$this->object->setTeam('AT');
		$this->object->setPosition('O');
		$this->object->setName('Gonzales');
		$this->object->setLineupNumber(3);
	}

	/**
	 * @covers {className}::{origMethodName}
	 */
	public function testGetPosition() {
		$this->assertEquals('O', $this->object->getPosition());
	}

	public function testGetType() {
		$this->assertEquals('Hitter', $this->object->getType());
	}


}

