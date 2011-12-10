<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Person
 *
 * @author Peter Meth
 */
class Person {

	protected $_db;
	protected $_table = 'persons';
	protected $_id;
	protected $_firstName;
	protected $_lastName;
	protected $_valid;

	public function __construct(PDO $db, $id) {
		$this->_db = $db;

		$sth = $this->_db->prepare('SELECT * FROM ' . $this->_table . ' WHERE id=:id');
		$sth->execute(array(':id' => $id));
		$persons = $sth->fetchAll();
		if (count($persons) == 1) {
			$this->setFirstName($persons[0]['first_name']);
			$this->setLastName($persons[0]['last_name']);
		} else {
			$this->_valid = false;
		}
	}

	public function getValid() {
		return $this->_valid;
	}

	public function getFirstName() {
		return $this->_firstName;
	}

	public function setFirstName($firstName) {
		$this->_firstName = $firstName;
	}

	public function getLastName() {
		return $this->_lastName;
	}

	public function setLastName($lastName) {
		$this->_lastName = $lastName;
	}

	public function validateNew() {
		if (!empty($this->id)) {
			return array(false, 'This user already exists');
		}

		if ($this->_usernameExists($this->_username)) {
			return array(false, 'Username is in use');
		}

		return $this->_person->validateNew();
	}

}

