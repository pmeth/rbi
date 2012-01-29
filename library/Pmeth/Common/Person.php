<?php
namespace Pmeth\Common;
/**
 * Description of Person
 *
 * @author Peter Meth
 */


class Person  implements \Serializable {

	protected $_db;
	protected $_table = 'persons';
	protected $_id;
	protected $_firstName;
	protected $_lastName;
	protected $_email;
	protected $_valid;
	protected $_error;

	public function __construct(\PDO $db, $id) {
		$this->_db = $db;

		$query = "
			SELECT
				first_name,
				last_name,
				email
			FROM
				{$this->_table}
			WHERE id=:id
		";
		$params = array(':id' => $id);
		$sth = $this->_db->prepare($query);
		$sth->execute($params);
		
		$persons = $sth->fetchAll();
		if (count($persons) == 1) {
			$this->setFirstName($persons[0]['first_name']);
			$this->setLastName($persons[0]['last_name']);
			$this->setEmail($persons[0]['email']);
			$this->_id = $id;
			$this->_valid = true;
		} else {
			$this->_valid = false;
		}
	}

	public function serialize() {
		$data = array(
			 '_id' => $this->_id,
			 '_firstName' => $this->_firstName,
			 '_lastName' => $this->_lastName,
			 '_email' => $this->_email,
			 '_valid' => $this->_valid,
			 '_error' => $this->_error,
		);
		return serialize($data);
	}

	public function unserialize($serialized) {
		$this->_id = $serialized['_id'];
		$this->_firstName = $serialized['_firstName'];
		$this->_lastName = $serialized['_lastName'];
		$this->_email = $serialized['_email'];
		$this->_valid = $serialized['_valid'];
		$this->_error = $serialized['_error'];
		return $this;
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

	public function getEmail() {
		return $this->_email;
	}

	public function setEmail($email) {
		$this->_email = $email;
	}

	public function validateNew() {
		$this->_error = null;
		if (!empty($this->_id)) {
			$this->_error = 'This person already exists';
			return false;
		}

		if ($this->_emailExists($this->_email)) {
			$this->_error = 'The email address is already in use';
			return false;
		}
		
		return true;
	}

	public function _emailExists($email, $id = 0) {
		$sth = $this->_db->prepare('SELECT * FROM ' . $this->_table . ' WHERE email=:email AND id!=:id');
		$sth->execute(array(':email' => $email, ':id' => $id));
		$persons = $sth->fetchAll();
		return count($persons) > 0;
	}


	public function getError() {
		return $this->_error;
	}

	public function save() {
		$query = $this->_table . ' SET first_name=:first_name, last_name=:last_name, email=:email';
		$params = array(
			 ':first_name' => $this->_firstName,
			 ':last_name' => $this->_lastName,
			 ':email' => $this->_email,
		);
		if (empty($this->_id)) {
			$query = 'INSERT INTO ' . $query;
		} else {
			$query = 'UPDATE ' . $query . ' WHERE id=:id';
			$params[':id'] = $this->_id;
		}

		$sth = $this->_db->prepare($query);
		$sth->execute($params);

		if (empty($this->_id)) {
			$this->_id = $this->_db->lastInsertId();
		}
	}

	public function delete() {
		$query = 'DELETE FROM ' . $this->_table . ' WHERE id=:id';
		$params = array(':id' => $this->_id);
		$sth = $this->_db->prepare($query);
		$sth->execute($params);

		$this->_id = null;
		$this->_firstName = null;
		$this->_lastName = null;
		$this->_valid = false;
	}

	public function getId() {
		return $this->_id;
	}

}

